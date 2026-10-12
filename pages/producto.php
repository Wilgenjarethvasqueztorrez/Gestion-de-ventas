<?php
// Proteger la página  
require("../Config/Ruta.php");
?>

<!doctype html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Productos</title>
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
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>src/css/styles.css">
</head>

<body>

  <?php include(BASE_PATH . "src/includes/Componentes/sidebar.php"); ?>

  <main class="container mt-4">

    <h1 class="bg-primary p-3 text-white text-center rounded"><img src="https://cdn3.emoji.gg/emojis/1197-mugiwara.png" width="50px" height="50px" alt="Mugiwara"> GESTIÓN DE PRODUCTOS</h1>

    <div class="text-end mb-3">
      <a href="<?php echo BASE_URL; ?>Formularios/Producto/AgregarProducto.php" class="btn btn-success">
        <i class="bi bi-plus-circle"></i> Agregar Producto
      </a>
    </div>

    <div class="table-container">
      <table id="tabla" class="table table-hover">
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Codigo</th>
            <th>Precio</th>
            <th>Stock</th>
            <th>Valor Total</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php
          require(BASE_PATH . "Config/Conexion.php");
          $sql = $conexion->query("SELECT * FROM productos ORDER BY nombre ASC");
          while ($resultado = $sql->fetch_assoc()) {
            $valor_total = $resultado['precio'] * $resultado['cantidad'];
            $stock_badge = $resultado['cantidad'] <= 5 ? 'bg-danger' : ($resultado['cantidad'] <= 10 ? 'bg-warning text-dark' : 'bg-success');
          ?>
            <tr>
              <td><strong><?php echo $resultado['nombre']; ?></strong></td>
              <td><?php echo $resultado['descripcion']; ?></td>
              <td>
                <?php
                if ($resultado['codigo']) {
                  echo "<span class='badge bg-info border'> " . (($resultado['codigo'])) .
                    "</span>";
                } else {
                  echo "<span class='badge bg-fujitora border'> Sin codigo</span>";
                }
                ?>
              </td>
              <td><span class="badge btn-akainu">C$<?php echo number_format($resultado['precio'], 0); ?></span>
              </td>
              <td><span class="badge <?php echo $stock_badge; ?>"><?php echo $resultado['cantidad']; ?>
                  unidades</span></td>
              <td>C$<?php echo number_format($valor_total, 0); ?></td>
              <td class="acciones">
                <a href="<?php echo BASE_URL; ?>Formularios/Producto/EditarProducto.php?Id=<?php echo $resultado['id']; ?>"
                  class="btn btn-warning btn-sm">
                  <i class="bi bi-pencil"></i> Editar
                </a>
                <a href="<?php echo BASE_URL; ?>CRUD/Producto/eliminarProducto.php?Id=<?php echo $resultado['id']; ?>"
                  class="btn btn-danger btn-sm"
                  onclick="event.preventDefault(); confirmarEliminacion(this.href)">
                  <i class="bi bi-trash3"></i> Eliminar
                </a>
              </td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </main>

  <!-- Inicializar DataTables -->
  <?php include(BASE_PATH . "src/includes/Dependencias/datatables.php"); ?>

  <!-- Inicializar SweetAlert2 -->
  <?php include(BASE_PATH . "src/includes/Dependencias/sweetalert.php"); ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>