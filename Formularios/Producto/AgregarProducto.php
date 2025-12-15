<!DOCTYPE html>    
<html lang="es">    
<head>    
    <meta charset="UTF-8">    
    <meta name="viewport" content="width=device-width, initial-scale=1">    
    <title>Agregar Producto</title>    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../src/css/styles.css">        
</head>    
<body>    
    <h1 class="bg-primary p-2 text-white text-center">Agregar Producto</h1>    
    <div class="container">    
        <form action="../../CRUD/Producto/insertarProducto.php" method="post">    
            <!-- Nombre -->    
            <div class="mb-3">    
                <label class="form-label">Nombre del Producto</label>    
                <input type="text" class="form-control" name="NombreProducto" required>    
            </div>    
  
            <!-- Descripción -->    
            <div class="mb-3">    
                <label class="form-label">Descripción</label>    
                <input type="text" class="form-control" name="Descripcion" placeholder="Ej: Producto de alta calidad">    
            </div>    
  
            <!-- Precio -->    
            <div class="mb-3">    
                <label class="form-label">Precio</label>    
                <input type="number" class="form-control" name="Precio" min="0" required>    
            </div>    
  
            <!-- Cantidad -->    
            <div class="mb-3">    
                <label class="form-label">Cantidad en Stock</label>    
                <input type="number" class="form-control" name="Cantidad" min="0" required>    
            </div>    
  
            <!-- Botones -->    
            <div class="text-center">    
                <button type="submit" class="btn btn-primary">Registrar</button>    
                <a href="../../producto.php" class="btn btn-dark">Cancelar</a>    
            </div>    
        </form>    
    </div>    
  
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>    
</body>    
</html>