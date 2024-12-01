
<?php
require_once 'conexion.php'; // Incluye la conexión a la base de datos

class BasePage {
    // Mostrar el encabezado (header) con el menú de navegación
    public function mostrarHeader() {
        echo '
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
            <title>Library Shop</title>
        </head>
        <body>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <a class="navbar-brand" href="#">Library Shop</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item active">
                        <a class="nav-link" href="index.php">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="libros.php">Libros</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="clientes.php">Clientes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="pedidos.php">Pedidos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="estadisticas.php">Estadísticas</a>
                    </li>
                </ul>
            </div>
        </nav>';
    }

    // Mostrar el pie de página (footer)
    public function mostrarFooter() {
        echo '
        <footer class="footer mt-auto py-3 bg-dark text-white">
            <div class="container">
                <span>&copy; 2024 Library Shop. Todos los derechos reservados.</span>
            </div>
        </footer>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz4fnFO9gybB4H8KBGQxtBdo6fz7ni9siN3jEx6+9v9s4Xy3bXyKfdf6k0" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-cu1yKxqFcUBRAjWA3r6A7zFzB92FX1uSc/qmf8hhkWcQJbbTce9lGvUihLqPyI/e" crossorigin="anonymous"></script>
        </body>
        </html>';
    }

    // Función para obtener los 5 libros más vendidos
    public function obtenerLibrosMasVendidos() {
        global $conn; // Usar la conexión PDO

        try {
            $sql = "SELECT L.Titulo, SUM(DP.Cantidad) AS Total_Vendido, L.Imagen 
                    FROM Libros L
                    JOIN Detalles_pedido DP ON L.ISBN = DP.libro_isbn
                    GROUP BY L.Titulo, L.Imagen
                    ORDER BY Total_Vendido DESC
                    LIMIT 5";
            
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC); // Retorna todos los resultados
        } catch (PDOException $e) {
            // Manejo de errores
            echo "Error al obtener los libros más vendidos: " . $e->getMessage();
            return [];
        }
    }
}
?>