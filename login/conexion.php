<?php
$host = 'localhost';     // Dirección del servidor de la base de datos (por defecto es localhost)
$dbname = 'rally_andaluz'; // Nombre de la base de datos
$username = 'root';      // Nombre de usuario para la base de datos (usualmente 'root' en local)
$password = '';          // Contraseña para el usuario de la base de datos (generalmente vacío en local)

try {
    // Crear una instancia de PDO para conectarse a la base de datos
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Establecer el modo de error de PDO a excepción
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Si ocurre un error, mostrar un mensaje
    die("Error al conectar a la base de datos: " . $e->getMessage());
}
?>
