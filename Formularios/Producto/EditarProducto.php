<?php require_once __DIR__ . "/../../Config/Ruta.php"; ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Editar Producto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>src/css/styles.css?v=1.9" />
</head>

<body class="bg-dark">

    <div class="container max-w-md" style="max-width: 650px;">

        <div class="form-card-container">
            <h1 class="form-title-custom text-center mb-4">✏️ Editar Producto</h1>
            <br>

            <form class="container" action="<?php echo BASE_URL; ?>CRUD/Producto/editarProducto.php" method="post">
                <?php
                require_once BASE_PATH . 'Config/Conexion.php';

                $sql = 'SELECT * FROM productos WHERE id = ' . $_GET['Id'];
                $resultado = $conexion->query($sql);
                $row = $resultado->fetch_assoc();
                ?>

                <input type="hidden" class="form-control" name="Id" value="<?php echo $row['id']; ?>">

                <!-- Nombre -->
                <div class="mb-3">
                    <label class="form-label-custom">Nombre del Producto</label>
                    <input type="text" class="form-control form-control-custom" name="NombreProducto" value="<?php echo $row['nombre']; ?>" required>
                </div>

                <!-- Descripción -->
                <div class="mb-3">
                    <label class="form-label-custom">Descripción</label>
                    <input
                        type="text"
                        class="form-control form-control-custom"
                        name="Descripcion"
                        value="<?php echo $row['descripcion']; ?>"
                        placeholder="Ej: Producto de alta calidad">
                </div>

                  <!-- Codigo -->
                <div class="mb-3">
                    <label class="form-label-custom">Codigo</label>
                    <input type="text" class="form-control form-control-custom" name="Codigo" value="<?php echo $row['codigo']; ?>" min="0" required>
                </div>

                <!-- Precio -->
                <div class="mb-3">
                    <label class="form-label-custom">Precio</label>
                    <input type="number" class="form-control form-control-custom" name="Precio" value="<?php echo $row['precio']; ?>" min="0" required>
                </div>

                <!-- Cantidad -->
                <div class="mb-4">
                    <label class="form-label-custom">Cantidad en Stock</label>
                    <input
                        type="number"
                        class="form-control form-control-custom"
                        name="Cantidad"
                        value="<?php echo $row['cantidad']; ?>"
                        min="0"
                        required>
                </div>

                <!-- Botones -->
                <div class="d-flex justify-content-center gap-3">
                    <button type="submit" class="btn-submit-custom">
                        <i class="bi bi-arrow-clockwise me-1"></i> Actualizar
                    </button>
                    <a href="<?php echo BASE_URL; ?>pages/producto.php" class="btn-cancel-custom text-decoration-none d-flex align-items-center justify-content-center">
                        <i class="bi bi-x-circle-fill me-1"></i>
                        Cancelar
                    </a>
                </div>
            </form>
</body>

</html>