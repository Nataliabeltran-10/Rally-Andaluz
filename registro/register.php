<?php
// Configuración de conexión a la base de datos
$servername = "localhost"; // Cambia esto según la configuración de tu servidor
$username = "root"; // Nombre de usuario de tu base de datos
$password = ""; // Contraseña de tu base de datos
$dbname = "10868095_rally_andaluz"; // Nombre de tu base de datos

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener los datos del formulario
$nombre = $_POST['nombre'];
$email = $_POST['email'];
$contraseña = password_hash($_POST['contraseña'], PASSWORD_DEFAULT); // Encriptar la contraseña

// Insertar los datos en la base de datos
$sql = "INSERT INTO usuarios (nombre, email, contraseña, rol) VALUES ('$nombre', '$email', '$contraseña', 'participante')";

if ($conn->query($sql) === TRUE) {
    echo "Registro exitoso. Puedes iniciar sesión ahora.";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Cerrar la conexión
$conn->close();
?>
