<?php
require_once 'conexion.php';
session_start();

if (!$conn) {
    die("Error de conexión a la base de datos.");
}

function obtenerDatos($query) {
    global $conn;
    $result = $conn->query($query);
    $labels = [];
    $values = [];
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $labels[] = $row['label'];
        $values[] = $row['total'];
    }
    return ['labels' => $labels, 'values' => $values];
}

$ventasPorLibro = obtenerDatos("SELECT libro_isbn AS label, SUM(Cantidad) AS total FROM Detalles_pedido GROUP BY libro_isbn");
$ventasMensuales = obtenerDatos("SELECT DATE_FORMAT(Pedidos.Fecha, '%Y-%m') AS label, SUM(Detalles_pedido.Cantidad) AS total FROM Detalles_pedido JOIN Pedidos ON Detalles_pedido.id_pedido = Pedidos.id GROUP BY label");
$ventasPorCliente = obtenerDatos("SELECT Clientes.Nombre AS label, SUM(Detalles_pedido.Cantidad) AS total FROM Detalles_pedido JOIN Pedidos ON Detalles_pedido.id_pedido = Pedidos.id JOIN Clientes ON Pedidos.cliente_id = Clientes.id GROUP BY label");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Shop - Estadísticas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .chart-container {
            width: 80%;
            margin: 0 auto 30px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">Library Shop</a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="#">Inicio</a></li>
                <li class="nav-item"><a class="nav-link" href="libros.php">Libros</a></li>
                <li class="nav-item"><a class="nav-link" href="clientes.php">Clientes</a></li>
                <li class="nav-item"><a class="nav-link" href="pedidos.php">Pedidos</a></li>
                <li class="nav-item"><a class="nav-link" href="estadisticas.php">Estadísticas</a></li>
            </ul>
        </div>
    </nav>

    <div class="text-center mt-4">
        <h2>Estadísticas de Ventas</h2>
    </div>

    <div class="chart-container">
        <h3>Ventas por Libro</h3>
        <canvas id="ventasPorLibro"></canvas>
    </div>
    <div class="chart-container">
        <h3>Ventas Mensuales</h3>
        <canvas id="ventasMensuales"></canvas>
    </div>
    <div class="chart-container">
        <h3>Ventas por Cliente</h3>
        <canvas id="ventasPorCliente"></canvas>
    </div>

    <script>
        function renderChart(ctx, labels, values, label) {
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: label,
                        data: values,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: { y: { beginAtZero: true } }
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            renderChart(document.getElementById('ventasPorLibro').getContext('2d'), <?php echo json_encode($ventasPorLibro['labels']); ?>, <?php echo json_encode($ventasPorLibro['values']); ?>, 'Ventas por Libro');
            renderChart(document.getElementById('ventasMensuales').getContext('2d'), <?php echo json_encode($ventasMensuales['labels']); ?>, <?php echo json_encode($ventasMensuales['values']); ?>, 'Ventas Mensuales');
            renderChart(document.getElementById('ventasPorCliente').getContext('2d'), <?php echo json_encode($ventasPorCliente['labels']); ?>, <?php echo json_encode($ventasPorCliente['values']); ?>, 'Ventas por Cliente');
        });
    </script>
</body>
</html>
