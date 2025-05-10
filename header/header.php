<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$rutaBase = $rutaBase ?? '';
$tipo = $_SESSION['usuario_tipo'] ?? null;
?>

<header class="main-header">
  <div class="logo">
    <a href="<?= $rutaBase ?>index.php" class="logo-link">
      <span class="logo-anda">Anda</span><span class="logo-rally">Rally</span>
    </a>
  </div>

  <nav class="nav-buttons">
    <!-- Agrupamos los botones en una línea -->
    <div class="menu-user-group">
      <!-- Botón Menú -->
      <button id="menu-toggle" class="btn-menu">Menú</button> <!-- Botón Menú estilizado igual al de Acceder -->
      <div id="menu-modal" class="modal oculto">
        <div class="modal-content">
          <a href="<?= $rutaBase ?>galeria/galeria.php">Concurso Lugares</a>
          <a href="<?= $rutaBase ?>galeria/galeria_tradiciones.php">Concurso Tradiciones</a>
          <a href="<?= $rutaBase ?>rankings/rankings.php">Rankings</a>
          <?php if ($tipo === 'participante'): ?>
            <a href="<?= $rutaBase ?>participante/pagina_participante.php">Participante</a>
          <?php elseif ($tipo === 'administrador'): ?>
            <a href="<?= $rutaBase ?>administrador/pagina_administrador.php">Administrador</a>
          <?php endif; ?>
        </div>
      </div>

      <!-- Accede o Usuario -->
      <?php if (isset($_SESSION['usuario_id'])): ?>
        <button id="usuario-nombre" class="btn-usuario">
          <?= htmlspecialchars($_SESSION['usuario_nombre']) ?>
        </button>
      <?php else: ?>
        <a href="<?= $rutaBase ?>login/login.php" class="btn-accede">Accede</a>
      <?php endif; ?>
    </div>
  </nav>
</header>

<?php if (isset($_SESSION['usuario_id'])): ?>
  <div id="modal-usuario" class="modal oculto">
    <div class="modal-content">
      <h3>Mi Perfil</h3>
      <p><strong>Nombre:</strong> <?= htmlspecialchars($_SESSION['usuario_nombre']) ?></p>
      <p><strong>Email:</strong> <?= htmlspecialchars($_SESSION['usuario_email']) ?></p>
      <div class="modal-actions">
        <a href="<?= $rutaBase ?>editar_perfil/editar_perfil.php" class="btn-editar">Editar Perfil</a>
        <button onclick="cerrarSesion()" class="btn-cerrar">Cerrar Sesión</button>
      </div>
    </div>
  </div>
<?php endif; ?>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    // Modal usuario
    const usuarioBtn = document.getElementById("usuario-nombre");
    const usuarioModal = document.getElementById("modal-usuario");

    usuarioBtn?.addEventListener("click", function () {
      usuarioModal.classList.toggle("visible");
    });

    // Cerrar modal usuario si haces clic fuera
    document.addEventListener("click", function (event) {
      if (usuarioModal && !usuarioModal.contains(event.target) && event.target !== usuarioBtn) {
        usuarioModal.classList.remove("visible");
      }
    });

    // Modal menú
    const menuBtn = document.getElementById("menu-toggle");
    const menuModal = document.getElementById("menu-modal");

    menuBtn?.addEventListener("click", function () {
      menuModal.classList.toggle("visible");
    });

    // Cerrar modal menú si haces clic fuera
    document.addEventListener("click", function (event) {
      if (menuModal && !menuModal.contains(event.target) && event.target !== menuBtn) {
        menuModal.classList.remove("visible");
      }
    });
  });

  function cerrarSesion() {
    window.location.replace("<?= $rutaBase ?>login/logout.php");
  }
</script>
