<?php
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_cliente = $_POST['id_cliente'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $direccion = $_POST['direccion'];
    $email = $_POST['email'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $telefono = $_POST['telefono'];
    $preferencias_genero = $_POST['preferencias_genero'];
    $historial_compras = $_POST['historial_compras'];
    $lista_deseos = $_POST['lista_deseos'];
    $boletin = isset($_POST['boletin']) ? 1 : 0;

    $sql = "INSERT INTO Clientes (id_cliente, Nombre, Apellido, Direccion, Email, Fecha_de_nacimiento, Telefono, Preferencias_de_genero, Historial_de_compras, Lista_de_deseos, Boletin_de_noticias)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id_cliente, $nombre, $apellido, $direccion, $email, $fecha_nacimiento, $telefono, $preferencias_genero, $historial_compras, $lista_deseos, $boletin]);

    header("Location: clientes.php");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Cliente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <div class="container mt-5">
        <h2>Agregar Cliente</h2>
        <form method="POST">
            <div class="mb-3">
                <label for="id_cliente" class="form-label">ID Cliente</label>
                <input type="number" class="form-control" id="id_cliente" name="id_cliente" required>
            </div>
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
            <div class="mb-3">
                <label for="apellido" class="form-label">Apellido</label>
                <input type="text" class="form-control" id="apellido" name="apellido" required>
            </div>
            <div class="mb-3">
                <label for="direccion" class="form-label">Dirección</label>
                <input type="text" class="form-control" id="direccion" name="direccion" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento">
            </div>
            <div class="mb-3">
                <label for="telefono" class="form-label">Teléfono</label>
                <input type="text" class="form-control" id="telefono" name="telefono" required>
            </div>
            <div class="mb-3">
                <label for="preferencias_genero" class="form-label">Preferencias de Género</label>
                <input type="text" class="form-control" id="preferencias_genero" name="preferencias_genero">
            </div>
            <div class="mb-3">
                <label for="historial_compras" class="form-label">Historial de Compras</label>
                <textarea class="form-control" id="historial_compras" name="historial_compras"></textarea>
            </div>
            <div class="mb-3">
                <label for="lista_deseos" class="form-label">Lista de Deseos</label>
                <textarea class="form-control" id="lista_deseos" name="lista_deseos"></textarea>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="boletin" name="boletin">
                <label class="form-check-label" for="boletin">Suscribirse al boletín de noticias</label>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Agregar Cliente</button>
        </form>
    </div>
</body>
</html>
