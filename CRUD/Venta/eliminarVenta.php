<?php
require_once __DIR__ . "/../../Config/Ruta.php";
require_once BASE_PATH . "Config/Conexion.php";

$id = filter_input(INPUT_GET, 'Id', FILTER_VALIDATE_INT);
if (!$id) {
    header("Location: " . BASE_URL . "pages/venta.php?error=no_existe");
    exit;
}

try {
    $conexion->begin_transaction();
    $obtenerDetalles = $conexion->prepare("SELECT producto_id, SUM(cantidad) AS cantidad FROM ventas_detalles WHERE venta_id = ? GROUP BY producto_id");
    $obtenerDetalles->bind_param("i", $id);
    $obtenerDetalles->execute();
    $detalles = $obtenerDetalles->get_result();

    $devolverStock = $conexion->prepare("UPDATE productos SET cantidad = cantidad + ? WHERE id = ?");
    while ($detalle = $detalles->fetch_assoc()) {
        $cantidad = (int)$detalle['cantidad'];
        $producto_id = (int)$detalle['producto_id'];
        $devolverStock->bind_param("ii", $cantidad, $producto_id);
        $devolverStock->execute();
    }

    $eliminarDetalles = $conexion->prepare("DELETE FROM ventas_detalles WHERE venta_id = ?");
    $eliminarDetalles->bind_param("i", $id);
    $eliminarDetalles->execute();
    $eliminarVenta = $conexion->prepare("DELETE FROM ventas WHERE id = ?");
    $eliminarVenta->bind_param("i", $id);
    $eliminarVenta->execute();
    if ($eliminarVenta->affected_rows !== 1) {
        throw new RuntimeException('no_existe');
    }

    $conexion->commit();
    header("Location: " . BASE_URL . "pages/venta.php?success=eliminado");
} catch (Throwable $error) {
    $conexion->rollback();
    header("Location: " . BASE_URL . "pages/venta.php?error=" . ($error->getMessage() === 'no_existe' ? 'no_existe' : 'db'));
}
?>