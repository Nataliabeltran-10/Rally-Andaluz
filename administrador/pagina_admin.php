<?php
require_once("conexion.php");
session_start();

// Asegurar que el usuario sea administrador (opcional)
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'administrador') {
    header("Location: ../login/login.php");
    exit;
}

// Cambiar estado de la imagen si se hizo clic en Admitir o Rechazar
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fotoId = $_POST['foto_id'];
    $nuevoEstado = $_POST['accion'] === 'admitir' ? 'admitida' : 'rechazada';

    $sqlUpdate = "UPDATE fotos SET estado = ? WHERE id = ?";
    $stmtUpdate = $conn->prepare($sqlUpdate);
    $stmtUpdate->execute([$nuevoEstado, $fotoId]);
}

// Obtener todas las imágenes pendientes
$sql = "SELECT id, titulo_imagen, descripcion, imagen FROM fotos WHERE estado = 'pendiente'";
$stmt = $conn->prepare($sql);
$stmt->execute();
$fotosPendientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Panel de Fotos Pendientes</h1>

    <div class="galeria-admin">
        <?php if (count($fotosPendientes) === 0): ?>
            <p style="color: white;">No hay fotos pendientes.</p>
        <?php else: ?>
            <?php foreach ($fotosPendientes as $foto): ?>
                <div class="foto-card">
                    <img src="data:image/jpeg;base64,<?= base64_encode($foto['imagen']) ?>" alt="Imagen">
                    <h3><?= htmlspecialchars($foto['titulo_imagen']) ?></h3>
                    <p><?= nl2br(htmlspecialchars($foto['descripcion'])) ?></p>

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
