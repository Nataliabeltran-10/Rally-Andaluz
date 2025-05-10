<?php
require_once("conexion.php");
session_start();

// Ruta de fondo para el login
$rutaFondo = '../fotos/fondo.jpg';
$mostrarError = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre     = trim($_POST['nombre']);
    $contraseña = $_POST['contraseña'];

    if ($nombre !== '' && $contraseña !== '') {
        try {
            // Verificar si el usuario existe en la base de datos
            $sql  = "SELECT * FROM usuarios WHERE nombre = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$nombre]);
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            // Si el usuario existe y la contraseña es correcta
            if ($usuario && password_verify($contraseña, $usuario['contraseña'])) {
                // Guardamos datos en la sesión
                $_SESSION['usuario_id']     = $usuario['id'];
                $_SESSION['usuario_rol']    = $usuario['rol'];
                $_SESSION['usuario_nombre'] = $usuario['nombre'];
                $_SESSION['usuario_email']  = $usuario['email'];

                // Redirigimos según rol
                switch ($usuario['rol']) {
                    case 'participante':
                        header("Location: ../participante/pagina_participante.php");
                        break;
                    case 'usuario_normal':
                        header("Location: ../usuarios/pagina_usuario_normal.php");
                        break;
                    case 'administrador':
                        header("Location: ../administrador/pagina_admin.php");
                        break;
                }
                exit;
            } else {
                $mostrarError = true;
            }
        } catch (PDOException $e) {
            die("Error en la base de datos: " . $e->getMessage());
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
<body data-fondo="<?= htmlspecialchars($rutaFondo) ?>">

  <!-- Eliminar el header y mostrar solo el contenido principal -->

  <!-- Formulario de Login -->
  <h2>Iniciar Sesión</h2>
  <form action="login.php" method="POST">
    <label for="nombre">Nombre de usuario:</label>
    <input type="text" id="nombre" name="nombre" required>

    <label for="contraseña">Contraseña:</label>
    <input type="password" id="contraseña" name="contraseña" required>

    <input type="submit" value="Iniciar Sesión">

    <div class="register-link">
      <a href="../registro/registroGeneral.php">¿No tienes cuenta? Regístrate</a>
    </div>
  </form>

  <!-- Modal de error -->
  <div id="errorModal" class="modal" style="<?= $mostrarError ? 'display: flex;' : 'display: none;' ?>">
    <div class="modal-content">
      <p>El usuario no existe o la contraseña es incorrecta</p>
      <button class="close-btn" onclick="cerrarModal()">Cancelar</button>
    </div>
  </div>

  <script>
    function cerrarModal() {
      document.getElementById("errorModal").style.display = "none";
    }

    // Fondo dinámico
    const fondo = document.body.getAttribute('data-fondo');
    if (fondo) {
      document.body.style.background = `url('${fondo}') no-repeat center center fixed`;
      document.body.style.backgroundSize = 'cover';
    }
  </script>

</body>
</html>
