<?php
// ../api/obtenerResenasUsuario.php
require '../db.php';
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode([]);
    exit;
}

$usuarioId = $_SESSION['usuario_id'];

$sql = "
    SELECT r.Comentario, r.Calificacion, r.Fecha, c.Titulo AS curso_titulo
    FROM Reseñas r
    INNER JOIN Cursos c ON r.ID_curso = c.ID
    WHERE r.ID_usuario = ?
";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $usuarioId);
$stmt->execute();
$result = $stmt->get_result();

$resenas = [];
while ($fila = $result->fetch_assoc()) {
    $resenas[] = $fila;
}

echo json_encode($resenas);