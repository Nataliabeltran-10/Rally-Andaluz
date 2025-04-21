<?php
require_once("conexion.php");
session_start();

// Solo participantes logueados pueden subir
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'participante') {
    header("Location: login.php");
    exit;
}

$rutaFondo = '../fotos/fondo.jpg';
$error = "";

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo      = trim($_POST['titulo']);
    $descripcion = trim($_POST['descripcion']);

    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        // Leer la imagen como binario
        $imagenData = file_get_contents($_FILES['imagen']['tmp_name']);

        // Insertar en BD (campo imagen debe ser BLOB o LONGBLOB)
        $sql = "INSERT INTO fotos (usuario_id, imagen, descripcion, titulo_imagen)
                VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            $_SESSION['usuario_id'],
            $imagenData,
            $descripcion,
            $titulo
        ]);

        // Alerta y recarga de la misma página
        echo "<script>
                 alert('Tu participación tiene que ser aceptada por el administrador');
                 window.location='concurso_lugares.php';
              </script>";
        exit;
    } else {
        $error = "Debes seleccionar una imagen.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Concurso Lugares Bonitos</title>
  <link rel="stylesheet" href="style.css">
</head>
<body data-fondo="<?= $rutaFondo ?>">

  <div class="form-container">
    <h2>Sube tu foto de “Lugares Bonitos”</h2>

    <?php if ($error): ?>
      <div class="error-message"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form action="concurso_lugares.php" method="POST" enctype="multipart/form-data">
      <label for="titulo">Título de la imagen:</label>
      <input type="text" id="titulo" name="titulo" required>

      <label for="descripcion">Descripción:</label>
      <textarea id="descripcion" name="descripcion" required></textarea>

      <label for="imagen">Selecciona la imagen:</label>
      <input type="file" id="imagen" name="imagen" accept="image/*" required>

      <input type="submit" value="Subir">
    </form>
  </div>

  <script>
    // Fondo dinámico
    const fondo = document.body.getAttribute('data-fondo');
    if (fondo) {
      document.body.style.background = `url('${fondo}') no-repeat center center fixed`;
      document.body.style.backgroundSize = 'cover';
    }
  </script>
</body>
</html>
