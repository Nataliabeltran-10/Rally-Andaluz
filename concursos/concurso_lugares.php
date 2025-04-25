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
$mensajeExito = "";

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo      = trim($_POST['titulo']);
    $descripcion = trim($_POST['descripcion']);

    // Verificar si ya existe una foto con el mismo título
    $sqlCheck = "SELECT * FROM fotos WHERE titulo_imagen = ? AND usuario_id = ?";
    $stmtCheck = $conn->prepare($sqlCheck);
    $stmtCheck->execute([$titulo, $_SESSION['usuario_id']]);
    $fotoExistente = $stmtCheck->fetch();

    if ($fotoExistente) {
        $error = "Ya existe una foto con este título.";
    } else {
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            // Leer la imagen como binario
            $imagenData = file_get_contents($_FILES['imagen']['tmp_name']);

            // Insertar en BD (ahora incluye el campo 'concurso')
            $sql = "INSERT INTO fotos (usuario_id, imagen, descripcion, titulo_imagen, concurso)
                    VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                $_SESSION['usuario_id'],
                $imagenData,
                $descripcion,
                $titulo,
                'Lugares'
            ]);

            $mensajeExito = "Tu participación ha sido enviada y está pendiente de revisión.";
        } else {
            $error = "Debes seleccionar una imagen.";
        }
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

    <?php if ($mensajeExito): ?>
      <div class="success-message">
        <p><?= htmlspecialchars($mensajeExito) ?></p>
        <form action="concurso_lugares.php" method="GET">
          <button type="submit">Aceptar</button>
        </form>
      </div>
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
    const fondo = document.body.getAttribute('data-fondo');
    if (fondo) {
      document.body.style.background = `url('${fondo}') no-repeat center center fixed`;
      document.body.style.backgroundSize = 'cover';
    }
  </script>
</body>
</html>
