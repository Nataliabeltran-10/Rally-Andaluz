<?php
// Incluir el archivo de conexión
require_once("conexion.php");

// Verificar si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $contraseña = $_POST['contraseña'];

    // Validar si el nombre ya existe en la base de datos
    try {
        // Consultamos si ya existe un usuario con ese nombre
        $sql = "SELECT * FROM usuarios WHERE nombre = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$nombre]);
        $usuarioExistente = $stmt->fetch();

        if ($usuarioExistente) {
            // Si existe, mostramos un mensaje de error
            echo "Error: Ya existe un usuario con ese nombre. Por favor, elige otro.";
        } else {
            // Si no existe, procedemos a insertar el nuevo usuario
            $sqlInsert = "INSERT INTO usuarios (nombre, email, contraseña, rol) VALUES (?, ?, ?, ?)";
            $stmtInsert = $conn->prepare($sqlInsert);
            // Usamos password_hash para almacenar la contraseña de manera segura
            $stmtInsert->execute([$nombre, $email, password_hash($contraseña, PASSWORD_DEFAULT), 'participante']);
            
            // Redirigir a la página de subida de fotos
            header("Location: subir_fotos.php");  // Asegúrate de que esta página exista
            exit;  // Asegurar que el script se detiene después de la redirección
        }
    } catch (PDOException $e) {
        echo "Error al consultar la base de datos: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Registro de Participante</title>
</head>
<body>
    <h2>Formulario de Registro de Participante</h2>
    <form action="registro.php" method="POST">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="contraseña">Contraseña:</label>
        <input type="password" id="contraseña" name="contraseña" required><br><br>

        <input type="submit" value="Registrar">
    </form>
</body>
</html>
