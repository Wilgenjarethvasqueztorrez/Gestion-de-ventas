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
    <link rel="stylesheet" href="../../src/css/styles.css">        
        <style>    
      .select2-container {    
          width: 100% !important; /* Igual que form-select */    
      }    
    
      .select2-selection {    
          min-height: 38px !important; /* Altura estándar de Bootstrap form-select */    
          padding: 0.375rem 0.75rem !important; /* Padding de Bootstrap */    
          font-size: 1rem !important;    
          line-height: 1.5 !important;    
          border: 1px solid #ced4da !important;    
          border-radius: 0.25rem !important;    
      }    
    
      .select2-selection__rendered {    
          padding: 0 !important;    
          line-height: 1.5 !important;    
      }    
    
      .select2-selection__arrow {    
          height: 36px !important;    
      }    
      
      /* Contenedor principal */  
.select2-container--default .select2-selection--single {  
    background-color: #132030 !important;  
    border: 2px solid #ffd700 !important;  
    color: #ff6b35 !important;  
}  
  
.select2-container--default .select2-selection--single .select2-selection__rendered {  
    color: #ff6b35 !important;  
}  
  
.select2-container--default .select2-selection--single .select2-selection__arrow b {  
    border-color: #ffd700 transparent transparent transparent !important;  
}  
  
/* Estado focus */  
.select2-container--default.select2-container--focus .select2-selection--single {  
    border-color: #ffd700 !important;  
    background-color: #1a2332 !important;  
}  
  
/* Dropdown */  
.select2-dropdown {  
    background-color: #132030 !important;  
    border: 2px solid #ffd700 !important;  
}  
  
/* Búsqueda en dropdown */  
.select2-search--dropdown .select2-search__field {  
    background-color: #1a2332 !important;  
    border: 2px solid #ffd700 !important;  
    color: #ff6b35 !important;  
}  
  
/* Opciones del dropdown */  
.select2-results__option {  
    color: #ff6b35 !important;  
}  
  
/* Hover en opciones */  
.select2-results__option--highlighted {  
    background-color: rgba(255, 107, 53, 0.2) !important;  
    color: #ffd700 !important;  
}  
  
/* Opción seleccionada */  
.select2-results__option[aria-selected="true"] {  
    background-color: rgba(255, 215, 0, 0.15) !important;  
    color: #ffd700 !important;  
}  
  
/* Select2 múltiple */  
.select2-container--default .select2-selection--multiple {  
    background-color: #132030 !important;  
    border: 2px solid #ffd700 !important;  
}  
  
.select2-container--default .select2-selection--multiple .select2-selection__choice {  
    background: #ff6b35 !important;  
    border: 1px solid #ffd700 !important;  
    color: white !important;  
}  
  
/* Placeholder */  
.select2-container--default .select2-selection--placeholder {  
    color: rgba(255, 107, 53, 0.5) !important;  
}
    </style>        
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
                    <input type="date" class="form-control" name="FechaInicio" id="FechaInicio" onchange="calcularDias()">      
                </div>      
  
                <div class="mb-3">      
                    <label class="form-label">Fecha Fin Crédito</label>      
                    <input type="date" class="form-control" name="FechaFin" id="FechaFin" onchange="calcularDias()">      
                </div>      
  
                <div class="mb-3">      
                    <label class="form-label">Total de Días</label>      
                    <input type="number" class="form-control" name="TotalDias" id="TotalDias" readonly>      
                </div>  
  
                <div class="mb-3">  
                    <label>Estado de venta</label>      
                    <select class="form-select" name="EstadoVenta" id="EstadoVenta">      
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
  
        /* Mostrar u ocultar fechas */  
        function toggleFechasCredito() {      
            const tipo = document.getElementById('Tipo').value;      
            const fechasCredito = document.getElementById('fechasCredito');      
            const fechaInicio = fechasCredito.querySelector('input[name="FechaInicio"]');      
            const fechaFin = fechasCredito.querySelector('input[name="FechaFin"]');      
            const estadoVenta = fechasCredito.querySelector('select[name="EstadoVenta"]');  
            const totalDias = fechasCredito.querySelector('input[name="TotalDias"]');  
  
            if (tipo === 'Credito') {      
                fechasCredito.style.display = 'block';      
                fechaInicio.required = true;      
                fechaFin.required = true;  
                estadoVenta.required = true;  
            } else {      
                fechasCredito.style.display = 'none';      
                fechaInicio.required = false;      
                fechaFin.required = false;  
                estadoVenta.required = false;  
                fechaInicio.value = '';      
                fechaFin.value = '';  
                estadoVenta.value = '';  
                totalDias.value = '';  
            }      
        }      
  
        /* Calcular días entre fechas */  
        function calcularDias() {  
            const fechaInicio = document.getElementById('FechaInicio').value;  
            const fechaFin = document.getElementById('FechaFin').value;  
  
            if (fechaInicio && fechaFin) {  
                const inicio = new Date(fechaInicio);  
                const fin = new Date(fechaFin);  
                  
                const diferenciaTiempo = fin - inicio;  
                const diferenciaDias = Math.ceil(diferenciaTiempo / (1000 * 60 * 60 * 24));  
                  
                document.getElementById('TotalDias').value = diferenciaDias >= 0 ? diferenciaDias : 0;  
            }  
        }  
  
        /* Mostrar stock disponible y colocar límite en cantidad */  
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