<?php
require_once 'BasePage.php'; // Clase para estructura de la página
require_once 'conexion.php'; // Conexión a la base de datos

$page = new BasePage();
$page->mostrarHeader();

// Obtener los pedidos de la base de datos
$sqlPedidos = "SELECT p.id AS pedido_id, c.Nombre AS cliente_nombre
               FROM Pedidos p
               INNER JOIN Clientes c ON p.cliente_id = c.id";
$stmtPedidos = $conn->prepare($sqlPedidos);
$stmtPedidos->execute();
$pedidos = $stmtPedidos->fetchAll(PDO::FETCH_ASSOC);
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-4">
    <h2>Gestión de Pedidos</h2>
    <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#agregarPedidoModal">Agregar Pedido</button>
    
    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>ID Pedido</th>
                <th>Cliente</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($pedidos): ?>
                <?php foreach ($pedidos as $pedido): ?>
                    <tr>
                        <td><?= $pedido['pedido_id'] ?></td>
                        <td><?= $pedido['cliente_nombre'] ?></td>
                        <td>
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#detallesPedidoModal<?= $pedido['pedido_id'] ?>">Ver Detalles</button>
                            <form method="POST" action="procesarPedido.php" class="d-inline">
                                <input type="hidden" name="id_pedido" value="<?= $pedido['pedido_id'] ?>">
                                <button type="submit" name="accion" value="eliminar" class="btn btn-danger btn-sm">Eliminar</button>
                            </form>
                        </td>
                    </tr>

                    <!-- Modal para detalles del pedido -->
                    <div class="modal fade" id="detallesPedidoModal<?= $pedido['pedido_id'] ?>" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Detalles del Pedido #<?= $pedido['pedido_id'] ?></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <?php
                                    $sqlDetalles = "SELECT dp.Cantidad, dp.Precio_unitario, l.Titulo
                                                    FROM Detalles_pedido dp
                                                    INNER JOIN Libros l ON dp.libro_isbn = l.ISBN
                                                    WHERE dp.id_pedido = :pedido_id";
                                    $stmtDetalles = $conn->prepare($sqlDetalles);
                                    $stmtDetalles->bindParam(':pedido_id', $pedido['pedido_id'], PDO::PARAM_INT);
                                    $stmtDetalles->execute();
                                    $detalles = $stmtDetalles->fetchAll(PDO::FETCH_ASSOC);

                                    if ($detalles): ?>
                                        <ul>
                                            <?php foreach ($detalles as $detalle): ?>
                                                <li>
                                                    <strong>Libro:</strong> <?= $detalle['Titulo'] ?><br>
                                                    <strong>Cantidad:</strong> <?= $detalle['Cantidad'] ?><br>
                                                    <strong>Precio Unitario:</strong> $<?= $detalle['Precio_unitario'] ?><br>
                                                </li>
                                                <hr>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php else: ?>
                                        <p>No hay detalles disponibles para este pedido.</p>
                                    <?php endif; ?>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3" class="text-center">No hay pedidos registrados.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Modal para agregar pedido -->
<div class="modal fade" id="agregarPedidoModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Agregar Pedido</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="procesarPedido.php">
                    <div class="mb-3">
                        <label for="cliente_id" class="form-label">Cliente</label>
                        <select class="form-control" id="cliente_id" name="cliente_id" required>
                            <?php
                            $sqlClientes = "SELECT id, Nombre FROM Clientes";
                            $stmtClientes = $conn->prepare($sqlClientes);
                            $stmtClientes->execute();
                            $clientes = $stmtClientes->fetchAll(PDO::FETCH_ASSOC);
                            foreach ($clientes as $cliente) {
                                echo "<option value='{$cliente['id']}'>{$cliente['Nombre']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="direccion_envio" class="form-label">Dirección de Envío</label>
                        <input type="text" class="form-control" id="direccion_envio" name="direccion_envio" required>
                    </div>
                    <button type="submit" name="accion" value="agregar" class="btn btn-primary">Agregar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<?php $page->mostrarFooter(); ?>
