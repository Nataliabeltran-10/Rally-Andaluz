<?php
require_once("conexion.php");
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

// Obtener los datos actuales del usuario
$sql = "SELECT * FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$_SESSION['usuario_id']]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $contraseña = $_POST['contraseña'];

    if ($contraseña !== '') {
        $contraseña = password_hash($contraseña, PASSWORD_BCRYPT);
        $sql = "UPDATE usuarios SET nombre = ?, email = ?, contraseña = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$nombre, $email, $contraseña, $_SESSION['usuario_id']]);
    } else {
        $sql = "UPDATE usuarios SET nombre = ?, email = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$nombre, $email, $_SESSION['usuario_id']]);
    }

    $_SESSION['usuario_nombre'] = $nombre;
    $_SESSION['usuario_email'] = $email;

    header("Location: index.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Perfil</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

  <h2>Editar Perfil</h2>
  <form method="POST">
    <label for="nombre">Nombre:</label>
    <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($usuario['nombre']) ?>" required>

    <label for="email">Correo electrónico:</label>
    <input type="email" id="email" name="email" value="<?= htmlspecialchars($usuario['email']) ?>" required>

    <label for="contraseña">Nueva contraseña (opcional):</label>
    <input type="password" id="contraseña" name="contraseña">

    <input type="submit" value="Guardar cambios">
  </form>

</body>
</html>
