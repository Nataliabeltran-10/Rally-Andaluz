<?php
session_start();
if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] !== 'participante') {
    header("Location: login.php");
    exit;
}
$rutaFondo = '../fotos/fondo.jpg';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Página Participante</title>
  <link rel="stylesheet" href="style.css">
</head>
<body data-fondo="<?= $rutaFondo ?>">

  <div class="container">
    <h1>¿En que concurso quieres participar?</h1>
    <div class="cuadrados-container">
      <a href="../concursos/concurso_lugares.php" class="cuadro">
        <img src="../fotos/concurso_1.jpg" alt="Lugares Bonitos">
        <span class="title">Lugares Bonitos</span>
      </a>
      <a href="concurso_tradicciones.php" class="cuadro">
        <img src="../fotos/concurso_2.jpg" alt="Tradiciones">
        <span class="title">Tradiciones</span>
      </a>
    </div>
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
