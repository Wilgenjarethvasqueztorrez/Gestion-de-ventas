<?php  
require_once __DIR__ . "/../../Config/Ruta.php";
require_once BASE_PATH . "Config/Conexion.php";
  
$id = $_POST['Id'];  
$nombre = $_POST['NombreProducto'];  
$descripcion = $_POST['Descripcion'];  
$codigo = $_POST['Codigo'];
$precio = $_POST['Precio'];  
$cantidad = $_POST['Cantidad'];  
  
$sql = "UPDATE productos SET   
        nombre='$nombre',   
        descripcion='$descripcion',   
        codigo='$codigo',   
        precio='$precio',   
        cantidad='$cantidad'   
        WHERE id=$id";  
  
if (mysqli_query($conexion, $sql)) {  
    header("Location: " . BASE_URL . "pages/producto.php?success=editado");
} else {  
    header("Location: " . BASE_URL . "pages/producto.php?error=db");
}  
?>