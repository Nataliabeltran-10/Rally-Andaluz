<?php
require_once("conexion.php");
session_start();

// Solo administradores
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'administrador') {
    header("Location: login.php");
    exit;
}

$sql = "SELECT f.id, f.titulo_imagen, f.descripcion, f.concurso, u.nombre, f.imagen
        FROM fotos f
        JOIN usuarios u ON f.usuario_id = u.id
        ORDER BY f.id DESC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$fotos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Galería de Participaciones</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

  <h1>Galería de Participaciones</h1>

  <div class="galeria-admin">
    <?php foreach ($fotos as $foto): ?>
      <div class="foto-card">
        <img src="data:image/jpeg;base64,<?= base64_encode($foto['imagen']) ?>" alt="<?= htmlspecialchars($foto['titulo_imagen']) ?>">
        <h3><?= htmlspecialchars($foto['titulo_imagen']) ?></h3>
        <p><strong>Participante:</strong> <?= htmlspecialchars($foto['nombre']) ?></p>
        <p><strong>Concurso:</strong> <?= htmlspecialchars($foto['concurso']) ?></p>
        <p><strong>Descripción:</strong> <?= nl2br(htmlspecialchars($foto['descripcion'])) ?></p>
      </div>
    <?php endforeach; ?>
  </div>

</body>
</html>
