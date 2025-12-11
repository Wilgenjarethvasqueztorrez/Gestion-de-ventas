<?php  
require("../../Config/Conexion.php");  
  
$id = $_GET['Id'];  
  
// Verificar si el producto tiene ventas asociadas  
$sql = $conexion->query("SELECT COUNT(*) as total FROM ventas WHERE producto_id = $id");  
$resultado = $sql->fetch_assoc();  
  
if ($resultado['total'] > 0) {  
    // No eliminar si tiene ventas asociadas  
    header("location:../../productos.php?error=ventas_asociadas");  
} else {  
    // Eliminar si no tiene ventas  
    if ($conexion->query("DELETE FROM productos WHERE id = $id")) {  
        header("location:../../producto.php?success=eliminado");  
    } else {  
        header("location:../../producto.php?error=db");  
    }  
}  
?>