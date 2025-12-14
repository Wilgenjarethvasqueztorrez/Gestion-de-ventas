<?php  
require("../../Config/Conexion.php");  
  
$id = $_POST['Id'];  
$producto_id = $_POST['ProductoId'];  
$cantidad_nueva = $_POST['Cantidad'];  
$tipo = $_POST['Tipo'];  
  
// Obtener datos actuales de la venta  
$sqlActual = $conexion->prepare("SELECT producto_id, cantidad_venta FROM ventas WHERE id = ?");  
$sqlActual->bind_param("i", $id);  
$sqlActual->execute();  
$resultado = $sqlActual->get_result();  
$ventaActual = $resultado->fetch_assoc();  
  
// Preparar variables según tipo de venta  
if ($tipo == 'Contado') {  
    $fecha_inicio = date('Y-m-d');  
    $fecha_fin = null;  
    $total_dias = null;  
} else {  
    // Validar campos requeridos para crédito  
    if (empty($_POST['FechaInicio']) || empty($_POST['FechaFin']) || empty($_POST['EstadoVenta'])) {  
        header("location:../../venta.php?error=campos_requeridos");  
        exit;  
    }  
      
    $fecha_inicio = $_POST['FechaInicio'];  
    $fecha_fin = $_POST['FechaFin'];  
    $total_dias = $_POST['TotalDias'];  
    $estado = $_POST['EstadoVenta'];  
}  
  
// Si cambió el producto, devolver stock al anterior y restar al nuevo  
if ($ventaActual['producto_id'] != $producto_id) {  
    // Devolver stock al producto anterior  
    $updateAnterior = $conexion->prepare("UPDATE productos SET cantidad = cantidad + ? WHERE id = ?");  
    $updateAnterior->bind_param("ii", $ventaActual['cantidad_venta'], $ventaActual['producto_id']);  
    $updateAnterior->execute();  
        
    // Verificar stock del nuevo producto  
    $sqlStock = $conexion->prepare("SELECT cantidad FROM productos WHERE id = ?");  
    $sqlStock->bind_param("i", $producto_id);  
    $sqlStock->execute();  
    $resultadoStock = $sqlStock->get_result();  
    $producto = $resultadoStock->fetch_assoc();  
        
    if ($producto['cantidad'] < $cantidad_nueva) {  
        // Revertir cambio si no hay stock  
        $revertir = $conexion->prepare("UPDATE productos SET cantidad = cantidad - ? WHERE id = ?");  
        $revertir->bind_param("ii", $ventaActual['cantidad_venta'], $ventaActual['producto_id']);  
        $revertir->execute();  
        header("location:../../venta.php?error=stock_insuficiente");  
        exit;  
    }  
        
    // Restar stock del nuevo producto  
    $updateNuevo = $conexion->prepare("UPDATE productos SET cantidad = cantidad - ? WHERE id = ?");  
    $updateNuevo->bind_param("ii", $cantidad_nueva, $producto_id);  
    $updateNuevo->execute();  
} else {  
    // Si es el mismo producto, ajustar diferencia de stock  
    $diferencia = $cantidad_nueva - $ventaActual['cantidad_venta'];  
    if ($diferencia > 0) {  
        // Se vendió más, verificar stock disponible  
        $sqlStock = $conexion->prepare("SELECT cantidad FROM productos WHERE id = ?");  
        $sqlStock->bind_param("i", $producto_id);  
        $sqlStock->execute();  
        $resultadoStock = $sqlStock->get_result();  
        $producto = $resultadoStock->fetch_assoc();  
            
        if ($producto['cantidad'] < $diferencia) {  
            header("location:../../venta.php?error=stock_insuficiente");  
            exit;  
        }  
    }  
    $updateStock = $conexion->prepare("UPDATE productos SET cantidad = cantidad - ? WHERE id = ?");  
    $updateStock->bind_param("ii", $diferencia, $producto_id);  
    $updateStock->execute();  
}  
  
// Actualizar la venta - SIGUIENDO EL PATRÓN DE editarAsistencia.php  
if ($tipo == 'Contado') {  
    // Query SIN el campo estado para contado (como en editarAsistencia.php líneas 13-19)  
    $sql = "UPDATE ventas SET     
            producto_id=?,     
            cantidad_venta=?,     
            tipo=?,     
            fecha_inicio=?,     
            fecha_fin=?,     
            total_dias=?    
            WHERE id=?";  
    $stmt = $conexion->prepare($sql);  
    $stmt->bind_param("iisssi", $producto_id, $cantidad_nueva, $tipo, $fecha_inicio, $fecha_fin, $total_dias, $id);  
} else {  
    // Query CON el campo estado para crédito  
    $sql = "UPDATE ventas SET     
            producto_id=?,     
            cantidad_venta=?,     
            tipo=?,     
            fecha_inicio=?,     
            fecha_fin=?,     
            total_dias=?,    
            estado=?    
            WHERE id=?";  
    $stmt = $conexion->prepare($sql);  
    $stmt->bind_param("iissssii", $producto_id, $cantidad_nueva, $tipo, $fecha_inicio, $fecha_fin, $total_dias, $estado, $id);  
}  
  
if ($stmt->execute()) {  
    header("location:../../venta.php?success=editado");  
} else {  
    header("location:../../venta.php?error=db");  
}  
  
$stmt->close();  
$conexion->close();  
?>