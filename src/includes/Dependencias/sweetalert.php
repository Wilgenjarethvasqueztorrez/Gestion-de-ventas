<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // CONFIGURACIÓN DE ANIMACIONES Y COLORES BASE (UML)
    const swalConfigBase = {
        background: '#ffffff',
        color: '#333333',
        backdrop: `rgba(15, 37, 97, 0.4)`, // Fondo oscuro azul translúcido
        customClass: {
            popup: 'border-radius-12 shadow-lg',
            confirmButton: 'btn btn-primary px-4 py-2 fw-semibold mx-1',
            cancelButton: 'btn btn-secondary px-4 py-2 fw-semibold mx-1'
        },
        buttonsStyling: false, // Permite que usemos clases CSS puras en vez de los estilos feos por defecto
        showClass: {
            popup: 'animate__animated animate__fadeInUp animate__faster' // Animación de entrada suave
        },
        hideClass: {
            popup: 'animate__animated animate__fadeOutDown animate__faster'
        }
    };

    function confirmarEliminacion(url) {
        Swal.fire({
            ...swalConfigBase,
            title: '<span style="color: #1d439c; font-weight:700;">¿Está seguro?</span>',
            html: '<p style="color: #64748b; margin-bottom:0;">¿Realmente desea eliminar este registro? Esta acción no se puede deshacer.</p>',
            icon: 'warning',
            iconColor: '#ef4444',
            showCancelButton: true,
            confirmButtonText: '<i class="bi bi-trash3-fill me-1"></i> Sí, eliminar',
            cancelButtonText: 'Cancelar',
            customClass: {
                popup: 'border-radius-12',
                confirmButton: 'btn btn-danger px-4 py-2 fw-semibold mx-2', // Rojo para peligro
                cancelButton: 'btn btn-secondary px-4 py-2 fw-semibold mx-2'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirige a la URL que ejecuta el borrado PHP
                window.location.href = url;
            }
        });
    }
</script>

<script>
    // Detectar parámetros de URL para mostrar mensajes  
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const success = urlParams.get('success');
        const error = urlParams.get('error');

        if (success) {

            // 👉 CASOS NORMALES (siguen funcionando igual)
            let titulo = '<span style="color: #10b981; font-weight:700;">¡Éxito!</span>';
            let mensaje = '';

            switch (success) {
                case 'agregado':
                    mensaje = 'Registro creado y guardado en el sistema correctamente.';
                    break;
                case 'editado':
                    mensaje = 'Los cambios se han actualizado exitosamente.';
                    break;
                case 'eliminado':
                    titulo = '<span style="color: #1d439c; font-weight:700;">¡Eliminado!</span>';
                    mensaje = 'El registro ha sido removido del sistema.';
                    break;
                default:
                    mensaje = 'Operación realizada exitosamente';
            }

            Swal.fire({
                ...swalConfigBase,
                icon: 'success',
                iconColor: '#10b981', // Verde esmeralda institucional
                title: titulo,
                text: mensaje,
                timer: 2300,
                showConfirmButton: false
            });

            // Limpiar URL sin recargar la página  
            window.history.replaceState({}, document.title, window.location.pathname);
        }

        if (error) {
            let mensaje = '';

            switch (error) {
                case 'db':
                    mensaje = 'Hubo un inconveniente con la base de datos del portal.';
                    break;
                case 'datos':
                    mensaje = 'Los datos proporcionados son inválidos o están incompletos.';
                    break;
                default:
                    mensaje = 'Ocurrió un error al procesar la solicitud';
            }

            Swal.fire({
                ...swalConfigBase,
                icon: 'error',
                iconColor: '#ef4444',
                title: '<span style="color: #ef4444; font-weight:700;">Error</span>',
                text: mensaje,
                confirmButtonText: 'Entendido',
                customClass: {
                    popup: 'border-radius-12',
                    confirmButton: 'btn btn-primary px-4 py-2 fw-semibold' // Azul UML
                }
            });

            window.history.history.replaceState({}, document.title, window.location.pathname);
        }
    });
</script>

<style>
    .swal2-popup.border-radius-12 {
        border-radius: 16px !important;
        font-family: 'Segoe UI', system-ui, sans-serif !important;
        padding: 2rem 1.5rem !important;
    }
</style>