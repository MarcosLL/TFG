<?php
// verificar_sesion.php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    // Si no hay sesión activa, redirigir al login
    header("Location: sesion.html");
    exit();
}

// Si hay sesión, puedes acceder a estos datos en cualquier página
$usuario_id = $_SESSION['usuario_id'];
$usuario_nombre = $_SESSION['usuario_nombre'];
$usuario_email = $_SESSION['usuario_email'];
?>