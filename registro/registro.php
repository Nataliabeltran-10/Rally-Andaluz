<?php
// Muestra todos los errores (solo para depuración)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Configuración de la base de datos en Hostalia
$host = 'localhost';  // Esto generalmente es 'localhost' en Hostalia, pero si tienes un servidor de base de datos remoto, pon la IP o el dominio aquí
$user = 'root';       // Cambia esto con el nombre de usuario de tu base de datos en Hostalia
$password = '';       // Cambia esto si tienes una contraseña configurada en tu base de datos
$dbname = 'rally_andaluz';  // Nombre de la base de datos

// Intentar conectar a la base de datos
$conn = new mysqli($host, $user, $password, $dbname);

// Verifica si hubo un error en la conexión
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Error de conexión: ' . $conn->connect_error]);
    exit; // Detener ejecución si hay error de conexión
}

// Obtener los datos JSON del formulario
$datos = json_decode(file_get_contents("php://input"), true);

// Verifica que los datos necesarios están presentes
if (!isset($datos['nombre'], $datos['email'], $datos['contraseña'])) {
    echo json_encode(['success' => false, 'message' => 'Faltan datos en la solicitud.']);
    exit;
}

// Sanitizar los datos de entrada
$nombre = $conn->real_escape_string($datos['nombre']);
$email = $conn->real_escape_string($datos['email']);
$contraseña = password_hash($datos['contraseña'], PASSWORD_DEFAULT); // Hashear la contraseña para mayor seguridad

// Comprobar si el email ya está registrado
$checkEmailQuery = "SELECT id FROM usuarios WHERE email = '$email'";
$result = $conn->query($checkEmailQuery);

if ($result->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Este email ya está registrado.']);
    $conn->close();
    exit;
}

// Insertar el nuevo usuario
$insertQuery = "INSERT INTO usuarios (nombre, email, contraseña, rol) VALUES ('$nombre', '$email', '$contraseña', 'participante')";

if ($conn->query($insertQuery) === TRUE) {
    echo json_encode(['success' => true, 'message' => 'Usuario registrado correctamente.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al registrar el usuario: ' . $conn->error]);
}

$conn->close(); // Cerrar la conexión
?>
