<?php
session_start();
if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] !== 'administrador') {
    header("Location: login.php");
    exit;
}
?>
<!-- Contenido exclusivo para administradores -->
