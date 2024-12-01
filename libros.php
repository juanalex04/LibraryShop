<?php
$servername = "sql203.infinityfree.com";
$username = "if0_37699759";
$password = "qDb9E9PhwgV";
$dbname = "if0_37699759_db_libraryshop";  // Verifica que este sea el nombre correcto

// Intentamos establecer la conexión
try {
    // Conexión utilizando PDO
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    
    // Establecer el modo de error a excepción para facilitar la depuración
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //echo "Conexión exitosa"; // Comentado para evitar ver este mensaje en producción
} catch (PDOException $e) {
    // Si ocurre un error, mostrar el mensaje
    die("Error de conexión: " . $e->getMessage());
}
?>