<!-- Inicializar DataTables -->
<!-- Primero: JavaScript de DataTables -->  
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>  
<!-- NUEVO: DataTables Responsive JS -->  
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>  
<!-- NUEVOS: DataTables Buttons -->  
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>  
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>  
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>  
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>  
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>  
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
  
<script>  
$(document).ready(function() {  
    $('#tabla').DataTable({  
        "language": {  
            "url": "//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json"  
        },  
        // NUEVO: Activar responsive  
        "responsive": true  
        
    });  
      
    // Para la segunda tabla en dia_no_laboral.php  
    $('#tabla-contado').DataTable({  
        "language": {  
            "url": "//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json"  
        },  
        
    });  
  
});  
</script>