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
            "responsive": true,
            "scrollX": false,
            "dom": 'Blfrtip', // Agregado 'l' para mostrar "Show X entries"  
            "buttons": [{
                    extend: 'copy',
                    className: 'btn btn-primary', // Quitado btn-sm  
                    text: '<i class="bi bi-clipboard"></i> Copiar',
                    exportOptions: {
                        columns: ':not(:last-child)' // Excluye la última columna
                    }
                },
                {
                    extend: 'excel',
                    className: 'btn btn-success', // Verde para Excel  
                    text: '<i class="bi bi-file-earmark-excel"></i> Excel',
                    exportOptions: {
                        columns: ':not(:last-child)' // Excluye la última columna
                    }
                },
                {
                    extend: 'pdf',
                    className: 'btn btn-danger', // Rojo para PDF  
                    text: '<i class="bi bi-file-earmark-pdf"></i> PDF',
                    exportOptions: {
                        columns: ':not(:last-child)' // Excluye la última columna
                    }
                },
                {
                    extend: 'print',
                    className: 'btn btn-secondary', // Gris para imprimir  
                    text: '<i class="bi bi-printer"></i> Imprimir',
                    exportOptions: {
                        columns: ':not(:last-child)' // Excluye la última columna
                    }
                }
            ]

        });

        // Para la segunda tabla en dia_no_laboral.php  
        $('#tabla-contado').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json"
            },

            "responsive": false,
            "scrollX": false,
            "dom": 'Blfrtip', // Agregado 'l' para mostrar "Show X entries"  
            "buttons": [{
                    extend: 'copy',
                    className: 'btn btn-primary', // Quitado btn-sm  
                    text: '<i class="bi bi-clipboard"></i> Copiar',
                    exportOptions: {
                        columns: ':not(:last-child)' // Excluye la última columna
                    }
                },
                {
                    extend: 'excel',
                    className: 'btn btn-success', // Verde para Excel  
                    text: '<i class="bi bi-file-earmark-excel"></i> Excel',
                    exportOptions: {
                        columns: ':not(:last-child)' // Excluye la última columna
                    }
                },
                {
                    extend: 'pdf',
                    className: 'btn btn-danger', // Rojo para PDF  
                    text: '<i class="bi bi-file-earmark-pdf"></i> PDF',
                    exportOptions: {
                        columns: ':not(:last-child)' // Excluye la última columna
                    }
                },
                {
                    extend: 'print',
                    className: 'btn btn-secondary', // Gris para imprimir  
                    text: '<i class="bi bi-printer"></i> Imprimir',
                    exportOptions: {
                        columns: ':not(:last-child)' // Excluye la última columna
                    }
                }
            ]
        });

    });
</script>