<?php
require_once 'conexion.php';  // Incluir la conexión a la base de datos
require_once 'Pedidos.php';   // Incluir la clase Pedidos

// Obtener los datos del formulario
$clienteId = $_POST['cliente_id'];
$fecha = $_POST['fecha'];
$total = $_POST['total'];
$metodoPago = $_POST['metodo_pago'];
$direccionEnvio = $_POST['direccion_envio'];

// Crear una instancia de la clase Pedidos
$pedidos = new Pedidos($conn);

// Agregar el pedido
$pedidoId = $pedidos->agregarPedido($clienteId, $fecha, $total, $metodoPago, $direccionEnvio);

// Mostrar mensaje de éxito o error
if ($pedidoId) {
    echo "Pedido agregado con éxito. ID del pedido: $pedidoId";
} else {
    echo "Hubo un error al agregar el pedido.";
}
?>
