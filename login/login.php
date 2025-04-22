<?php
require_once("conexion.php");
session_start();

$rutaFondo = '../fotos/fondo.jpg';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre     = trim($_POST['nombre']);
    $contraseña = $_POST['contraseña'];

    if ($nombre !== '' && $contraseña !== '') {
        try {
            $sql  = "SELECT * FROM usuarios WHERE nombre = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$nombre]);
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($usuario && password_verify($contraseña, $usuario['contraseña'])) {
                // Guardamos datos en sesión
                $_SESSION['usuario_id']  = $usuario['id'];
                $_SESSION['usuario_rol'] = $usuario['rol'];

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
                header("Location: login.php?error=1");
                exit;
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
<body data-fondo="<?= $rutaFondo ?>">

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
      if (params.get('error') === '1') {
        mostrarModal();
        history.replaceState({}, document.title, window.location.pathname);
      }
    };
    // Fondo dinámico
    const fondo = document.body.getAttribute('data-fondo');
    if (fondo) {
      document.body.style.background = `url('${fondo}') no-repeat center center fixed`;
      document.body.style.backgroundSize = 'cover';
    }
  </script>
</body>
</html>
