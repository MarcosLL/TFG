<?php
session_start();

// Limpia todas las variables de sesión
$_SESSION = [];

// Destruye la sesión
session_destroy();

// Redirige a la página de inicio (sin sesión)
header("Location: inicio.html");
exit();
?>