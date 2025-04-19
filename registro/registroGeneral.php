<?php
// Incluir el archivo de conexión
require_once("conexion.php");

// Verificar si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $contraseña = $_POST['contraseña'];
    $rol = $_POST['rol'];

    try {
        $sql = "SELECT * FROM usuarios WHERE nombre = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$nombre]);
        $usuarioExistente = $stmt->fetch();

        if ($usuarioExistente) {
            echo "Error: Ya existe un usuario con ese nombre. Por favor, elige otro.";
        } else {
            $sqlInsert = "INSERT INTO usuarios (nombre, email, contraseña, rol) VALUES (?, ?, ?, ?)";
            $stmtInsert = $conn->prepare($sqlInsert);
            $stmtInsert->execute([
                $nombre,
                $email,
                password_hash($contraseña, PASSWORD_DEFAULT),
                $rol
            ]);

            if ($rol == 'participante') {
                header("Location: subir_fotos.php");
            } else {
                header("Location: index.php");
            }
            exit;
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
    <title>Formulario de Registro</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            background: url('fotos/fondo.jpg') no-repeat center center fixed;
            background-size: cover;
            position: relative;
            height: 100vh;
        }

        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            width: 100%;
            background-color: rgba(100, 100, 100, 0.5);
            z-index: 0;
        }
    </style>
</head>
<body>
    <h2>Formulario de Registro</h2>
    <form action="registroGeneral.php" method="POST">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="contraseña">Contraseña:</label>
        <input type="password" id="contraseña" name="contraseña" required>

        <label for="rol">Tipo de usuario:</label>
        <select id="rol" name="rol" required>
            <option value="participante">Participante</option>
            <option value="usuario_normal">Usuario Normal</option>
        </select>

        <input type="submit" value="Registrar">
    </form>
</body>
</html>
