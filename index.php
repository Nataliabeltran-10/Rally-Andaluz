<?php
session_start();
include 'conexion.php';
$rutaBase = ''; // estamos en la raíz

// Obtener 6 fotos admitidas
$query = "SELECT imagen, concurso FROM fotos WHERE estado = 'admitida' ORDER BY fecha_subida DESC LIMIT 6";
$resultado = mysqli_query($conexion, $query);

$fotos = [];
if ($resultado && mysqli_num_rows($resultado) > 0) {
    while ($fila = mysqli_fetch_assoc($resultado)) {
        $fotos[] = [
            'imagen' => base64_encode($fila['imagen']),
            'concurso' => $fila['concurso']
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Inicio - AndaRally</title>
  <link rel="stylesheet" href="header/style.css" />
  <link rel="stylesheet" href="styles.css" />
</head>
<body>

  <?php include 'header/header.php'; ?>

  <div class="overlay"></div>
  <div class="content-container">

    <section class="hero">
      <h1>Los mejores lugares y momentos de ANDALUCÍA</h1>
      <p>Participa en nuestro Rally Fotográfico y comparte tu mirada sobre nuestra tierra.</p>
      <p>Entra en uno de los concursos y deja tu huella visual: <strong>“Lugares de Andalucía”</strong> o <strong>“Tradiciones Andaluzas”</strong>.</p>

      <div class="botones-concursos">
        <div class="concurso">
          <a class="boton-concurso" href="galeria/galeria.php">📍 Concurso de Lugares</a>
          <div class="reloj" id="reloj-lugares"></div>
        </div>
        <div class="concurso">
          <a class="boton-concurso" href="galeria/galeria_tradiciones.php">🎭 Concurso de Tradiciones</a>
          <div class="reloj" id="reloj-tradiciones"></div>
        </div>
        <div class="concurso">
          <a class="boton-concurso" href="rankings\rankings.php">Rankings</a>
        </div>
      </div>
    </section>

    <section class="galeria">
      <h2>Fotos destacadas de participantes</h2>
      <div class="galeria-grid">
        <?php foreach ($fotos as $foto): ?>
          <div class="foto-tarjeta">
            <div class="etiqueta"><?= ucfirst($foto['concurso']) ?></div>
            <a href="<?= isset($_SESSION['usuario_id']) 
                ? ($foto['concurso'] === 'lugares' 
                  ? 'galeria/galeria.php' 
                  : 'galeria/galeria_tradiciones.php') 
                : 'login/login.php' ?>">
              <img src="data:image/jpeg;base64,<?= $foto['imagen']; ?>" alt="Foto participante" />
            </a>
          </div>
        <?php endforeach; ?>
      </div>
    </section>

  </div>

  <script>
    function iniciarCuentaAtras(id, fechaFinStr) {
      const reloj = document.getElementById(id);
      const fechaFin = new Date(fechaFinStr).getTime();

      const actualizar = () => {
        const ahora = new Date().getTime();
        const restante = fechaFin - ahora;

        if (restante <= 0) {
          reloj.innerHTML = "⏰ Concurso finalizado";
          return;
        }

        const dias = Math.floor(restante / (1000 * 60 * 60 * 24));
        const horas = Math.floor((restante % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutos = Math.floor((restante % (1000 * 60 * 60)) / (1000 * 60));
        const segundos = Math.floor((restante % (1000 * 60)) / 1000);

        reloj.innerHTML = `⏳ ${dias}d ${horas}h ${minutos}m ${segundos}s`;
      };

      actualizar();
      setInterval(actualizar, 1000);
    }

    iniciarCuentaAtras('reloj-lugares', '2025-06-15T23:59:59');
    iniciarCuentaAtras('reloj-tradiciones', '2025-06-20T23:59:59');
  </script>

</body>
</html>
