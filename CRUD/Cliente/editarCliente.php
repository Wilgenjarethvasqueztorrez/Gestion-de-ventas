<?php  
require_once __DIR__ . "/../../Config/Ruta.php";
require_once BASE_PATH . "Config/Conexion.php";
  
// Recibir datos del formulario  
$id = $_POST['Id'];  
$nombre = $_POST['NombreCliente'];  
$apellido = $_POST['ApellidoCliente'];   
  
// Actualizar solo la tabla usuarios  
$sql = "UPDATE clientes   
        SET nombre='$nombre',   
            apellido='$apellido'  
        WHERE id=$id";  
  
if (mysqli_query($conexion, $sql)) {  
    header("Location: " . BASE_URL . "pages/cliente.php?success=editado");
} else {  
    header("Location: " . BASE_URL . "pages/cliente.php?error=db");
}
