<?php
// Incluir conexión a la base de datos
require_once("conexion.php");

// Ruta del fondo
$rutaFondo = '../fotos/fondo.jpg';

session_start(); // Si más adelante quieres usar sesiones

// Verifica si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $contraseña = $_POST['contraseña'];

    try {
        $sql = "SELECT * FROM usuarios WHERE nombre = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$nombre]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($contraseña, $usuario['contraseña'])) {
            // Usuario válido, redirigir a GaleriaFotos.php
            header("Location: GaleriaFotos.php");
            exit;
        } else {
            $error = "El usuario no existe o la contraseña es incorrecta.";
        }
    } catch (PDOException $e) {
        $error = "Error al consultar la base de datos: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
    <link rel="stylesheet" href="style.css">
</head>
<body data-fondo="<?= $rutaFondo ?>">

    <h2>Iniciar Sesión</h2>
    <form action="login.php" method="POST">
        <label for="nombre">Nombre de usuario:</label>
        <input type="text" id="nombre" name="nombre" required>

        <label for="contraseña">Contraseña:</label>
        <input type="password" id="contraseña" name="contraseña" required>

        <input type="submit" value="Iniciar Sesión">
    </form>

    <?php if (isset($error)): ?>
        <script>
            alert("<?= $error ?>");
        </script>
    <?php endif; ?>

    <script>
        // Aplica fondo dinámicamente
        const fondo = document.body.getAttribute('data-fondo');
        if (fondo) {
            document.body.style.background = `url('${fondo}') no-repeat center center fixed`;
            document.body.style.backgroundSize = 'cover';
        }
    </script>
</body>
</html>
