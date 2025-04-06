<?php
// Configuración de la conexión a la base de datos
$host = "PMYSQL182.dns-servicio.com:3306";  // Cambia si tu base de datos está en otro servidor
$dbname = "10868095_rally_andaluz";  // Reemplaza con el nombre de tu base de datos
$username = "natalia";  // Usuario de la base de datos
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
