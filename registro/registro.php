<?php
header('Content-Type: application/json');

// Oculta errores que puedan romper el JSON
ini_set('display_errors', 0);
error_reporting(0);

// Configuración de la base de datos
$host = 'localhost';
$user = 'root';
$password = ''; // Ajusta si tu MySQL tiene clave
$dbname = 'rally_andaluz';

$conn = new mysqli($host, $user, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Error de conexión a la base de datos.']);
    exit;
}

// Leer datos del body como JSON
$datos = json_decode(file_get_contents("php://input"), true);

// Verificar campos requeridos
if (!isset($datos['nombre'], $datos['email'], $datos['contraseña'])) {
    echo json_encode(['success' => false, 'message' => 'Faltan datos.']);
    exit;
}

// Sanitizar entradas
$nombre = $conn->real_escape_string($datos['nombre']);
$email = $conn->real_escape_string($datos['email']);
$contraseña = password_hash($datos['contraseña'], PASSWORD_DEFAULT);

// Verificar si ya existe el email
$checkQuery = "SELECT id FROM usuarios WHERE email = '$email'";
$resultado = $conn->query($checkQuery);

if ($resultado && $resultado->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Este email ya está registrado.']);
    $conn->close();
    exit;
}

// Insertar nuevo usuario
$insertQuery = "INSERT INTO usuarios (nombre, email, contraseña, rol) VALUES ('$nombre', '$email', '$contraseña', 'participante')";

if ($conn->query($insertQuery) === TRUE) {
    echo json_encode(['success' => true, 'message' => 'Usuario registrado con éxito.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al registrar.']);
}

$conn->close();
