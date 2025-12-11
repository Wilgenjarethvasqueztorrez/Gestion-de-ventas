<?php  
require("../../Config/Conexion.php");  
  
$nombre = $_POST['NombreProducto'];  
$descripcion = $_POST['Descripcion'];  
$precio = $_POST['Precio'];  
$cantidad = $_POST['Cantidad'];  
  
$sql = "INSERT INTO productos (nombre, descripcion, precio, cantidad)   
        VALUES ('$nombre', '$descripcion', '$precio', '$cantidad')";  
  
if (mysqli_query($conexion, $sql)) {  
    header("location:../../producto.php?success=agregado");  
} else {  
    header("location:../../producto.php?error=db");  
}  
?>