<?php
require_once __DIR__ . "/../../Config/Ruta.php";
require_once BASE_PATH . "Config/Conexion.php";

$id = $_GET['Id'];  
  
// Verificar si el cliente tiene ventas asociadas  
$sql = $conexion->query("SELECT COUNT(*) as total FROM ventas WHERE cliente_id = $id");  
$resultado = $sql->fetch_assoc();  
  
    // Eliminar si no tiene ventas  
    if ($conexion->query("DELETE FROM clientes WHERE id = $id")) {
        header("Location: " . BASE_URL . "pages/cliente.php?success=eliminado");
        exit;
    } else {
        header("Location: " . BASE_URL . "pages/cliente.php?error=db");
        exit;
}
?>