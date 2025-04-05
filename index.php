<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Rally Fotográfico</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>

  <!-- Sección del video que ocupa toda la pantalla -->
  <section class="video-section">
    <div class="video-container">
      <video autoplay muted loop>
        <source src="VideoProvisional.mp4" type="video/mp4">
        Tu navegador no soporta este formato de video.
      </video>
    </div>
  </section>

  <!-- Cabecera que aparece cuando se hace scroll -->
  <header class="header">
    <div class="logo">
      <img src="logo.png" alt="Logo Rally" />
    </div>
    <div class="login-btn">
      <a href="login/login.html" class="btn">Acceder</a>
    </div>
  </header>

  <!-- Información sobre el rally -->
  <section class="info-rally">
    <h1>Bienvenidos al Rally Fotográfico</h1>
    <p>
      Participa en el rally fotográfico más emocionante de Andalucía. Captura las bellezas de las localidades más hermosas, 
      y haz que tu fotografía sea la más votada. 
      <br><br>
      <strong>Fechas:</strong> Del 1 de mayo al 30 de junio.
      <br>
      <strong>Temática:</strong> Las mejores vistas de Andalucía.
      <br><br>
      Consulta las bases del concurso, plazos y más detalles a continuación.
    </p>
  </section>

  <!-- Enlace de registro -->
  <section class="enlaces">
    <h2>¿Eres nuevo? ¡Regístrate ahora!</h2>
    <a href="registro_participante/registro.php" class="btn">Regístrate como Participante</a>
  </section>

  <!-- Acceso a la galería -->
  <section class="galeria">
    <h2>Galería de Fotografías Publicadas</h2>
    <a href="galeria.html" class="btn">Ver Galería</a>
  </section>

  <!-- Footer (opcional) -->
  <footer>
    <p>&copy; 2025 Rally Fotográfico | Todos los derechos reservados</p>
  </footer>

  <script src="script.js"></script>
</body>
</html>
