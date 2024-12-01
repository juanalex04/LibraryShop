<?php
require_once 'conexion.php';

if (!$conn) {
    echo json_encode(['error' => 'Error de conexión a la base de datos.']);
    exit;
}

if (isset($_GET['tipo'])) {
    $tipo = $_GET['tipo'];

    $labels = [];
    $values = [];
    $colors = [];
    $borderColors = [];

    switch ($tipo) {
        case 'ventasPorLibro':
            $query = "SELECT libro_isbn AS libro, SUM(Cantidad) AS total FROM Detalles_pedido GROUP BY libro_isbn";
            break;
        case 'ventasMensuales':
            $query = "SELECT DATE_FORMAT(Pedidos.Fecha, '%Y-%m') AS mes, SUM(Detalles_pedido.Cantidad) AS total FROM Detalles_pedido JOIN Pedidos ON Detalles_pedido.id_pedido = Pedidos.id GROUP BY mes";
            break;
        case 'ventasPorCliente':
            $query = "SELECT Clientes.Nombre AS cliente, SUM(Detalles_pedido.Cantidad) AS total FROM Detalles_pedido JOIN Pedidos ON Detalles_pedido.id_pedido = Pedidos.id JOIN Clientes ON Pedidos.cliente_id = Clientes.id GROUP BY cliente";
            break;
        default:
            echo json_encode(['error' => 'Tipo no válido']);
            exit;
    }

    // Añadir mensaje de depuración
    error_log("Ejecutando consulta: $query");

    $result = mysqli_query($conn, $query);
    if (!$result) {
        error_log("Error en la consulta: " . mysqli_error($conn));
        echo json_encode(['error' => 'Error en la consulta: ' . mysqli_error($conn)]);
        exit;
    }

    // Verificar si se obtuvieron resultados
    if (mysqli_num_rows($result) == 0) {
        error_log("No se encontraron datos para la consulta: $query");
        echo json_encode(['error' => 'No se encontraron datos.']);
        exit;
    }

    while ($row = mysqli_fetch_assoc($result)) {
        $labels[] = $row['libro'] ?? $row['mes'] ?? $row['cliente'];
        $values[] = $row['total'];
        $colors[] = 'rgba(75, 192, 192, 0.2)';
        $borderColors[] = 'rgba(75, 192, 192, 1)';
    }

    echo json_encode([
        'labels' => $labels,
        'values' => $values,
        'colors' => $colors,
        'borderColors' => $borderColors,
        'label' => 'Datos de Gráfica'
    ]);
} else {
    echo json_encode(['error' => 'Tipo de gráfico no especificado']);
}
?>
