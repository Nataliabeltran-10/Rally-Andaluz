<?php
// Mostrar todos los errores para depuración
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Configuración de la base de datos
$host = 'localhost';
$user = 'root';
$password = ''; // Cambia la contraseña si la tienes configurada
$dbname = 'rally_andaluz'; // Nombre de tu base de datos

// Crear la conexión a la base de datos
$conn = new mysqli($host, $user, $password, $dbname);

// Comprobar la conexión
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Error de conexión: ' . $conn->connect_error]));
}

// Obtener los datos del formulario en formato JSON
$data = json_decode(file_get_contents("php://input"), true);

// Verificar que se recibieron los datos correctamente
if (isset($data['nombre'], $data['email'], $data['contraseña'])) {
    // Sanitizar los datos
    $nombre = $conn->real_escape_string($data['nombre']);
    $email = $conn->real_escape_string($data['email']);
    $contraseña = password_hash($data['contraseña'], PASSWORD_DEFAULT); // Hashear la contraseña

    // Consultar si el email ya está registrado
    $checkEmailQuery = "SELECT id FROM usuarios WHERE email = '$email'";
    $result = $conn->query($checkEmailQuery);

    if ($result->num_rows > 0) {
        // El email ya está registrado
        echo json_encode(['success' => false, 'message' => 'Este email ya está registrado.']);
    } else {
        // Insertar el nuevo usuario en la base de datos
        $query = "INSERT INTO usuarios (nombre, email, contraseña, rol) VALUES ('$nombre', '$email', '$contraseña', 'participante')";

        if ($conn->query($query) === TRUE) {
            // Responder con éxito
            echo json_encode(['success' => true, 'message' => 'Usuario registrado exitosamente']);
        } else {
            // Si hay un error con la inserción
            echo json_encode(['success' => false, 'message' => 'Error al registrar el usuario']);
        }
    }
} else {
    // Si faltan datos en la solicitud
    echo json_encode(['success' => false, 'message' => 'Faltan datos']);
}

// Cerrar la conexión a la base de datos
$conn->close();
?>
