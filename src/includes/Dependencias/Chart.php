<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<?php  
// Consulta para obtener las unidades vendidas por producto
$sqlVentasPorProducto = $conexion->query("  
    SELECT p.nombre, COALESCE(SUM(vd.cantidad), 0) AS total_unidades
    FROM productos p  
    INNER JOIN ventas_detalles vd ON p.id = vd.producto_id
    GROUP BY p.id, p.nombre  
    HAVING total_unidades > 0
    ORDER BY total_unidades DESC
    LIMIT 10  
");  
  
$productos = [];  
$ventas = [];  
  
while ($row = $sqlVentasPorProducto->fetch_assoc()) {  
    $productos[] = $row['nombre'];  
    $ventas[] = (int)$row['total_unidades'];
}  
?>  
  
<script>  
// Configuración del gráfico de pastel para ventas por producto  
const ctx = document.getElementById('ventasChart').getContext('2d');  
const ventasChart = new Chart(ctx, {  
    type: 'pie',  
    data: {  
        labels: <?php echo json_encode($productos); ?>,  
        datasets: [{  
            label: 'Unidades vendidas',
            data: <?php echo json_encode($ventas); ?>,  
            backgroundColor: [  
                '#FF6384',  
                '#36A2EB',  
                '#FFCE56',  
                '#4BC0C0',  
                '#9966FF',  
                '#FF9F40',  
                '#FF6384',  
                '#C9CBCF',  
                '#4BC0C0',  
                '#FF6384'  
            ],  
            borderWidth: 2,  
            borderColor: '#fff'  
        }]  
    },  
    options: {  
        responsive: true,  
        plugins: {  
            legend: {  
                position: 'bottom',  
                labels: {  
                    padding: 15,  
                    font: {  
                        size: 12  
                    }  
                }  
            },  
            title: {  
                display: true,  
                text: 'Top 10 Productos Más Vendidos',  
                font: {  
                    size: 16  
                }  
            },  
            tooltip: {  
                callbacks: {  
                    label: function(context) {  
                        let label = context.label || '';  
                        if (label) {  
                            label += ': ';  
                        }  
                        const total = context.dataset.data.reduce((a, b) => a + b, 0);  
                        const percentage = ((context.parsed / total) * 100).toFixed(1);  
                        label += context.parsed + ' unidades (' + percentage + '%)';
                        return label;  
                    }  
                }  
            }  
        }  
    }  
});  
</script>