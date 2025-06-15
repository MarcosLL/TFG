<?php
$host = 'localhost';
$usuario = 'root'; 
$password = '';  
$base_de_datos = 'tfg'; 

$conexion = new mysqli($host, $usuario, $password, $base_de_datos);

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}
?>

