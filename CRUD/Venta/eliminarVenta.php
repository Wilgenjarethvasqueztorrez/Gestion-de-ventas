<?php  
require("../../Config/Conexion.php");  
  
$id = $_GET['Id'];  
  
// Obtener datos de la venta antes de eliminar  
$sql = $conexion->prepare("SELECT producto_id, cantidad_venta FROM ventas WHERE id = ?");  
$sql->bind_param("i", $id);  
$sql->execute();  
$resultado = $sql->get_result();  
$venta = $resultado->fetch_assoc();  
  
if (!$venta) {  
    header("location:../../venta.php?error=no_existe");  
    exit;  
}  
  
$producto_id = $venta['producto_id'];  
$cantidad = $venta['cantidad_venta'];  
  
// Eliminar la venta  
$delete = $conexion->prepare("DELETE FROM ventas WHERE id = ?");  
$delete->bind_param("i", $id);  
  
if ($delete->execute()) {  
    // Devolver stock al producto  
    $update = $conexion->prepare("UPDATE productos SET cantidad = cantidad + ? WHERE id = ?");  
    $update->bind_param("ii", $cantidad, $producto_id);  
    $update->execute();  
      
    header("location:../../venta.php?success=eliminado");  
} else {  
    header("location:../../venta.php?error=db");  
}  
  
$sql->close();  
$delete->close();  
$update->close();  
$conexion->close();  
?>