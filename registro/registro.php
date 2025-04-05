<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Conexión a la base de datos
$host = 'localhost';
$user = 'root';
$password = ''; // Ajusta si tienes contraseña
$dbname = 'rally_andaluz';

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Error de conexión: ' . $conn->connect_error]));
}

// Recibir datos en JSON
$data = json_decode(file_get_contents("php://input"), true);

// Verificar datos
if (isset($data['nombre'], $data['email'], $data['contraseña'])) {
    $nombre = $conn->real_escape_string($data['nombre']);
    $email = $conn->real_escape_string($data['email']);
    $contraseña = password_hash($data['contraseña'], PASSWORD_DEFAULT);

    // Verificar si ya existe el email
    $check = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'Este email ya está registrado.']);
    } else {
        // Insertar usuario
        $stmt = $conn->prepare("INSERT INTO usuarios (nombre, email, contraseña, rol) VALUES (?, ?, ?, 'participante')");
        $stmt->bind_param("sss", $nombre, $email, $contraseña);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Usuario registrado exitosamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al registrar el usuario']);
        }
        $stmt->close();
    }
    $check->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Faltan datos']);
}

$conn->close();
?>
