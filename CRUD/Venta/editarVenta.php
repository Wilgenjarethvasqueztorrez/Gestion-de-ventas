<?php  
require("../../Config/Conexion.php");  
  
$id = $_POST['Id'];  
$producto_id = $_POST['ProductoId'];  
$cantidad_nueva = $_POST['Cantidad'];  
$tipo = $_POST['Tipo'];  
$fecha_inicio = $_POST['FechaInicio'];  
$fecha_fin = $_POST['FechaFin'];  
  
// Obtener datos actuales de la venta  
$sqlActual = $conexion->query("SELECT producto_id, cantidad FROM ventas WHERE id = $id");  
$ventaActual = $sqlActual->fetch_assoc();  
  
// Calcular total_dias solo para ventas a crédito  
if ($tipo == 'Credito') {  
    $total_dias = (strtotime($fecha_fin) - strtotime($fecha_inicio)) / 86400 + 1;  
} else {  
    $total_dias = 'NULL';  
}  
  
// Si cambió el producto, devolver stock al anterior y restar al nuevo  
if ($ventaActual['producto_id'] != $producto_id) {  
    // Devolver stock al producto anterior  
    $conexion->query("UPDATE productos SET cantidad = cantidad + {$ventaActual['cantidad']} WHERE id = {$ventaActual['producto_id']}");  
      
    // Verificar stock del nuevo producto  
    $sqlStock = $conexion->query("SELECT cantidad FROM productos WHERE id = $producto_id");  
    $producto = $sqlStock->fetch_assoc();  
      
    if ($producto['cantidad'] < $cantidad_nueva) {  
        // Revertir cambio si no hay stock  
        $conexion->query("UPDATE productos SET cantidad = cantidad - {$ventaActual['cantidad']} WHERE id = {$ventaActual['producto_id']}");  
        header("location:../../ventas.php?error=stock_insuficiente");  
        exit;  
    }  
      
    // Restar stock del nuevo producto  
    $conexion->query("UPDATE productos SET cantidad = cantidad - $cantidad_nueva WHERE id = $producto_id");  
} else {  
    // Si es el mismo producto, ajustar diferencia de stock  
    $diferencia = $cantidad_nueva - $ventaActual['cantidad'];  
    if ($diferencia > 0) {  
        // Se vendió más, verificar stock disponible  
        $sqlStock = $conexion->query("SELECT cantidad FROM productos WHERE id = $producto_id");  
        $producto = $sqlStock->fetch_assoc();  
          
        if ($producto['cantidad'] < $diferencia) {  
            header("location:../../venta.php?error=stock_insuficiente");  
            exit;  
        }  
    }  
    $conexion->query("UPDATE productos SET cantidad = cantidad - $diferencia WHERE id = $producto_id");  
}  
  
// Actualizar la venta  
$sql = "UPDATE ventas SET   
        producto_id='$producto_id',   
        tipo='$tipo',   
        fecha_inicio='$fecha_inicio',   
        fecha_fin='$fecha_fin',   
        total_dias='$total_dias',  
        cantidad='$cantidad_nueva'  
        WHERE id=$id";  
  
if (mysqli_query($conexion, $sql)) {  
    header("location:../../venta.php?success=editado");  
} else {  
    header("location:../../venta.php?error=db");  
}  
?>