<?php  
require("../../Config/Conexion.php");  
  
$producto_id = $_POST['ProductoId'];  
$cantidad = $_POST['Cantidad'];  
$tipo = $_POST['Tipo'];  
$fecha_actual = date('Y-m-d');  
  
// Verificar stock disponible  
$sqlStock = $conexion->query("SELECT cantidad FROM productos WHERE id = $producto_id");  
$producto = $sqlStock->fetch_assoc();  
  
if ($producto['cantidad'] < $cantidad) {  
    header("location:../../venta.php?error=stock_insuficiente");  
    exit;  
}  
  
if ($tipo == 'Contado') {  
    $fecha_inicio = $fecha_actual;  
    $fecha_fin = 'NULL';  
    $total_dias = 'NULL';  
} else {  
    $fecha_inicio = $_POST['FechaInicio'];  
    $fecha_fin = "'" . $_POST['FechaFin'] . "'";  
    $total_dias = (strtotime($_POST['FechaFin']) - strtotime($_POST['FechaInicio'])) / 86400 + 1;  
    $estado = $_POST['EstadoVenta'];
}  
  
// Insertar múltiples registros para la cantidad  
$sql = "INSERT INTO ventas (producto_id, tipo, fecha_inicio, fecha_fin, total_dias, estado)   
        VALUES ";  
$values = [];  
for ($i = 0; $i < $cantidad; $i++) {  
    $values[] = "('$producto_id', '$tipo', '$fecha_inicio', $fecha_fin, $total_dias, $estado)";  
}  
$sql .= implode(',', $values);  
  
if (mysqli_query($conexion, $sql)) {  
    // Actualizar stock del producto  
    $conexion->query("UPDATE productos SET cantidad = cantidad - $cantidad WHERE id = $producto_id");  
    header("location:../../venta.php?success=agregado");  
} else {  
    header("location:../../venta.php?error=db");  
}  
?>
