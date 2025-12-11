<?php  
require("../../Config/Conexion.php");  
  
$id = $_GET['Id'];  
  
// Primero obtener el producto_id y cantidad para devolver stock  
$sql = $conexion->query("SELECT producto_id, cantidad FROM ventas WHERE id = $id");  
$venta = $sql->fetch_assoc();  
$producto_id = $venta['producto_id'];  
$cantidad = $venta['cantidad'];  
  
// Eliminar la venta  
if ($conexion->query("DELETE FROM ventas WHERE id = $id")) {  
    // Devolver stock al producto  
    $conexion->query("UPDATE productos SET cantidad = cantidad + $cantidad WHERE id = $producto_id");  
    header("location:../../venta.php?success=eliminado");  
} else {  
    header("location:../../venta.php?error=db");  
}  
?>