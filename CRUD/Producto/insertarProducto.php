<?php  
require_once __DIR__ . "/../../Config/Ruta.php";
require_once BASE_PATH . "Config/Conexion.php";
  
$nombre = $_POST['NombreProducto'];  
$descripcion = $_POST['Descripcion'];  
$codigo = $_POST['Codigo'];  
$precio = $_POST['Precio'];  
$cantidad = $_POST['Cantidad'];  
  
$sql = "INSERT INTO productos (nombre, descripcion, codigo, precio, cantidad)   
        VALUES ('$nombre', '$descripcion', '$codigo', '$precio', '$cantidad')";  
  
if (mysqli_query($conexion, $sql)) {  
    header("Location: " . BASE_URL . "pages/producto.php?success=agregado");
} else {  
    header("Location: " . BASE_URL . "pages/producto.php?error=db");
}  
?>