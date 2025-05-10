<?php
session_start();
$rutaBase = '../';

require_once("conexion.php");
require_once("../header/header.php");

// Solo acceso a administradores
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'administrador') {
    header("Location: {$rutaBase}login/login.php");
    exit;
}

// Procesar cambio de estado de foto
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['foto_id'])) {
    $fotoId = intval($_POST['foto_id']);
    $accion = $_POST['accion'];
    $nuevoEstado = ($accion === 'admitir') ? 'admitida' : 'rechazada';

    $sqlUpdate = "UPDATE fotos SET estado = ? WHERE id = ?";
    $stmtUpdate = $conn->prepare($sqlUpdate);
    $stmtUpdate->execute([$nuevoEstado, $fotoId]);
}

// Procesar edición/eliminación de usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['usuario_accion'])) {
    $usuarioId = intval($_POST['usuario_id']);
    if ($_POST['usuario_accion'] === 'eliminar') {
        $stmt = $conn->prepare("DELETE FROM usuarios WHERE id = ?");
        $stmt->execute([$usuarioId]);
    } elseif ($_POST['usuario_accion'] === 'editar') {
        $nuevoNombre = $_POST['nuevo_nombre'];
        $nuevoEmail = $_POST['nuevo_email'];
        $stmt = $conn->prepare("UPDATE usuarios SET nombre = ?, email = ? WHERE id = ?");
        $stmt->execute([$nuevoNombre, $nuevoEmail, $usuarioId]);
    }
}

// Fotos pendientes
$sqlFotos = "SELECT f.id, f.titulo_imagen, f.descripcion, f.concurso, u.nombre, f.imagen
            FROM fotos f
            JOIN usuarios u ON f.usuario_id = u.id
            WHERE f.estado = 'pendiente'
            ORDER BY f.id DESC";
$stmt = $conn->prepare($sqlFotos);
$stmt->execute();
$fotos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Ganadores: más puntuación por concurso
$sqlGanadores = "SELECT f.concurso, f.id AS foto_id, f.titulo_imagen, u.nombre, SUM(v.puntuacion) AS total
                 FROM fotos f
                 JOIN votos v ON f.id = v.foto_id
                 JOIN usuarios u ON f.usuario_id = u.id
                 WHERE f.estado = 'admitida'
                 GROUP BY f.id
                 ORDER BY f.concurso, total DESC";
$stmt = $conn->prepare($sqlGanadores);
$stmt->execute();
$ganadores = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Participantes
$sqlUsuarios = "SELECT * FROM usuarios WHERE rol = 'participante'";
$stmt = $conn->prepare($sqlUsuarios);
$stmt->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administrador</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="../header/style.css">
</head>
<body>
<h1 class="titulo-principal">Panel de Administrador</h1>

<section>
    <h2 class="titulo">Admisión de Fotos</h2>
    <div class="galeria-admin">
        <?php if (empty($fotos)): ?>
            <p>No hay fotos pendientes.</p>
        <?php else: ?>
            <?php foreach ($fotos as $foto): ?>
                <div class="foto-card">
                    <img src="data:image/jpeg;base64,<?= base64_encode($foto['imagen']) ?>" alt="<?= htmlspecialchars($foto['titulo_imagen']) ?>">
                    <h3><?= htmlspecialchars($foto['titulo_imagen']) ?></h3>
                    <p><strong>Participante:</strong> <?= htmlspecialchars($foto['nombre']) ?></p>
                    <p><strong>Concurso:</strong> <?= htmlspecialchars($foto['concurso']) ?></p>
                    <p><?= nl2br(htmlspecialchars($foto['descripcion'])) ?></p>
                    <form method="POST">
                        <input type="hidden" name="foto_id" value="<?= $foto['id'] ?>">
                        <button name="accion" value="admitir" class="admitir">Admitir</button>
                        <button name="accion" value="rechazar" class="rechazar">Rechazar</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>

<section>
    <h2 class="titulo">Ganadores por Concurso</h2>
    <div class="ganadores">
        <?php
        $concursoActual = '';
        foreach ($ganadores as $ganador):
            if ($ganador['concurso'] !== $concursoActual):
                if ($concursoActual !== '') echo "</ul>";
                $concursoActual = $ganador['concurso'];
                echo "<h3 class='copa'>" . htmlspecialchars($concursoActual) . " - ¡Ganadores!</h3><ul>";
            endif;
            echo "<li>" . htmlspecialchars($ganador['titulo_imagen']) . " - " . htmlspecialchars($ganador['nombre']) . " (" . $ganador['total'] . " puntos)</li>";
        endforeach;
        if ($concursoActual !== '') echo "</ul>";
        ?>
    </div>
</section>

<section>
    <h2 class="titulo">Edición de Datos de Participantes</h2>
    <div class="tabla-container">
        <table class="tabla-participantes">
            <tr>
                <th>Nombre</th>
                <th>Email</th>
                <th>Acciones</th>
            </tr>
            <?php foreach ($usuarios as $usuario): ?>
                <tr>
                    <form method="POST">
                        <td><input type="text" name="nuevo_nombre" value="<?= htmlspecialchars($usuario['nombre']) ?>"></td>
                        <td><input type="email" name="nuevo_email" value="<?= htmlspecialchars($usuario['email']) ?>"></td>
                        <td>
                            <input type="hidden" name="usuario_id" value="<?= $usuario['id'] ?>">
                            <button name="usuario_accion" value="editar">Guardar</button>
                            <button name="usuario_accion" value="eliminar" onclick="return confirm('¿Eliminar usuario?')">Eliminar</button>
                        </td>
                    </form>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</section>

</body>
</html>
