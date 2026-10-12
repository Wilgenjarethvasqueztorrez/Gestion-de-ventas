<?php require_once __DIR__ . "/../../Config/Ruta.php"; ?>
<!DOCTYPE html>    
<html lang="es">    
<head>    
    <meta charset="UTF-8">    
    <meta name="viewport" content="width=device-width, initial-scale=1">    
    <title>Agregar Producto</title>    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>src/css/styles.css?v=1.9" />       
</head>    
<body class="bg-dark">  
      
    <div class="container max-w-md" style="max-width: 650px;">  
        
        <div class="form-card-container">
            <h1 class="form-title-custom text-center mb-4">✨ Agregar Producto</h1> 

        <form action="<?php echo BASE_URL; ?>CRUD/Producto/insertarProducto.php" method="post">    
            <!-- Nombre -->    
            <div class="mb-3">    
                <label class="form-label-custom">Nombre del Producto</label>    
                <input type="text" class="form-control form-control-custom" name="NombreProducto" required>    
            </div>    
  
            <!-- Descripción -->    
            <div class="mb-3">    
                <label class="form-label-custom">Descripción</label>    
                <input type="text" class="form-control form-control-custom" name="Descripcion" placeholder="Ej: Producto de alta calidad">    
            </div>    
  
            <!-- Codigo -->    
            <div class="mb-3">    
                <label class="form-label-custom">Codigo</label>    
                <input type="text" class="form-control form-control-custom" name="Codigo" min="0">    
            </div>    

            <!-- Precio -->    
            <div class="mb-3">    
                <label class="form-label-custom">Precio</label>    
                <input type="number" class="form-control form-control-custom" name="Precio" min="0" required>    
            </div>    
  
            <!-- Cantidad -->    
            <div class="mb-4">    
                <label class="form-label-custom">Cantidad en Stock</label>    
                <input type="number" class="form-control form-control-custom" name="Cantidad" min="0" required>    
            </div>    
  
            <!-- Botones -->    
           <div class="d-flex justify-content-center gap-3">  
                    <button type="submit" class="btn-submit-custom">
                        <i class="bi bi-check-circle-fill me-1"></i> Registrar
                    </button>      
                <a href="<?php echo BASE_URL; ?>pages/producto.php" class="btn-cancel-custom text-decoration-none d-flex align-items-center justify-content-center">
                        <i class="bi bi-x-circle-fill me-1"></i> 
                        Cancelar
                    </a>
            </div>    
        </form>    
    </div>    
  
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>    
</body>    
</html>