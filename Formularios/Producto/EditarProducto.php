<!doctype html>    
<html lang="es">    
<head>    
   <meta charset="UTF-8">    
   <meta name="viewport" content="width=device-width, initial-scale=1">    
   <title>Editar Producto</title>    
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">    
</head>    
<body>    
 <h1 class="bg-primary p-2 text-white text-center">Editar Producto</h1>    
 <br>    
 <form class="container" action="../../CRUD/Producto/editarProducto.php" method="post">    
  <?php    
   include ('../../Config/Conexion.php');    
   $sql = "SELECT * FROM productos WHERE id = " . $_GET['Id'];    
   $resultado = $conexion->query($sql);    
   $row = $resultado->fetch_assoc();    
  ?>    
  <input type="hidden" class="form-control" name="Id" value="<?php echo $row['id']; ?>">    
      
  <!-- Nombre -->    
  <div class="mb-3">    
    <label class="form-label">Nombre del Producto</label>    
    <input type="text" class="form-control" name="NombreProducto" value="<?php echo $row['nombre']; ?>" required>    
  </div>    
  
  <!-- Descripción -->    
  <div class="mb-3">    
    <label class="form-label">Descripción</label>    
    <input type="text" class="form-control" name="Descripcion" value="<?php echo $row['descripcion']; ?>" placeholder="Ej: Producto de alta calidad">    
  </div>    
  
  <!-- Precio -->    
  <div class="mb-3">    
    <label class="form-label">Precio</label>    
    <input type="number" class="form-control" name="Precio" value="<?php echo $row['precio']; ?>" min="0" required>    
  </div>    
  
  <!-- Cantidad -->    
  <div class="mb-3">    
    <label class="form-label">Cantidad en Stock</label>    
    <input type="number" class="form-control" name="Cantidad" value="<?php echo $row['cantidad']; ?>" min="0" required>    
  </div>    
  
  <!-- Botones -->    
  <div class="text-center">    
    <button type="submit" class="btn btn-primary">Actualizar</button>    
    <a href="../../producto.php" class="btn btn-dark">Cancelar</a>    
  </div>    
 </form>    
</body>    
</html>