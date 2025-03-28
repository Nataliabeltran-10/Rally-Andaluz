<?php
session_start();
require_once("conexion.php");

// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $contraseña = $_POST['contraseña'];

    try {
        // Consulta para obtener el usuario por email
        $sql = "SELECT * FROM usuarios WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$email]);
        $usuario = $stmt->fetch();

        if ($usuario && password_verify($contraseña, $usuario['contraseña'])) {
            // Iniciar la sesión y guardar los datos del usuario
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['nombre'] = $usuario['nombre'];
            $_SESSION['rol'] = $usuario['rol']; // Guardamos el rol para poder hacer las restricciones

            // Redirigir a la página principal
            header("Location: index.php");  // Cambia esto por la ruta correcta a la página principal
            exit;
        } else {
            // Si no existe el usuario o la contraseña es incorrecta
            echo "Correo o contraseña incorrectos.";
        }
    } catch (PDOException $e) {
        echo "Error en la base de datos: " . $e->getMessage();
    }
}
?>
