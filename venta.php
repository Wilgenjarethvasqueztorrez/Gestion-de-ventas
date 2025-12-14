  <!doctype html>    
<html lang="es">    
<head>    
  <meta charset="UTF-8">    
  <meta name="viewport" content="width=device-width, initial-scale=1">    
  <title>Ventas</title>    
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">    
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">    
  <!-- SweetAlert2 CSS -->    
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">     
  <!-- CSS de DataTables -->    
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">    
  <!-- jQuery -->    
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>   
  <!-- DataTables Responsive -->    
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">    
  <link rel="stylesheet" href="src/css/styles.css">    
</head>    
<body>      
  <?php include('src/includes/Componentes/sidebar.php'); ?>    
  
  <main class="container mt-4">    
  
    <h1 class="bg-info p-3 text-white text-center rounded">💰 GESTIÓN DE VENTAS</h1>    
  
    <div class="text-end mb-3">    
      <a href="Formularios/Venta/AgregarVenta.php" class="btn btn-success">    
        <i class="bi bi-plus-circle"></i> Agregar Venta    
      </a>    
    </div>    
  
    <!-- Tablas para separar Ventas Crédito y Contado -->    
    <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">    
      <li class="nav-item" role="presentation">    
        <button class="nav-link active" id="credito-tab" data-bs-toggle="tab" data-bs-target="#credito" type="button">    
          💳 Ventas a Crédito    
        </button>    
      </li>    
      <li class="nav-item" role="presentation">    
        <button class="nav-link" id="contado-tab" data-bs-toggle="tab" data-bs-target="#contado" type="button">    
          💵 Ventas de Contado    
        </button>    
      </li>    
    </ul>    
  
    <div class="tab-content" id="myTabContent">    
      <!-- Tabla de Ventas a Crédito -->    
      <div class="tab-pane fade show active" id="credito" role="tabpanel">    
        <div class="table-container">    
          <table id="tabla" class="table table-hover">    
            <thead>    
              <tr>    
                <th>Producto</th>    
                <th>Precio</th>    
                <th>Cantidad</th>    
                <th>Fecha Inicio</th>    
                <th>Fecha Fin</th>    
                <th>Días</th>    
                <th>Estado</th>    
                <th>Acciones</th>    
              </tr>    
            </thead>    
            <tbody>    
              <?php    
                require("Config/Conexion.php");    
                $sqlCredito = $conexion->query("SELECT v.*, p.nombre as producto_nombre, p.precio    
                                                  FROM ventas v    
                                                  INNER JOIN productos p ON v.producto_id = p.id    
                                                  WHERE v.tipo = 'Credito'     
                                                  ORDER BY v.fecha_inicio DESC");    
                while ($venta = $sqlCredito->fetch_assoc()) {    
                  $dias = (strtotime($venta['fecha_fin']) - strtotime($venta['fecha_inicio'])) / 86400 + 1;    
              ?>    
              <tr>    
                <td><strong><?php echo $venta['producto_nombre']; ?></strong></td>    
                <td>$<?php echo number_format($venta['precio'], 0); ?></td>    
                <td><span class="badge bg-warning text-dark"><?php echo $venta['cantidad_venta']; ?> unidades</span></td>    
                <td><?php echo date('d/m/Y', strtotime($venta['fecha_inicio'])); ?></td>    
                <td><?php echo date('d/m/Y', strtotime($venta['fecha_fin'])); ?></td>    
                <td><span class="badge bg-info"><?php echo $dias; ?> día(s)</span></td>  
                  <td>  
                <?php   
                $badge_class = '';  
                switch($venta['estado']) {  
                  case 'Pendiente':  
                    $badge_class = 'bg-danger';  
                    break;  
                  case 'Cancelada':  
                    $badge_class = 'bg-success';  
                    break;  
                
                }  
                echo "<span class='badge $badge_class'>".$venta['estado']."</span>";  
                ?>  
              </td>     
                <td class="acciones">    
                  <a href="Formularios/Venta/EditarVenta.php?Id=<?php echo $venta['id']; ?>" class="btn btn-warning btn-sm">    
                    <i class="bi bi-pencil"></i> Editar    
                  </a>    
                  <a href="CRUD/Venta/eliminarVenta.php?Id=<?php echo $venta['id']; ?>" class="btn btn-danger btn-sm" onclick="event.preventDefault(); confirmarEliminacion(this.href)">    
                    <i class="bi bi-trash3"></i> Eliminar    
                  </a>    
                </td>    
              </tr>    
              <?php } ?>    
            </tbody>    
          </table>    
        </div>    
      </div>    
  
      <div class="tab-pane fade" id="contado" role="tabpanel">    
        <div class="table-container">    
          <table id="tabla-contado" class="table table-hover">    
            <thead>    
              <tr>    
                <th>Producto</th>    
                <th>Precio</th>    
                <th>Cantidad</th>    
                <th>Fecha Venta</th>    
                <th>Acciones</th>    
              </tr>    
            </thead>    
            <tbody>    
              <?php    
                $sqlContado = $conexion->query("SELECT v.*, p.nombre as producto_nombre, p.precio    
                                               FROM ventas v    
                                               INNER JOIN productos p ON v.producto_id = p.id    
                                               WHERE v.tipo = 'Contado'    
                                               ORDER BY v.fecha_inicio DESC");    
                while ($venta = $sqlContado->fetch_assoc()) {    
              ?>    
              <tr>    
                <td><strong><?php echo $venta['producto_nombre']; ?></strong></td>    
                <td>$<?php echo number_format($venta['precio'], 0); ?></td>    
                <td><span class="badge bg-warning text-dark"><?php echo $venta['cantidad_venta']; ?> unidades</span></td>  
                <td><?php echo date('d/m/Y', strtotime($venta['fecha_inicio'])); ?></td>    
                <td class="acciones">    
                  <a href="Formularios/Venta/EditarVenta.php?Id=<?php echo $venta['id']; ?>" class="btn btn-warning btn-sm">    
                    <i class="bi bi-pencil"></i> Editar    
                  </a>    
                  <a href="CRUD/Venta/eliminarVenta.php?Id=<?php echo $venta['id']; ?>" class="btn btn-danger btn-sm" onclick="event.preventDefault(); confirmarEliminacion(this.href)">    
                    <i class="bi bi-trash3"></i> Eliminar    
                  </a>    
                </td>    
              </tr>    
              <?php } ?>    
            </tbody>    
          </table>    
        </div>    
      </div>    
    </div>    
  </main>    
  
  <!-- Inicializar DataTables -->  
  <?php include('src/includes/Dependencias/datatables.php'); ?>  
  
  <!-- Inicializar SweetAlert2 -->    
  <?php include('src/includes/Dependencias/sweetalert.php'); ?>  

  
  
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>    
</body>    
</html>