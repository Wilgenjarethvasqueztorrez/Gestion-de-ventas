<?php
require_once __DIR__ . "/../../Config/Ruta.php";
require_once BASE_PATH . "Config/Conexion.php";

function volverVentaEdicion($error)
{
    header("Location: " . BASE_URL . "pages/venta.php?error=" . rawurlencode($error));
    exit;
}

$id = filter_input(INPUT_POST, 'Id', FILTER_VALIDATE_INT);
$cliente_id = filter_input(INPUT_POST, 'ClienteId', FILTER_VALIDATE_INT);
$tipo = $_POST['Tipo'] ?? '';
$productos = $_POST['ProductoId'] ?? [];
$cantidades = $_POST['Cantidad'] ?? [];

if (!$id || !$cliente_id || !in_array($tipo, ['Contado', 'Credito'], true) || !is_array($productos) || !is_array($cantidades) || count($productos) !== count($cantidades)) {
    volverVentaEdicion('campos_requeridos');
}

$detalles = [];
foreach ($productos as $indice => $producto_id) {
    $producto_id = filter_var($producto_id, FILTER_VALIDATE_INT);
    $cantidad = filter_var($cantidades[$indice] ?? null, FILTER_VALIDATE_INT);
    if (!$producto_id || !$cantidad || $cantidad < 1) {
        volverVentaEdicion('campos_requeridos');
    }
    $detalles[$producto_id] = ($detalles[$producto_id] ?? 0) + $cantidad;
}
if (!$detalles) {
    volverVentaEdicion('campos_requeridos');
}

$fecha_inicio = date('Y-m-d');
$fecha_fin = null;
$total_dias = null;
$estado = null;
if ($tipo === 'Credito') {
    $fecha_inicio = $_POST['FechaInicio'] ?? '';
    $fecha_fin = $_POST['FechaFin'] ?? '';
    $estado = $_POST['EstadoVenta'] ?? '';
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha_inicio) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha_fin) || !in_array($estado, ['Pendiente', 'Cancelada'], true)) {
        volverVentaEdicion('campos_requeridos');
    }
    $inicio = DateTime::createFromFormat('!Y-m-d', $fecha_inicio);
    $fin = DateTime::createFromFormat('!Y-m-d', $fecha_fin);
    if (!$inicio || !$fin || $inicio->format('Y-m-d') !== $fecha_inicio || $fin->format('Y-m-d') !== $fecha_fin || $fin < $inicio) {
        volverVentaEdicion('campos_requeridos');
    }
    $total_dias = $inicio->diff($fin)->days + 1;
}

try {
    $conexion->begin_transaction();

    $bloquearVenta = $conexion->prepare("SELECT id FROM ventas WHERE id = ? FOR UPDATE");
    $bloquearVenta->bind_param("i", $id);
    $bloquearVenta->execute();
    if (!$bloquearVenta->get_result()->fetch_assoc()) {
        throw new RuntimeException('no_existe');
    }

    $obtenerDetallesAnteriores = $conexion->prepare("SELECT producto_id, SUM(cantidad) AS cantidad FROM ventas_detalles WHERE venta_id = ? GROUP BY producto_id");
    $obtenerDetallesAnteriores->bind_param("i", $id);
    $obtenerDetallesAnteriores->execute();
    $detallesAnteriores = $obtenerDetallesAnteriores->get_result();
    $devolverStock = $conexion->prepare("UPDATE productos SET cantidad = cantidad + ? WHERE id = ?");
    while ($detalleAnterior = $detallesAnteriores->fetch_assoc()) {
        $cantidadAnterior = (int)$detalleAnterior['cantidad'];
        $productoAnterior = (int)$detalleAnterior['producto_id'];
        $devolverStock->bind_param("ii", $cantidadAnterior, $productoAnterior);
        $devolverStock->execute();
    }

    $eliminarDetalles = $conexion->prepare("DELETE FROM ventas_detalles WHERE venta_id = ?");
    $eliminarDetalles->bind_param("i", $id);
    $eliminarDetalles->execute();

    $actualizarVenta = $conexion->prepare("UPDATE ventas SET cliente_id = ?, tipo = ?, fecha_inicio = ?, fecha_fin = ?, total_dias = ?, estado = ? WHERE id = ?");
    $actualizarVenta->bind_param("isssisi", $cliente_id, $tipo, $fecha_inicio, $fecha_fin, $total_dias, $estado, $id);
    $actualizarVenta->execute();

    $obtenerProducto = $conexion->prepare("SELECT precio, cantidad FROM productos WHERE id = ? FOR UPDATE");
    $actualizarStock = $conexion->prepare("UPDATE productos SET cantidad = cantidad - ? WHERE id = ?");
    $insertarDetalle = $conexion->prepare("INSERT INTO ventas_detalles (venta_id, producto_id, cantidad, precio) VALUES (?, ?, ?, ?)");
    foreach ($detalles as $producto_id => $cantidad) {
        $obtenerProducto->bind_param("i", $producto_id);
        $obtenerProducto->execute();
        $producto = $obtenerProducto->get_result()->fetch_assoc();
        if (!$producto || (int)$producto['cantidad'] < $cantidad) {
            throw new RuntimeException('stock_insuficiente');
        }
        $precio = (int)$producto['precio'];
        $actualizarStock->bind_param("ii", $cantidad, $producto_id);
        $actualizarStock->execute();
        $insertarDetalle->bind_param("iiii", $id, $producto_id, $cantidad, $precio);
        $insertarDetalle->execute();
    }

    $conexion->commit();
    header("Location: " . BASE_URL . "pages/venta.php?success=editado");
} catch (Throwable $error) {
    $conexion->rollback();
    $codigoError = in_array($error->getMessage(), ['stock_insuficiente', 'no_existe'], true) ? $error->getMessage() : 'db';
    volverVentaEdicion($codigoError);
}
?>