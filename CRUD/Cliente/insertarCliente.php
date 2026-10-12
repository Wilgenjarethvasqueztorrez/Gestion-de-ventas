<?php  
require_once __DIR__ . "/../../Config/Ruta.php";
require_once BASE_PATH . "Config/Conexion.php";
  
$nombre = $_POST['NombreCliente'];  
$apellido = $_POST['ApellidoCliente'];  

  
$sql = "INSERT INTO clientes (nombre, apellido)   
        VALUES ('$nombre', '$apellido')";  
  
if (mysqli_query($conexion, $sql)) {  
    header("Location: " . BASE_URL . "pages/cliente.php?success=agregado");
} else {  
    header("Location: " . BASE_URL . "pages/cliente.php?error=db");
}  
?>