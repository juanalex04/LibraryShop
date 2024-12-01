<?php
require_once 'Conexion.php';
require_once 'Pedido.php';

if (isset($_GET['id'])) {
    $id_pedido = $_GET['id'];

    $conexion = new Conexion();
    $db = $conexion->getConn();
    $pedido = new Pedido($db);

    if ($pedido->eliminarPedido($id_pedido)) {
        header("Location: pedidos.php");
    } else {
        echo "Error al eliminar el pedido.";
    }
}
?>
