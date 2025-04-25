<?php
require_once("conexion.php");
session_start();

// Solo administradores
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'administrador') {
    header("Location: ../login/login.php");
    exit;
}

// Cambiar estado si se pulsa Admitir o Rechazar
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fotoId = $_POST['foto_id'];
    $nuevoEstado = $_POST['accion'] === 'admitir' ? 'admitida' : 'rechazada';

    $sqlUpdate = "UPDATE fotos SET estado = ? WHERE id = ?";
    $stmtUpdate = $conn->prepare($sqlUpdate);
    $stmtUpdate->execute([$nuevoEstado, $fotoId]);
}

// Obtener fotos pendientes
$sql = "SELECT f.id, f.titulo_imagen, f.descripcion, f.concurso, u.nombre, f.imagen
        FROM fotos f
        JOIN usuarios u ON f.usuario_id = u.id
        WHERE f.estado = 'pendiente'
        ORDER BY f.id DESC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$fotos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Fotos Pendientes</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <h1>Panel de Fotos Pendientes</h1>

    <div class="galeria-admin">
        <?php if (count($fotos) === 0): ?>
            <p style="color: white;">No hay fotos pendientes.</p>
        <?php else: ?>
            <?php foreach ($fotos as $foto): ?>
                <div class="foto-card">
                    <img src="data:image/jpeg;base64,<?= base64_encode($foto['imagen']) ?>" alt="<?= htmlspecialchars($foto['titulo_imagen']) ?>">
                    <h3><?= htmlspecialchars($foto['titulo_imagen']) ?></h3>
                    <p><strong>Participante:</strong> <?= htmlspecialchars($foto['nombre']) ?></p>
                    <p><strong>Concurso:</strong> <?= htmlspecialchars($foto['concurso']) ?></p>
                    <p><strong>Descripción:</strong> <?= nl2br(htmlspecialchars($foto['descripcion'])) ?></p>

                    <form method="POST">
                        <input type="hidden" name="foto_id" value="<?= $foto['id'] ?>">
                        <button class="admitir" name="accion" value="admitir">Admitir</button>
                        <button class="rechazar" name="accion" value="rechazar">Rechazar</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

</body>
</html>
