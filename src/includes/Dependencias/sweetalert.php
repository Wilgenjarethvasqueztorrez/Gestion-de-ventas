<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>  
function confirmarEliminacion(url) {  
    Swal.fire({  
        title: '¿Está seguro?',  
        text: "¿Desea eliminar este registro?",  
        icon: 'warning',  
        showCancelButton: true,  
        confirmButtonColor: '#d33',  
        cancelButtonColor: '#3085d6',  
        confirmButtonText: 'Sí, eliminar',  
        cancelButtonText: 'Cancelar'  
    }).then((result) => {  
        if (result.isConfirmed) {  
            // Mostrar Pace.js automáticamente durante la navegación  
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
        let titulo = '¡Éxito!';  
        let mensaje = '';  
          
        switch(success) {  
            case 'agregado':  
                mensaje = 'Registro agregado exitosamente';  
                break;  
            case 'editado':  
                mensaje = 'Registro actualizado exitosamente';  
                break;  
            case 'eliminado':  
                mensaje = 'Registro eliminado exitosamente';  
                break;  
            default:  
                mensaje = 'Operación realizada exitosamente';  
        }  
          
        Swal.fire({  
            icon: 'success',  
            title: titulo,  
            text: mensaje,  
            timer: 2000,  
            showConfirmButton: false  
        });  
          
        // Limpiar URL sin recargar la página  
        window.history.replaceState({}, document.title, window.location.pathname);  
    }  
      
    if (error) {  
        let mensaje = '';  
          
        switch(error) {  
            case 'db':  
                mensaje = 'Error en la base de datos';  
                break;  
            case 'datos':  
                mensaje = 'Error en los datos proporcionados';  
                break;  
            default:  
                mensaje = 'Ocurrió un error al procesar la solicitud';  
        }  
          
        Swal.fire({  
            icon: 'error',  
            title: 'Error',  
            text: mensaje  
        });  
          
        window.history.replaceState({}, document.title, window.location.pathname);  
    }  
 });  
</script>