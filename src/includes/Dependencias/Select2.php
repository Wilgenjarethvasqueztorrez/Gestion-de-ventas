<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>  
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    window.inicializarSelect2Productos = function(root) {
        const $root = $(root || document);
        $root.find('.producto-select').addBack('.producto-select').each(function() {
            if (!$(this).hasClass('select2-hidden-accessible')) {
                $(this).select2({ width: '100%', language: 'es' });
            }
        });
    };

    $(document).ready(function() {
        $('#ClienteId').select2({ width: '100%', language: 'es' });
        window.inicializarSelect2Productos();
    });

    $(document).on('change', '.producto-select', function() {
        if (window.actualizarProductoVenta) {
            window.actualizarProductoVenta(this);
        }
    });
</script>