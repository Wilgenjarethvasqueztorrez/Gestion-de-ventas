<!doctype html>    
<html lang="es">    
<head>    
   <meta charset="UTF-8">    
   <meta name="viewport" content="width=device-width, initial-scale=1">    
   <title>Editar Venta</title>    
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">    
</head>    
<body>    
 <h1 class="bg-primary p-2 text-white text-center">Editar Venta</h1>    
 <br>    
 <form class="container" action="../../CRUD/Venta/editarVenta.php" method="post">    
  <?php    
   include ('../../Config/Conexion.php');    
   $sql = "SELECT v.*, p.nombre as producto_nombre, p.precio, p.cantidad as stock_actual    
           FROM ventas v    
           INNER JOIN productos p ON v.producto_id = p.id    
           WHERE v.id = " . $_GET['Id'];    
   $resultado = $conexion->query($sql);    
   $row = $resultado->fetch_assoc();    
  ?>    
  <input type="hidden" class="form-control" name="Id" value="<?php echo $row['id']; ?>">    
      
  <!-- Producto (dropdown seleccionable) -->    
  <label for="">Producto</label>    
  <select class="form-select mb-3" name="ProductoId" id="ProductoId" required onchange="actualizarStockMaximo()">    
      <?php    
      $sqlProductos = $conexion->query("SELECT id, nombre, precio, cantidad     
                                        FROM productos     
                                        ORDER BY nombre ASC");    
      while ($producto = $sqlProductos->fetch_assoc()) {    
          $selected = ($producto['id'] == $row['producto_id']) ? 'selected' : '';    
          echo "<option value='".$producto['id']."' $selected data-stock='".$producto['cantidad']."'>".$producto['nombre']." - $".number_format($producto['precio'], 0)." (Stock: ".$producto['cantidad'].")</option>";    
      }    
      ?>    
  </select>    
  
  <!-- Cantidad -->    
  <div class="mb-3">    
    <label class="form-label">Cantidad</label>    
    <input type="number" class="form-control" name="Cantidad" id="Cantidad" min="1" value="<?php echo $row['cantidad'] ?? 1; ?>" required>    
    <small class="text-muted">Cantidad disponible: <span id="stockDisponible"><?php echo $row['stock_actual']; ?></span></small>    
  </div>    
  
  <!-- Tipo de venta (dropdown seleccionable) -->    
  <label for="">Tipo de Venta</label>    
  <select class="form-select mb-3" name="Tipo" id="Tipo" required onchange="toggleFechasCredito()">    
      <option value="Contado" <?php echo ($row['tipo'] == 'Contado') ? 'selected' : ''; ?>>Contado (Pago inmediato)</option>    
      <option value="Credito" <?php echo ($row['tipo'] == 'Credito') ? 'selected' : ''; ?>>Crédito (Pago diferido)</option>    
  </select>    
  
  <!-- Fechas para crédito (solo para ventas a crédito) -->    
  <div id="fechasCredito" style="display: <?php echo ($row['tipo'] == 'Credito') ? 'block' : 'none'; ?>;">    
      <div class="mb-3">    
          <label class="form-label">Fecha Inicio Crédito</label>    
          <input type="date" class="form-control" name="FechaInicio" value="<?php echo $row['fecha_inicio']; ?>">    
      </div>    
  
      <div class="mb-3">    
          <label class="form-label">Fecha Fin Crédito</label>    
          <input type="date" class="form-control" name="FechaFin" value="<?php echo $row['fecha_fin']; ?>">    
      </div>    
  </div>    
  
  <!-- Botones -->    
  <div class="text-center">    
    <button type="submit" class="btn btn-primary">Actualizar</button>    
    <a href="../../venta.php" class="btn btn-dark">Cancelar</a>    
  </div>    
 </form>    
  
 <script>    
     function toggleFechasCredito() {    
         const tipo = document.getElementById('Tipo').value;    
         const fechasCredito = document.getElementById('fechasCredito');    
         const fechaInicio = fechasCredito.querySelector('input[name="FechaInicio"]');    
         const fechaFin = fechasCredito.querySelector('input[name="FechaFin"]');    
             
         if (tipo === 'Credito') {    
             fechasCredito.style.display = 'block';    
             fechaInicio.required = true;    
             fechaFin.required = true;    
         } else {    
             fechasCredito.style.display = 'none';    
             fechaInicio.required = false;    
             fechaFin.required = false;    
             fechaInicio.value = '';    
             fechaFin.value = '';    
         }    
     }  
  
     function actualizarStockMaximo() {  
         const select = document.getElementById('ProductoId');  
         const cantidadInput = document.getElementById('Cantidad');  
         const stockSpan = document.getElementById('stockDisponible');  
           
         if (select.selectedIndex > 0) {  
             const stock = select.options[select.selectedIndex].getAttribute('data-stock');  
             stockSpan.textContent = stock;  
             cantidadInput.max = stock;  
             if (cantidadInput.value > stock) {  
                 cantidadInput.value = 1;  
             }  
         }  
     }  
  
     // Inicializar al cargar  
     document.addEventListener('DOMContentLoaded', function() {  
         actualizarStockMaximo();  
     });  
 </script>    
  
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>    
</body>    
</html>