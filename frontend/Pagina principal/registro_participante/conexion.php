<?php
// Configuración de la conexión a la base de datos
$host = "localhost";  // Cambia si tu base de datos está en otro servidor
$dbname = "rally_andaluz";  // Reemplaza con el nombre de tu base de datos
$username = "root";  // Usuario de la base de datos
$password = "";  // Contraseña (si tienes una)

// Intenta la conexión con PDO
try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    // Configurar para lanzar excepciones en caso de error
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>
