<!DOCTYPE html>    
<html lang="es">    
<head>    
    <meta charset="UTF-8">    
    <meta name="viewport" content="width=device-width, initial-scale=1">    
    <title>Agregar Venta</title>    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
      <!-- Agregar Flatpickr -->  
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.css">  
    <!-- Agregar Select2 -->  
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />      
</head>    

<body>    
    <h1 class="bg-primary p-2 text-white text-center">Agregar Venta</h1>    

    <div class="container">    

        <form action="../../CRUD/Venta/insertarVenta.php" method="post">    

            <!-- Producto -->    
            <label>Producto</label>    
            <select class="form-select mb-3" name="ProductoId" id="ProductoId" required onchange="actualizarStockMaximo()">    
                <option value="" selected disabled>--Seleccionar producto--</option>    

                <?php    
                include ("../../Config/Conexion.php");    

                $sql = $conexion->query("SELECT id, nombre, precio, cantidad     
                                         FROM productos     
                                         WHERE cantidad > 0        
                                         ORDER BY nombre ASC");    

                while ($resultado = $sql->fetch_assoc()) {    
                    echo "<option value='" . $resultado['id'] . "' 
                               data-stock='" . $resultado['cantidad'] . "'>
                              " . $resultado['nombre'] . " - $" . number_format($resultado['precio'], 0) . 
                             " (Stock: " . $resultado['cantidad'] . ")
                          </option>";    
                }    
                ?>    

            </select>    

            <!-- Cantidad -->    
            <div class="mb-3">    
                <label class="form-label">Cantidad</label>    
                <input type="number" class="form-control" name="Cantidad" id="Cantidad" min="1" required>    
                <small class="text-muted">Cantidad disponible: <span id="stockDisponible">-</span></small>    
            </div>    

            <!-- Tipo de venta -->    
            <label>Tipo de Venta</label>    
            <select class="form-select mb-3" name="Tipo" id="Tipo" required onchange="toggleFechasCredito()">    
                <option value="" selected disabled>--Seleccionar tipo--</option>    
                <option value="Contado">Contado (Pago inmediato)</option>    
                <option value="Credito">Crédito (Pago diferido)</option>    
            </select>    

            <!-- Fechas y estado para crédito -->    
            <div id="fechasCredito" style="display: none;">    

                <div class="mb-3">    
                    <label class="form-label">Fecha Inicio Crédito</label>    
                    <input type="date" class="form-control" name="FechaInicio">    
                </div>    

                <div class="mb-3">    
                    <label class="form-label">Fecha Fin Crédito</label>    
                    <input type="date" class="form-control" name="FechaFin">    
                </div>    

                <div>
                <label>Estado de venta</label>    
                 <select class="form-select mb-3" name="EstadoVenta" id="EstadoVenta">    
                    <option value="" selected disabled>--Seleccionar estado--</option>    
                    <option value="Pendiente">Pendiente</option>    
                    <option value="Cancelada">Cancelada</option>    
                 </select>    
                </div>
            </div>    

            <!-- Botones -->    
            <div class="text-center">    
                <button type="submit" class="btn btn-primary">Registrar</button>    
                <a href="../../venta.php" class="btn btn-dark">Cancelar</a>    
            </div>    

        </form>    

    </div>    

    <script>    

        /* Mostrar u ocultar fechas  */
        function toggleFechasCredito() {    
            const tipo = document.getElementById('Tipo').value;    
            const fechasCredito = document.getElementById('fechasCredito');    
            const fechaInicio = fechasCredito.querySelector('input[name="FechaInicio"]');    
            const fechaFin = fechasCredito.querySelector('input[name="FechaFin"]');    
            const estadoVenta = fechasCredito.querySelector('input[name="EstadoVenta"]');    

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


        /* Mostrar stock disponible  y colocar límite en cantidad*/
        function actualizarStockMaximo() {  
            const select = document.getElementById('ProductoId');  
            const cantidadInput = document.getElementById('Cantidad');  
            const stockSpan = document.getElementById('stockDisponible');  

            if (select.selectedIndex > 0) {  
                const stock = select.options[select.selectedIndex].getAttribute('data-stock');  
                
                stockSpan.textContent = stock;  
                cantidadInput.max = stock;  
                cantidadInput.value = 1;  
            } else {  
                stockSpan.textContent = '-';  
                cantidadInput.max = '';  
                cantidadInput.value = '';  
            }  
        }  

    </script>    

    <!-- Incluir Flatpickr -->
    <?php include('../../src/includes/Dependencias/Flatpickr.php'); ?> 
    <!-- Incluir Select2 -->
    <?php include('../../src/includes/Dependencias/Select2.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>    

</body>    
</html>
