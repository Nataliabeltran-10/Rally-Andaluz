<?php
session_start();
if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] !== 'usuario_normal') {
    header("Location: login.php");
    exit;
}
?>
<!-- Contenido exclusivo para usuarios normales -->
<p>Pagina usuario normal</p>