<?php
// ../api/obtenerCursosPublicados.php

require '../db.php';
session_start();
header('Content-Type: application/json');

// Verifica si el usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['error' => 'No hay sesión activa']);
    exit;
}

$usuarioId = $_SESSION['usuario_id'];

// Consulta para obtener cursos publicados por el usuario
$sql = "SELECT 
            c.ID_curso AS ID, 
            c.Titulo, 
            c.Descripcion, 
            c.Precio, 
            c.Imagen_curso AS Imagen
        FROM Publicaciones p
        JOIN Cursos c ON p.ID_curso = c.ID_curso
        WHERE p.ID_usu = ?";

$stmt = $conexion->prepare($sql);

if (!$stmt) {
    echo json_encode(['error' => 'Error al preparar la consulta: ' . $conexion->error]);
    exit;
}

$stmt->bind_param("i", $usuarioId);
$stmt->execute();
$resultado = $stmt->get_result();

$cursos = [];
while ($curso = $resultado->fetch_assoc()) {
    // Ajustar la ruta de la imagen si existe
    if (!empty($curso['Imagen'])) {
        $curso['Imagen'] = 'uploads/' . $curso['Imagen'];
    } else {
        $curso['Imagen'] = '../imagenes/default-curso.jpg'; // Imagen por defecto si no hay
    }
    $cursos[] = $curso;
}

// Devuelve los cursos en formato JSON
echo json_encode($cursos);

$stmt->close();
$conexion->close();