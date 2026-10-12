<?php require_once __DIR__ . "/../../Config/Ruta.php"; ?>
<!DOCTYPE html>  
<html lang="es">  
<head>  
    <meta charset="UTF-8">  
    <meta name="viewport" content="width=device-width, initial-scale=1">  
    <title>Editar Cliente</title>  
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>src/css/styles.css" />  
</head>  
<body class="bg-dark">  
      
    <div class="container" style="max-width: 650px;">  
        
        <div class="form-card-container">
            <h1 class="form-title-custom text-center mb-4">📝 Editar Cliente</h1> 
            
            <form action="<?php echo BASE_URL; ?>CRUD/Cliente/editarCliente.php" method="post">  
                <?php
                require_once BASE_PATH . 'Config/Conexion.php';

                // Obtener datos del usuario  
                $sql = "SELECT id, nombre, apellido  
                    FROM clientes   
                    WHERE id = " . $_GET['Id'];
                $resultado = $conexion->query($sql);
                $row = $resultado->fetch_assoc();
                ?>

                <input type="hidden" name="Id" value="<?php echo $row['id']; ?>">

                <div class="mb-3">  
                    <label class="form-label-custom">Nombre del Cliente</label>  
                    <input type="text" class="form-control form-control-custom" name="NombreCliente" value="<?php echo $row['nombre']; ?>" required>  
                </div>  
                  
                <div class="mb-3">  
                    <label class="form-label-custom">Apellido del Cliente</label>  
                    <input type="text" class="form-control form-control-custom" name="ApellidoCliente" value="<?php echo $row['apellido']; ?>" required>  
                </div>  
                  
                <div class="d-flex justify-content-center gap-3">  
                    <button type="submit" class="btn-submit-custom">
                        <i class="bi bi-arrow-clockwise me-1"></i> Actualizar
                    </button>  
                    <a href="<?php echo BASE_URL; ?>pages/cliente.php" class="btn-cancel-custom text-decoration-none d-flex align-items-center justify-content-center">
                        <i class="bi bi-x-circle-fill me-1"></i> 
                        Cancelar
                    </a>
                </div>  
            </form>  
        </div>

    </div>  

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>  
</body>  
</html>