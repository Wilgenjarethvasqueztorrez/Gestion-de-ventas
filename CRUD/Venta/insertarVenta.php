<?php  
require("../../Config/Conexion.php");  
  
$producto_id = $_POST['ProductoId'];  
$cantidad = $_POST['Cantidad'];  
$tipo = $_POST['Tipo'];  
$fecha_actual = date('Y-m-d');  
  
// Verificar stock disponible  
$sqlStock = $conexion->prepare("SELECT cantidad FROM productos WHERE id = ?");  
$sqlStock->bind_param("i", $producto_id);  
$sqlStock->execute();  
$resultado = $sqlStock->get_result();  
$producto = $resultado->fetch_assoc();  
  
if ($producto['cantidad'] < $cantidad) {  
    header("location:../../venta.php?error=stock_insuficiente");  
    exit;  
}  
  
// Preparar variables según tipo de venta  
if ($tipo == 'Contado') {  
    $fecha_inicio = $fecha_actual;  
    $fecha_fin = null;  
    $total_dias = null;  
    $estado = null;  
} else {  
    // Validar campos requeridos para crédito  
    if (empty($_POST['FechaInicio']) || empty($_POST['FechaFin']) || empty($_POST['EstadoVenta'])) {  
        header("location:../../venta.php?error=campos_requeridos");  
        exit;  
    }  
      
    $fecha_inicio = $_POST['FechaInicio'];  
    $fecha_fin = $_POST['FechaFin'];  
    $total_dias = $_POST['TotalDias']; // Usar valor calculado en el formulario  
    $estado = $_POST['EstadoVenta'];  
}  
  
// Insertar un solo registro con la cantidad  
$sql = "INSERT INTO ventas (producto_id, cantidad_venta, tipo, fecha_inicio, fecha_fin, total_dias, estado)   
        VALUES (?, ?, ?, ?, ?, ?, ?)";  
$stmt = $conexion->prepare($sql);  
$stmt->bind_param("iisssis", $producto_id, $cantidad, $tipo, $fecha_inicio, $fecha_fin, $total_dias, $estado);  
  
if ($stmt->execute()) {  
    // Actualizar stock del producto  
    $updateStock = $conexion->prepare("UPDATE productos SET cantidad = cantidad - ? WHERE id = ?");  
    $updateStock->bind_param("ii", $cantidad, $producto_id);  
    $updateStock->execute();  
      
    header("location:../../venta.php?success=agregado");  
} else {  
    header("location:../../venta.php?error=db");  
}  
  
$stmt->close();  
$conexion->close();  
?>
