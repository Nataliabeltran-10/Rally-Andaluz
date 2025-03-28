<?php
session_start();
session_destroy();  // Destruir toda la sesión
header("Location: login.html");  // Redirigir al login
exit;
?>
