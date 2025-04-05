<?php
// Mostrar todos los errores para depuración
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Configuración de la base de datos
$host = 'localhost';  // Asegúrate de que este sea el host correcto (puede ser localhost o una IP o dominio si es remoto)
$user = 'root';       // Cambia esto por tu usuario de base de datos
$password = '';       // Cambia esto si tienes una contraseña configurada
$dbname = 'rally_andaluz';  // El nombre de tu base de datos

// Intentar conectar a la base de datos
$conn = new mysqli($host, $user, $password, $dbname);

// Verifica si hubo un error en la conexión
if ($conn->connect_error) {
    // Si hay error de conexión, se muestra en formato JSON
    echo json_encode(['success' => false, 'message' => 'Error de conexión: ' . $conn->connect_error]);
    exit; // Detener ejecución si hay error de conexión
}

// Obtener los datos JSON del formulario
$datos = json_decode(file_get_contents("php://input"), true);

// Verifica que los datos necesarios están presentes
if (!isset($datos['nombre'], $datos['email'], $datos['contraseña'])) {
    // Si faltan datos, devuelve un error en JSON
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
    // Si el email ya está registrado, responder con error
    echo json_encode(['success' => false, 'message' => 'Este email ya está registrado.']);
    $conn->close();
    exit;
}

// Insertar el nuevo usuario
$insertQuery = "INSERT INTO usuarios (nombre, email, contraseña, rol) VALUES ('$nombre', '$email', '$contraseña', 'participante')";

if ($conn->query($insertQuery) === TRUE) {
    // Si la inserción fue exitosa, responder con éxito en formato JSON
    echo json_encode(['success' => true, 'message' => 'Usuario registrado correctamente.']);
} else {
    // Si hay un error al insertar, responder con un mensaje de error
    echo json_encode(['success' => false, 'message' => 'Error al registrar el usuario: ' . $conn->error]);
}

// Cerrar la conexión
$conn->close();
?>
