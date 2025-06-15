<?php
// ../api/obtenerCursosSuscritos.php
require '../db.php';
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode([]);
    exit;
}

$usuarioId = $_SESSION['usuario_id'];

$sql = "
    SELECT c.ID, c.Titulo, c.Descripcion, c.Imagen
    FROM Suscripciones s
    INNER JOIN Cursos c ON s.ID_curso = c.ID
    WHERE s.ID_usuario = ?
";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $usuarioId);
$stmt->execute();
$result = $stmt->get_result();

$cursos = [];
while ($fila = $result->fetch_assoc()) {
    $cursos[] = $fila;
}

echo json_encode($cursos);