<?php  
require("../../Config/Conexion.php");  
  
$id = $_POST['Id'];  
$nombre = $_POST['NombreProducto'];  
$descripcion = $_POST['Descripcion'];  
$precio = $_POST['Precio'];  
$cantidad = $_POST['Cantidad'];  
  
$sql = "UPDATE productos SET   
        nombre='$nombre',   
        descripcion='$descripcion',   
        precio='$precio',   
        cantidad='$cantidad'   
        WHERE id=$id";  
  
if (mysqli_query($conexion, $sql)) {  
    header("location:../../producto.php?success=editado");  
} else {  
    header("location:../../producto.php?error=db");  
}  
?>