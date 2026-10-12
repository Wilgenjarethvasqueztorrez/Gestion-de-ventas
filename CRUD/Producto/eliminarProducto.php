<?php
require_once __DIR__ . "/../../Config/Ruta.php";
require_once BASE_PATH . "Config/Conexion.php";

$id = filter_input(INPUT_GET, 'Id', FILTER_VALIDATE_INT);
if (!$id) {
    header("Location: " . BASE_URL . "pages/producto.php?error=no_existe");
    exit;
}

$verificar = $conexion->prepare("SELECT COUNT(*) AS total FROM ventas_detalles WHERE producto_id = ?");
$verificar->bind_param("i", $id);
$verificar->execute();
$resultado = $verificar->get_result()->fetch_assoc();

if ((int)$resultado['total'] > 0) {
    header("Location: " . BASE_URL . "pages/producto.php?error=asociado_a_ventas");
    exit;
}

$eliminar = $conexion->prepare("DELETE FROM productos WHERE id = ?");
$eliminar->bind_param("i", $id);
if ($eliminar->execute() && $eliminar->affected_rows === 1) {
    header("Location: " . BASE_URL . "pages/producto.php?success=eliminado");
} else {
    header("Location: " . BASE_URL . "pages/producto.php?error=db");
}
exit;
?>