<?php require_once __DIR__ . "/../../Config/Ruta.php"; ?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Editar Venta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Agregar Flatpickr -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.css">
    <!-- Agregar Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>src/css/styles.css?v=1.9" />
    <?php include BASE_PATH . 'src/includes/Dependencias/bsStepper.php'; ?>
    <style>
        .producto-linea {
            border: 1px solid rgba(255, 255, 255, .18);
            border-radius: 6px;
            padding: 12px;
        }

        .producto-acciones {
            display: flex;
            align-items: flex-end;
            justify-content: center;
        }

        .quitar-producto {
            width: 42px;
            height: 42px;
            padding: 0;
        }

        .venta-resumen-flotante {
            position: sticky;
            bottom: 0;
            z-index: 5;
            border-top: 2px solid var(--bs-info);
            box-shadow: 0 -6px 18px rgba(0, 0, 0, .14);
        }
    </style>
</head>

<body class="bg-dark">

    <div class="container max-w-md" style="max-width: 650px;">

        <div class="form-card-container">
            <h1 class="form-title-custom text-center mb-4">✏️ Editar Venta</h1>

            <form class="container" action="<?php echo BASE_URL; ?>CRUD/Venta/editarVenta.php" method="post" id="formVenta" novalidate>
                <div id="ventaStepper" class="bs-stepper">
                    <div class="bs-stepper-header" role="tablist">
                        <div class="step" data-target="#pasoCliente">
                            <button type="button" class="step-trigger" role="tab" aria-controls="pasoCliente"><span class="bs-stepper-circle">1</span><span class="bs-stepper-label">Cliente y pago</span></button>
                        </div>
                        <div class="bs-stepper-line"></div>
                        <div class="step" data-target="#pasoProductos">
                            <button type="button" class="step-trigger" role="tab" aria-controls="pasoProductos"><span class="bs-stepper-circle">2</span><span class="bs-stepper-label">Productos</span></button>
                        </div>
                        <div class="bs-stepper-line"></div>
                        <div class="step" data-target="#pasoCondiciones">
                            <button type="button" class="step-trigger" role="tab" aria-controls="pasoCondiciones"><span class="bs-stepper-circle">3</span><span class="bs-stepper-label">Confirmar</span></button>
                        </div>
                    </div>
                    <div class="bs-stepper-content">
                        <div id="pasoCliente" class="content" role="tabpanel">
                            <?php
                            require_once BASE_PATH . 'Config/Conexion.php';
                            $id = filter_input(INPUT_GET, 'Id', FILTER_VALIDATE_INT);
                            $consultaVenta = $conexion->prepare("SELECT * FROM ventas WHERE id = ?");
                            $consultaVenta->bind_param("i", $id);
                            $consultaVenta->execute();
                            $row = $consultaVenta->get_result()->fetch_assoc();
                            if (!$row) {
                                header("Location: " . BASE_URL . "pages/venta.php?error=no_existe");
                                exit;
                            }
                            $consultaDetalles = $conexion->prepare("SELECT producto_id, cantidad FROM ventas_detalles WHERE venta_id = ?");
                            $consultaDetalles->bind_param("i", $id);
                            $consultaDetalles->execute();
                            $detalles = $consultaDetalles->get_result()->fetch_all(MYSQLI_ASSOC);
                            $productos = $conexion->query("SELECT id, nombre, descripcion, precio, cantidad FROM productos ORDER BY nombre ASC")->fetch_all(MYSQLI_ASSOC);
                            ?>
                            <input type="hidden" class="form-control form-control-custom" name="Id" value="<?php echo $row['id']; ?>">

                            <!-- Cliente -->
                            <div class="mb-3">
                                <label class="form-label-custom" for="ClienteId">Cliente</label>
                                <select class="form-select form-control-custom" name="ClienteId" id="ClienteId" required>
                                    <?php
                                    $sqlClientes = $conexion->query("SELECT id, nombre, apellido
                                                         FROM clientes
                                                         ORDER BY nombre ASC");
                                    while ($cliente = $sqlClientes->fetch_assoc()) {
                                        $selected = ($cliente['id'] == $row['cliente_id']) ? 'selected' : '';
                                        echo "<option value='" . $cliente['id'] . "' $selected>"
                                            . htmlspecialchars($cliente['nombre'] . ' ' . $cliente['apellido'], ENT_QUOTES, 'UTF-8')
                                            . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="form-label-custom" for="Tipo">Tipo de Venta</label>
                                <select class="form-select form-control-custom" name="Tipo" id="Tipo" required>
                                    <option value="Contado" <?php echo ($row['tipo'] == 'Contado') ? 'selected' : ''; ?>>💵 Contado (Pago inmediato)</option>
                                    <option value="Credito" <?php echo ($row['tipo'] == 'Credito') ? 'selected' : ''; ?>>💳 Crédito (Pago diferido)</option>
                                </select>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn-submit-custom stepper-next">Siguiente <i class="bi bi-arrow-right"></i></button>
                            </div>
                        </div>

                        <div id="pasoProductos" class="content" role="tabpanel">

                            <div class="mb-3">
                                <div class="d-flex align-items-center justify-content-between gap-2 mb-3">
                                    <h2 class="h5 mb-0">Productos de la venta</h2>
                                    <button type="button" class="btn btn-outline-info" id="agregarProducto"><i class="bi bi-plus-circle"></i> Agregar producto</button>
                                </div>
                                <div id="detallesVenta" class="d-grid gap-2">
                                    <?php foreach ($detalles as $detalle) { ?>
                                        <div class="row g-2 align-items-end detalle-venta producto-linea">
                                            <div class="col-md-6">
                                                <label class="form-label-custom">Producto</label>
                                                <select class="form-select form-control-custom producto-select" name="ProductoId[]" required>
                                                    <option value="" disabled>--Seleccionar producto--</option>
                                                    <?php foreach ($productos as $producto) {
                                                        $selected = ((int)$producto['id'] === (int)$detalle['producto_id']) ? 'selected' : '';
                                                        $descripcionProducto = htmlspecialchars($producto['descripcion'] ?: 'Sin descripción', ENT_QUOTES, 'UTF-8');
                                                        $nombreProducto = htmlspecialchars($producto['nombre'], ENT_QUOTES, 'UTF-8');
                                                    ?>
                                                        <option value="<?php echo (int)$producto['id']; ?>" data-stock="<?php echo (int)$producto['cantidad'] + ($selected ? (int)$detalle['cantidad'] : 0); ?>" data-precio="<?php echo (int)$producto['precio']; ?>" <?php echo $selected; ?>><?php echo $nombreProducto; ?> - <?php echo $descripcionProducto; ?> - C$<?php echo number_format($producto['precio'], 0); ?> (Stock: <?php echo (int)$producto['cantidad']; ?>)</option>
                                                    <?php } ?>
                                                </select>
                                                <small class="text-muted">Stock disponible: <span class="stock-disponible">-</span></small>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label-custom">Cantidad</label>
                                                <input type="number" class="form-control form-control-custom" name="Cantidad[]" min="1" value="<?php echo (int)$detalle['cantidad']; ?>" required>
                                            </div>
                                            <div class="col-md-2">
                                                <small class="d-block">Subtotal</small>
                                                <strong class="subtotal-producto">C$0</strong>
                                            </div>
                                            <div class="col-md-1 producto-acciones">
                                                <button type="button" class="btn btn-outline-danger quitar-producto" aria-label="Quitar producto" title="Quitar producto"><i class="bi bi-trash3"></i></button>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>

                            <template id="detalleTemplate">
                                <div class="row g-2 align-items-end detalle-venta producto-linea">
                                    <div class="col-md-6">
                                        <label class="form-label-custom">Producto</label>
                                        <select class="form-select form-control-custom producto-select" name="ProductoId[]" required>
                                            <option value="" selected disabled>--Seleccionar producto--</option>
                                            <?php foreach ($productos as $producto) {
                                                $nombreProducto = htmlspecialchars($producto['nombre'], ENT_QUOTES, 'UTF-8');
                                            ?>
                                                <option value="<?php echo (int)$producto['id']; ?>" data-stock="<?php echo (int)$producto['cantidad']; ?>" data-precio="<?php echo (int)$producto['precio']; ?>"><?php echo $nombreProducto; ?> - C$<?php echo number_format($producto['precio'], 0); ?> (Stock: <?php echo (int)$producto['cantidad']; ?>)</option>
                                            <?php } ?>
                                        </select>
                                        <small class="text-muted">Stock disponible: <span class="stock-disponible">-</span></small>
                                    </div>
                                    <div class="col-md-3"><label class="form-label-custom">Cantidad</label><input type="number" class="form-control form-control-custom" name="Cantidad[]" min="1" value="1" required></div>
                                    <div class="col-md-2"><small class="d-block">Subtotal</small><strong class="subtotal-producto">C$0</strong></div>
                                    <div class="col-md-1 producto-acciones"><button type="button" class="btn btn-outline-danger quitar-producto" aria-label="Quitar producto" title="Quitar producto"><i class="bi bi-trash3"></i></button></div>
                                </div>
                            </template>

                            <aside class="venta-resumen-flotante bg-light text-dark rounded-2 p-3 mt-3" aria-live="polite">
                                <div class="d-flex justify-content-between align-items-center gap-3">
                                    <div><small class="d-block"><span id="resumenProductos">0</span> productos · <span id="resumenUnidades">0</span> unidades</small><strong>Total estimado</strong></div>
                                    <strong class="fs-4 text-nowrap" id="totalVenta">C$0</strong>
                                </div>
                            </aside>

                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn-cancel-custom stepper-prev"><i class="bi bi-arrow-left"></i> Anterior</button>
                                <button type="button" class="btn-submit-custom stepper-next">Siguiente <i class="bi bi-arrow-right"></i></button>
                            </div>
                        </div>

                        <div id="pasoCondiciones" class="content" role="tabpanel">

                            <div id="contadoNota" class="alert alert-info" style="display: none;">💵 Venta de contado: no requiere fechas de crédito.</div>
                            <div id="fechasCredito" style="display: none;">
                                <div class="mb-3">
                                    <label class="form-label-custom">Fecha Inicio Crédito</label>
                                    <input type="date" class="form-control form-control-custom" name="FechaInicio" id="FechaInicio"
                                        value="<?php echo $row['fecha_inicio']; ?>" onchange="calcularDias()">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label-custom">Fecha Fin Crédito</label>
                                    <input type="date" class="form-control form-control-custom" name="FechaFin" id="FechaFin"
                                        value="<?php echo $row['fecha_fin']; ?>" onchange="calcularDias()">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label-custom">Total de Días</label>
                                    <input type="number" class="form-control form-control-custom" name="TotalDias" id="TotalDias"
                                        value="<?php echo $row['total_dias']; ?>" readonly>
                                </div>

                                <!-- ADDED: Estado field for credit sales -->
                                <div class="mb-4">
                                    <label class="form-label-custom">Estado de venta</label>
                                    <select class="form-select form-control-custom" name="EstadoVenta" id="EstadoVenta">
                                        <option value="Pendiente" <?php echo ($row['estado'] == 'Pendiente') ? 'selected' : ''; ?>>⌛ Pendiente</option>
                                        <option value="Cancelada" <?php echo ($row['estado'] == 'Cancelada') ? 'selected' : ''; ?>>❌ Cancelada</option>
                                    </select>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between gap-2">

                                <button type="button" class="btn-cancel-custom stepper-prev">
                                    <i class="bi bi-arrow-left"></i> Anterior
                                </button>

                                <button type="submit" class="btn-submit-custom">
                                    <i class="bi bi-arrow-clockwise"></i> Actualizar
                                </button>

                                <a href="<?php echo BASE_URL; ?>pages/venta.php" class="btn-cancel-custom text-decoration-none d-flex align-items-center justify-content-center">
                                    <i class="bi bi-x-circle-fill me-1"></i>
                                    Cancelar
                                </a>

                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <script>
                function toggleFechasCredito() {
                    const tipo = document.getElementById('Tipo').value;
                    const fechasCredito = document.getElementById('fechasCredito');
                    const contadoNota = document.getElementById('contadoNota');
                    const fechaInicio = fechasCredito.querySelector('input[name="FechaInicio"]');
                    const fechaFin = fechasCredito.querySelector('input[name="FechaFin"]');
                    const estadoVenta = fechasCredito.querySelector('select[name="EstadoVenta"]');
                    const totalDias = fechasCredito.querySelector('input[name="TotalDias"]');

                    if (tipo === 'Credito') {
                        fechasCredito.style.display = 'block';
                        contadoNota.style.display = 'none';
                        fechaInicio.required = true;
                        fechaFin.required = true;
                        estadoVenta.required = true;
                    } else {
                        fechasCredito.style.display = 'none';
                        contadoNota.style.display = tipo === 'Contado' ? 'block' : 'none';
                        fechaInicio.required = false;
                        fechaFin.required = false;
                        estadoVenta.required = false;
                        fechaInicio.value = '';
                        fechaFin.value = '';
                        estadoVenta.value = '';
                        totalDias.value = '';
                    }
                }

                function calcularDias() {
                    const fechaInicio = document.getElementById('FechaInicio').value;
                    const fechaFin = document.getElementById('FechaFin').value;

                    if (fechaInicio && fechaFin) {
                        const inicio = new Date(fechaInicio);
                        const fin = new Date(fechaFin);

                        const diferenciaTiempo = fin - inicio;
                        const diferenciaDias = Math.ceil(diferenciaTiempo / (1000 * 60 * 60 * 24)) + 1;

                        document.getElementById('TotalDias').value = diferenciaDias >= 0 ? diferenciaDias : 0;
                    }
                }

                const detallesVenta = document.getElementById('detallesVenta');
                const detalleTemplate = document.getElementById('detalleTemplate');
                const ventaStepperElement = document.getElementById('ventaStepper');
                const ventaStepper = new Stepper(ventaStepperElement, {
                    linear: false,
                    animation: true
                });
                let avanceValidado = false;

                ventaStepperElement.addEventListener('show.bs-stepper', function(event) {
                    if (event.detail.to > event.detail.from && !avanceValidado) {
                        event.preventDefault();
                    }
                });

                document.querySelectorAll('.stepper-next').forEach(function(button) {
                    button.addEventListener('click', function() {
                        const activePane = ventaStepperElement.querySelector('.content.active');
                        const invalidField = Array.from(activePane.querySelectorAll('input, select, textarea')).find(function(field) {
                            return field.willValidate && !field.checkValidity();
                        });
                        if (invalidField) {
                            invalidField.reportValidity();
                            return;
                        }
                        avanceValidado = true;
                        ventaStepper.next();
                        avanceValidado = false;
                    });
                });
                document.querySelectorAll('.stepper-prev').forEach(function(button) {
                    button.addEventListener('click', function() {
                        ventaStepper.previous();
                    });
                });
                document.getElementById('formVenta').addEventListener('submit', function(event) {
                    if (!this.checkValidity()) {
                        event.preventDefault();
                        this.reportValidity();
                    }
                });
                document.getElementById('Tipo').addEventListener('change', toggleFechasCredito);
                toggleFechasCredito();

                function formatoCordobas(valor) {
                    return 'C$' + new Intl.NumberFormat('es-NI', {
                        maximumFractionDigits: 0
                    }).format(valor);
                }

                function actualizarDetalle(fila) {
                    const selector = fila.querySelector('.producto-select');
                    const opcion = selector.selectedOptions[0];
                    const cantidadInput = fila.querySelector('input[name="Cantidad[]"]');
                    const precio = Number(opcion?.dataset.precio || 0);
                    const stock = opcion?.dataset.stock || '';
                    const cantidad = Math.max(0, Number.parseInt(cantidadInput.value, 10) || 0);
                    fila.querySelector('.stock-disponible').textContent = stock || '-';
                    cantidadInput.max = stock;
                    fila.querySelector('.subtotal-producto').textContent = formatoCordobas(precio * cantidad);
                    actualizarResumen();
                }

                window.actualizarProductoVenta = function(select) {
                    actualizarDetalle(select.closest('.detalle-venta'));
                };

                function actualizarResumen() {
                    const filas = Array.from(detallesVenta.querySelectorAll('.detalle-venta'));
                    let unidades = 0;
                    let total = 0;
                    let productosSeleccionados = 0;
                    filas.forEach(function(fila) {
                        const opcion = fila.querySelector('.producto-select').selectedOptions[0];
                        if (!opcion || !opcion.value) return;
                        productosSeleccionados++;
                        const cantidad = Math.max(0, Number.parseInt(fila.querySelector('input[name="Cantidad[]"]').value, 10) || 0);
                        unidades += cantidad;
                        total += Number(opcion.dataset.precio || 0) * cantidad;
                    });
                    document.getElementById('resumenProductos').textContent = productosSeleccionados;
                    document.getElementById('resumenUnidades').textContent = unidades;
                    document.getElementById('totalVenta').textContent = formatoCordobas(total);
                }

                function prepararFila(fila) {
                    fila.querySelector('.producto-select').addEventListener('change', function() {
                        actualizarDetalle(fila);
                    });
                    fila.querySelector('input[name="Cantidad[]"]').addEventListener('input', function() {
                        actualizarDetalle(fila);
                    });
                    if (window.inicializarSelect2Productos) window.inicializarSelect2Productos(fila);
                    actualizarDetalle(fila);
                }

                document.getElementById('agregarProducto').addEventListener('click', function() {
                    detallesVenta.appendChild(detalleTemplate.content.cloneNode(true));
                    prepararFila(detallesVenta.lastElementChild);
                });
                detallesVenta.addEventListener('click', function(event) {
                    const boton = event.target.closest('.quitar-producto');
                    if (boton && detallesVenta.children.length > 1) {
                        boton.closest('.detalle-venta').remove();
                        actualizarResumen();
                    }
                });
                if (detallesVenta.children.length === 0) {
                    detallesVenta.appendChild(detalleTemplate.content.cloneNode(true));
                }
                detallesVenta.querySelectorAll('.detalle-venta').forEach(prepararFila);

                document.addEventListener('DOMContentLoaded', function() {
                    calcularDias();
                });
            </script>

            <!-- Incluir Flatpickr -->
            <?php include BASE_PATH . 'src/includes/Dependencias/Flatpickr.php'; ?>
            <!-- Incluir Select2 -->
            <?php include BASE_PATH . 'src/includes/Dependencias/Select2.php'; ?>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>