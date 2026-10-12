<?php
require("Config/Conexion.php");

$fecha_actual = date('Y-m-d');

// Proteger la página
require("Config/Ruta.php");

/* FUNCIONES ÚTILES */

function fetchValue($conexion, $query, $field = 'total')
{
  return $conexion->query($query)->fetch_assoc()[$field] ?? 0;
}

function diasRestantes($fecha_fin, $fecha_actual = null)
{
  $fecha_actual = $fecha_actual ?? date('Y-m-d');
  $fin = new DateTime($fecha_fin);
  $hoy = new DateTime($fecha_actual);
  return ($fin <= $hoy) ? 0 : $hoy->diff($fin)->days;
}

/* CONSULTAS SQL */

// Totales importantes
$totalProductos       = fetchValue($conexion, "SELECT COUNT(*) AS total FROM productos");
$totalVentas          = fetchValue($conexion, "SELECT COUNT(*) AS total FROM ventas");
$totalVentasCredito   = fetchValue($conexion, "SELECT COUNT(*) AS total FROM ventas WHERE tipo = 'Credito'");
$totalVentasContado   = fetchValue($conexion, "SELECT COUNT(*) AS total FROM ventas WHERE tipo = 'Contado'");
$ventasHoy            = fetchValue($conexion, "SELECT COUNT(*) AS total FROM ventas WHERE fecha_inicio = '$fecha_actual'");
$productosBajoStock   = fetchValue($conexion, "SELECT COUNT(*) AS total FROM productos WHERE cantidad < 10");
$ventasActivas        = fetchValue($conexion, "
    SELECT COUNT(*) AS total 
    FROM ventas 
    WHERE tipo='Credito' AND fecha_fin > '$fecha_actual'
");

// Valor del inventario
$valorInventario = fetchValue($conexion, "SELECT SUM(precio * cantidad) AS total FROM productos");

// Ventas de la semana
$inicio_semana = date('Y-m-d', strtotime("last monday"));
$fin_semana    = date('Y-m-d', strtotime("next sunday"));

$ventasSemana = fetchValue(
  $conexion,
  "SELECT COUNT(*) AS total
     FROM ventas 
     WHERE fecha_inicio BETWEEN '$inicio_semana' AND '$fin_semana'"
);

?>
<!doctype html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link rel="stylesheet" href="src/css/styles.css">
</head>

<body>

  <?php include('src/includes/Componentes/sidebar.php'); ?>

  <main class="container py-4">

    <h1 class="bg-primary p-3 text-white text-center rounded">📊 DASHBOARD DEL SISTEMA</h1>

    <!-- RESUMEN GENERAL -->
    <h3 class="mt-5"><i class="bi bi-bar-chart-fill"></i> Resumen General</h3>
    <div class="row mt-4 g-3">

      <!-- Tarjeta Productos -->
      <div class="col-md-4 col-lg-3">
        <div class="card bg-primary text-white text-center">
          <div class="card-body">
            <h5><i class="bi bi-box-fill"></i> Productos</h5>
            <p class="display-4 fw-bold mb-0"><?= $totalProductos ?></p>
            <small>Total en inventario</small>
          </div>
        </div>
      </div>

      <!-- Ventas Totales -->
      <div class="col-md-4 col-lg-3">
        <div class="card bg-success text-white text-center">
          <div class="card-body">
            <h5><i class="bi bi-cart-check-fill"></i> Ventas Totales</h5>
            <p class="display-4 fw-bold mb-0"><?= $totalVentas ?></p>
            <small>Registradas</small>
          </div>
        </div>
      </div>

      <!-- Ventas Hoy -->
      <div class="col-md-4 col-lg-3">
        <div class="card bg-info text-white text-center">
          <div class="card-body">
            <h5><i class="bi bi-calendar-check"></i> Ventas Hoy</h5>
            <p class="display-4 fw-bold mb-0"><?= $ventasHoy ?></p>
            <small>Del día</small>
          </div>
        </div>
      </div>

      <!-- Ventas Crédito -->
      <div class="col-md-4 col-lg-3">
        <div class="card bg-warning text-dark text-center">
          <div class="card-body">
            <h5><i class="bi bi-credit-card-fill"></i> Ventas Crédito</h5>
            <p class="display-4 fw-bold mb-0"><?= $totalVentasCredito ?></p>
            <small><?= $totalVentasContado ?> al contado</small>
          </div>
        </div>
      </div>

      <!-- Ventas Semana -->
      <div class="col-md-4 col-lg-3">
        <div class="card bg-success text-white text-center">
          <div class="card-body">
            <h5><i class="bi bi-graph-up"></i> Ventas Semana</h5>
            <p class="display-4 fw-bold mb-0"><?= $ventasSemana ?></p>
            <small>Esta semana</small>
          </div>
        </div>
      </div>

      <!-- Bajo Stock -->
      <div class="col-md-4 col-lg-3">
        <div class="card bg-danger text-white text-center">
          <div class="card-body">
            <h5><i class="bi bi-exclamation-triangle-fill"></i> Bajo Stock</h5>
            <p class="display-4 fw-bold mb-0"><?= $productosBajoStock ?></p>
            <small>Menos de 10 unidades</small>
          </div>
        </div>
      </div>

      <!-- Valor Inventario -->
      <div class="col-md-4 col-lg-3">
        <div class="card bg-primary text-white text-center">
          <div class="card-body">
            <h5><i class="bi bi-cash-stack"></i> Valor Inventario</h5>
            <p class="display-4 fw-bold mb-0"><small>C$<?= number_format($valorInventario, 0) ?></small></p>
            <small>Total en productos</small>
          </div>
        </div>
      </div>

      <!-- Créditos Activos -->
      <div class="col-md-4 col-lg-3">
        <div class="card bg-dark text-white text-center">
          <div class="card-body">
            <h5><i class="bi bi-clock-history"></i> Créditos Activos</h5>
            <p class="display-4 fw-bold mb-0"><?= $ventasActivas ?></p>
            <small>Pendientes de pago</small>
          </div>
        </div>
      </div>
    </div>

    <!-- GRÁFICO -->
    <div class="row mt-5">
      <div class="col-md-6 mx-auto">
        <div class="card">
          <div class="card-body">
            <h3 class="card-title text-center mb-4">
              <i class="bi bi-pie-chart-fill"></i> Distribución de Ventas por Producto
            </h3>
            <div style="position: relative; height: 300px;">
              <canvas id="ventasChart"></canvas>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- VENTAS ACTIVAS A CRÉDITO -->
    <h3 class="mt-5"><i class="bi bi-credit-card"></i> Ventas Activas a Crédito</h3>
    <div class="dashboard-table-container mb-4">
      <table class="table table-hover align-middle">
        <thead class="table-light">
          <tr>
            <th>Cliente</th>
            <th>Productos</th>
            <th>Total</th>
            <th>Fecha Inicio</th>
            <th>Fecha Fin</th>
            <th>Días Restantes</th>
          </tr>
        </thead>
        <tbody>
          <?php

          // Función para calcular días entre dos fechas
          function diasEntreFechas($fecha_inicio, $fecha_fin)
          {
            $inicio = new DateTime($fecha_inicio);
            $fin = new DateTime($fecha_fin);
            $diferencia = $fin->diff($inicio);
            return (int)$diferencia->format('%a') + 1; // cuenta también el día inicial y el final
          }

           $sqlVentasActivas = $conexion->query("
             SELECT v.*, c.nombre AS cliente_nombre, c.apellido AS cliente_apellido, d.productos, d.total
             FROM ventas v
             INNER JOIN clientes c ON v.cliente_id = c.id
             LEFT JOIN (SELECT vd.venta_id, GROUP_CONCAT(CONCAT(p.nombre, ' (', vd.cantidad, ')') ORDER BY p.nombre SEPARATOR ', ') AS productos, SUM(vd.cantidad * vd.precio) AS total
                    FROM ventas_detalles vd INNER JOIN productos p ON vd.producto_id = p.id GROUP BY vd.venta_id) d ON d.venta_id = v.id
             WHERE v.tipo='Credito' AND v.fecha_fin > '$fecha_actual'
             ORDER BY v.fecha_fin ASC
             LIMIT 10
           ");

          if ($sqlVentasActivas->num_rows > 0) {
            while ($venta = $sqlVentasActivas->fetch_assoc()) {

              $dias_restantes = diasEntreFechas($venta['fecha_inicio'], $venta['fecha_fin']);
              $cliente_nombre = htmlspecialchars(
                $venta['cliente_nombre'] . ' ' . $venta['cliente_apellido'],
                ENT_QUOTES,
                'UTF-8'
              );

              $badge_class = ($dias_restantes <= 3) ? 'bg-danger' : (($dias_restantes <= 7) ? 'bg-warning text-dark' : 'bg-success');

              echo "
              <tr>
                  <td><strong>{$cliente_nombre}</strong></td>
                  <td><strong>" . htmlspecialchars($venta['productos'] ?? 'Sin productos', ENT_QUOTES, 'UTF-8') . "</strong></td>
                  <td>C$" . number_format((float)($venta['total'] ?? 0), 0) . "</td>
                  <td>" . date('d/m/Y', strtotime($venta['fecha_inicio'])) . "</td>
                  <td>" . date('d/m/Y', strtotime($venta['fecha_fin'])) . "</td>
                  <td>
                      <span class='badge $badge_class' 
                            data-fecha-inicio='{$venta['fecha_inicio']}' 
                            data-fecha-fin='{$venta['fecha_fin']}'>
                          {$dias_restantes} días
                      </span>
                  </td>
              </tr>";
            }
          } else {
            echo "<tr><td colspan='6' class='text-center text-muted'>No hay ventas activas a crédito</td></tr>";
          }
          ?>
        </tbody>
      </table>
    </div>

    <!-- Script opcional para actualizar días restantes en tiempo real -->
    <script>
      function actualizarDiasRestantes() {
        const badges = document.querySelectorAll('.badge');
        const hoy = new Date();
        badges.forEach(badge => {
          const fechaFin = new Date(badge.dataset.fechaFin);
          const diffTime = fechaFin - hoy;
          const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
          badge.textContent = diffDays + ' días';

          // Actualiza el color del badge según los días restantes
          if (diffDays <= 3) {
            badge.className = 'badge bg-danger';
          } else if (diffDays <= 7) {
            badge.className = 'badge bg-warning text-dark';
          } else {
            badge.className = 'badge bg-success';
          }
        });
      }

      // Actualiza cada día automáticamente (o recarga si quieres precisión al segundo)
      actualizarDiasRestantes();
    </script>


    <!-- ÚLTIMAS VENTAS -->
    <h3 class="mt-5"><i class="bi bi-clock-history"></i> Últimas Ventas Registradas</h3>
    <div class="dashboard-table-container mb-4">
      <table class="table table-hover align-middle">
        <thead class="table-light">
          <tr>
            <th>Fecha</th>
            <th>Cliente</th>
            <th>Productos</th>
            <th>Tipo</th>
            <th>Total</th>
            <th>Días Crédito</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $sqlUltimasVentas = $conexion->query("
              SELECT v.*, c.nombre AS cliente_nombre, c.apellido AS cliente_apellido, d.productos, d.total
              FROM ventas v
              INNER JOIN clientes c ON v.cliente_id = c.id
              LEFT JOIN (SELECT vd.venta_id, GROUP_CONCAT(CONCAT(p.nombre, ' (', vd.cantidad, ')') ORDER BY p.nombre SEPARATOR ', ') AS productos, SUM(vd.cantidad * vd.precio) AS total
                         FROM ventas_detalles vd INNER JOIN productos p ON vd.producto_id = p.id GROUP BY vd.venta_id) d ON d.venta_id = v.id
              ORDER BY v.fecha_inicio DESC, v.id DESC
              LIMIT 10
          ");

          if ($sqlUltimasVentas->num_rows > 0) {
            while ($venta = $sqlUltimasVentas->fetch_assoc()) {

              $tipo_badge = ($venta['tipo'] == 'Credito')
                ? 'bg-warning text-dark'
                : 'bg-success';

              $dias_credito = $venta['total_dias']
                ? $venta['total_dias'] . ' días'
                : '-';

              $cliente_nombre = htmlspecialchars(
                $venta['cliente_nombre'] . ' ' . $venta['cliente_apellido'],
                ENT_QUOTES,
                'UTF-8'
              );

              echo "
                  <tr>
                      <td>" . date('d/m/Y', strtotime($venta['fecha_inicio'])) . "</td>
                      <td><strong>{$cliente_nombre}</strong></td>
                      <td><strong>" . htmlspecialchars($venta['productos'] ?? 'Sin productos', ENT_QUOTES, 'UTF-8') . "</strong></td>
                      <td><span class='badge $tipo_badge'>{$venta['tipo']}</span></td>
                      <td>C$" . number_format((float)($venta['total'] ?? 0), 0) . "</td>
                      <td>$dias_credito</td>
                  </tr>";
            }
          } else {
            echo "<tr><td colspan='6' class='text-center text-muted'>No hay ventas registradas</td></tr>";
          }
          ?>
        </tbody>
      </table>
    </div>

    <!-- PRODUCTOS BAJO STOCK -->
    <h3 class="mt-5"><i class="bi bi-exclamation-triangle"></i> Productos con Bajo Stock</h3>
    <div class="dashboard-table-container mb-5">
      <table class="table table-hover align-middle">
        <thead class="table-light">
          <tr>
            <th>Producto</th>
            <th>Descripción</th>
            <th>Stock Actual</th>
            <th>Precio Unitario</th>
            <th>Valor Total</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $sqlBajoStock = $conexion->query("
              SELECT *
              FROM productos
              WHERE cantidad < 10
              ORDER BY cantidad ASC
              LIMIT 5
          ");

          if ($sqlBajoStock->num_rows > 0) {
            while ($producto = $sqlBajoStock->fetch_assoc()) {

              $valor_total = $producto['precio'] * $producto['cantidad'];
              $badge_class = ($producto['cantidad'] <= 5)
                ? 'bg-danger'
                : 'bg-warning text-dark';

              echo "
                  <tr>
                      <td><strong>{$producto['nombre']}</strong></td>
                      <td>{$producto['descripcion']}</td>
                      <td><span class='badge {$badge_class}'>{$producto['cantidad']} unidades</span></td>
                      <td>C$" . number_format($producto['precio'], 0) . "</td>
                      <td>C$" . number_format($valor_total, 0) . "</td>
                  </tr>";
            }
          } else {
            echo "<tr><td colspan='5' class='text-center text-muted'>No hay productos con bajo stock</td></tr>";
          }
          ?>
        </tbody>
      </table>
    </div>

  </main>

  <!-- Chart.js -->
  <?php include('src/includes/Dependencias/Chart.php'); ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>