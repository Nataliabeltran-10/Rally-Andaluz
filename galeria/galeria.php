<?php
require_once("conexion.php");
session_start(); // Inicia la sesión antes de cualquier salida

$rutaBase = '../';

$usuario_id = $_SESSION['usuario_id'] ?? null;
$usuario_rol = $_SESSION['usuario_rol'] ?? null;

$concursoNombre = 'Lugares';
$hoy = date('Y-m-d');

// Procesar votación (antes de incluir header.php)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!$usuario_id) {
        header("Location: ../login/login.php");
        exit;
    }

    $foto_id = intval($_POST['foto_id']);

    // Verificar que no sea su propia foto
    $sqlFoto = "SELECT * FROM fotos WHERE id = ?";
    $stmtFoto = $conn->prepare($sqlFoto);
    $stmtFoto->execute([$foto_id]);
    $foto = $stmtFoto->fetch();

    if ($foto['usuario_id'] == $usuario_id) {
        $_SESSION['mensaje'] = "No puedes votar por tu propia foto.";
        header("Location: galeria.php");
        exit;
    }

    // Verificar fechas del concurso
    $puntuacion = intval($_POST['puntuacion']);
    $sqlConcursoFoto = "SELECT c.fecha_inicio, c.fecha_fin FROM fotos f JOIN concursos c ON f.concurso = c.nombre WHERE f.id = ?";
    $stmt = $conn->prepare($sqlConcursoFoto);
    $stmt->execute([$foto_id]);
    $datosConcurso = $stmt->fetch();

    if (!$datosConcurso || $hoy < $datosConcurso['fecha_inicio'] || $hoy > $datosConcurso['fecha_fin']) {
        $_SESSION['mensaje'] = "El periodo de votación ha finalizado o aún no ha comenzado.";
        header("Location: galeria.php");
        exit;
    }

    // Verificar si ya ha votado
    $sqlCheck = "SELECT * FROM votos WHERE foto_id = ? AND usuario_id = ?";
    $stmtCheck = $conn->prepare($sqlCheck);
    $stmtCheck->execute([$foto_id, $usuario_id]);
    $votoExistente = $stmtCheck->fetch();

    if ($votoExistente) {
        $_SESSION['mensaje'] = "Ya has votado esta foto anteriormente.";
    } else {
        $sqlInsert = "INSERT INTO votos (foto_id, usuario_id, puntuacion) VALUES (?, ?, ?)";
        $stmtInsert = $conn->prepare($sqlInsert);
        $stmtInsert->execute([$foto_id, $usuario_id, $puntuacion]);
        $_SESSION['mensaje'] = "Gracias por dejar tu voto.";
    }

    header("Location: galeria.php");
    exit;
}

// Después de procesar POST, ya puedes incluir archivos que generan salida
require_once('../header/header.php');

// Obtener datos del concurso
$sqlConcurso = "SELECT * FROM concursos WHERE LOWER(nombre) = LOWER(?)";
$stmtConcurso = $conn->prepare($sqlConcurso);
$stmtConcurso->execute([$concursoNombre]);
$concurso = $stmtConcurso->fetch();

$concursoActivo = false;
if ($concurso && $hoy >= $concurso['fecha_inicio'] && $hoy <= $concurso['fecha_fin']) {
    $concursoActivo = true;
}

// Obtener fotos admitidas
$sql = "SELECT * FROM fotos WHERE estado = 'admitida' AND LOWER(concurso) = LOWER(?)";
$stmt = $conn->prepare($sql);
$stmt->execute([$concursoNombre]);
$fotos = $stmt->fetchAll(PDO::FETCH_ASSOC);

$mensajeAgradecimiento = $_SESSION['mensaje'] ?? '';
unset($_SESSION['mensaje']);
?>

<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="../header/style.css">

<h2>Galería de Fotos: Concurso "Lugares"</h2>

<?php if ($mensajeAgradecimiento): ?>
    <div class="mensaje-error animar-mensaje"><?= htmlspecialchars($mensajeAgradecimiento) ?></div>
<?php endif; ?>

<div class="galeria-container">
    <?php foreach ($fotos as $foto): ?>
        <div class="foto-card">
            <?php
            $imagenBase64 = base64_encode($foto['imagen']);
            $titulo = htmlspecialchars($foto['titulo_imagen']);
            $descripcion = htmlspecialchars($foto['descripcion']);
            ?>
            <img src="data:image/jpeg;base64,<?= $imagenBase64 ?>" alt="<?= $titulo ?>">
            <h3><?= $titulo ?></h3>
            <p><?= $descripcion ?></p>
            <span class="concurso"><?= $foto['concurso'] ?></span>

            <?php if ($concursoActivo): ?>
                <form class="votacion-form" method="POST">
                    <input type="hidden" name="foto_id" value="<?= $foto['id'] ?>">
                    <div class="estrellas" data-id="<?= $foto['id'] ?>">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <span class="estrella" data-valor="<?= $i ?>">&#9733;</span>
                        <?php endfor; ?>
                    </div>
                    <input type="hidden" name="puntuacion" id="puntuacion-<?= $foto['id'] ?>" value="0">
                    <button type="submit" class="boton-enviar">Enviar</button>
                </form>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>

<script>
setTimeout(() => {
    const mensaje = document.querySelector('.mensaje-error');
    if (mensaje) mensaje.classList.remove('animar-mensaje');
}, 4000);

document.querySelectorAll('.estrellas').forEach(contenedor => {
    const estrellas = contenedor.querySelectorAll('.estrella');
    const input = document.getElementById('puntuacion-' + contenedor.dataset.id);
    let puntuacion = 0;

    estrellas.forEach((estrella, index) => {
        estrella.addEventListener('mouseover', () => {
            estrellas.forEach((e, i) => {
                e.style.color = i <= index ? 'gold' : 'black';
            });
        });

        estrella.addEventListener('mouseout', () => {
            estrellas.forEach((e, i) => {
                e.style.color = i < puntuacion ? 'gold' : 'black';
            });
        });

        estrella.addEventListener('click', () => {
            puntuacion = index + 1;
            input.value = puntuacion;
        });
    });
});
</script>
