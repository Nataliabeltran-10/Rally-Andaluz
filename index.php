<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inicio</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <div class="overlay"></div>
  <div class="content-container">

    <header>
      <div class="logo">AndaRally</div>
      <nav class="nav-buttons">
        <a href="registro/registroGeneral.php">Accede</a>
      </nav>
    </header>

    <section class="hero">
      <h1>Los mejores lugares y momentos de ANDALUCÍA</h1>
      <p>Participa con tus mejores fotos e impresionanos con lo mejor de nuestra Andalucía</p>
    </section>

    <section class="galeria">
      <h2>Fotos destacadas de participantes</h2>
      <div class="galeria-grid">
        <?php
        include 'conexion.php';
        $query = "SELECT imagen FROM fotos WHERE estado = 'admitida' ORDER BY fecha_subida DESC LIMIT 6";
        $resultado = mysqli_query($conexion, $query);

        if ($resultado && mysqli_num_rows($resultado) > 0) {
          while ($fila = mysqli_fetch_assoc($resultado)) {
            echo '<div class="foto">';
            echo '<img src="fotos/' . htmlspecialchars($fila['imagen']) . '" alt="Foto participante">';
            echo '</div>';
          }
        } else {
          echo '<p>No hay fotos admitidas aún.</p>';
        }

        mysqli_close($conexion);
        ?>
      </div>
    </section>

  </div>
</body>
</html>
