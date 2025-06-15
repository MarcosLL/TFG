<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401);
    echo json_encode([]);
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

$host = 'localhost'; // Cambia si es necesario
$db = 'nombre_basedatos'; // Cambia
$user = 'usuario'; // Cambia
$pass = 'contraseña'; // Cambia

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode([]);
    exit;
}

$sql = "SELECT c.ID_curso, c.Titulo, c.tema, c.Precio, c.Descripcion, c.Imagen_curso 
        FROM cursos c 
        INNER JOIN inscripciones i ON c.ID_curso = i.curso_id 
        WHERE i.usuario_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get