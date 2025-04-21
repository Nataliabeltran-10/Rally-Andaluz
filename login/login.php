<?php
require_once("conexion.php");

$rutaFondo = '../fotos/fondo.jpg';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $contraseña = $_POST['contraseña'];

    // Verificamos que los campos no estén vacíos
    if (!empty($nombre) && !empty($contraseña)) {
        try {
            // Consultar si el usuario existe en la base de datos
            $sql = "SELECT * FROM usuarios WHERE nombre = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$nombre]);
            $usuario = $stmt->fetch();

            // Si el usuario existe y la contraseña es correcta
            if ($usuario && password_verify($contraseña, $usuario['contraseña'])) {
                header("Location: GaleriaFotos.php"); // Si el login es correcto, redirigir a la galería
                exit;
            } else {
                // Si el login falla, redirigir con el parámetro error=1
                header("Location: login.php?error=1");
                exit;
            }
        } catch (PDOException $e) {
            echo "Error en la base de datos: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
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

    <!-- Modal de error (solo se muestra cuando el login falla) -->
    <div id="errorModal" class="modal">
        <div class="modal-content">
            <p>El usuario no existe o la contraseña es incorrecta</p>
            <button class="close-btn" onclick="cerrarModal()">Cancelar</button>
        </div>
    </div>

    <script>
        function mostrarModal() {
            document.getElementById("errorModal").style.display = "flex";
        }

        function cerrarModal() {
            document.getElementById("errorModal").style.display = "none";
        }

        window.onload = function() {
            const params = new URLSearchParams(window.location.search);
            
            // Mostrar el modal solo si el parámetro 'error' está presente en la URL
            if (params.has('error') && params.get('error') === '1') {
                mostrarModal();
                
                // Limpiar la URL para evitar que el parámetro de error se mantenga
                history.replaceState({}, document.title, window.location.pathname);
            }
        };

        // Aplica dinámicamente el fondo desde el atributo data-fondo
        const fondo = document.body.getAttribute('data-fondo');
        if (fondo) {
            document.body.style.background = `url('${fondo}') no-repeat center center fixed`;
            document.body.style.backgroundSize = 'cover';
        }
    </script>

</body>
</html>
