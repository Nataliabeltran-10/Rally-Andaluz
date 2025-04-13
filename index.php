<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Rally Fotográfico</title>
  <link rel="stylesheet" href="styles.css">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>

<div class="background-wrapper" style="background-image: url('fotos/fondo.jpg');">
    <div class="background-overlay"></div>

    <div class="main-content">
      <h1>Bienvenido al AndaRally</h1>
      <h2>El Rally Fotográfico de Andalucía</h2>
      <p>Explora, captura y comparte la belleza de nuestra tierra.  
      ¡Únete y participa en esta experiencia única!</p>
    </div>
  </div>


  <!-- Cabecera que aparece cuando se hace scroll -->
  <header class="header">
    <div class="logo">
      <img src="logo.png" alt="Logo Rally" />
    </div>
    <div class="login-btn">
      <a href="registro/registroGeneral.php" class="btn">Acceder</a>
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
