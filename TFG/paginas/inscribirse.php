<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    echo "Debes iniciar sesión para inscribirte.";
    exit;
}

if (!isset($_POST['idCurso'])) {
    echo "ID de curso no recibido.";
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$curso_id = intval($_POST['idCurso']);

$host = 'localhost'; // Cambia si es necesario
$db = 'nombre_basedatos'; // Cambia
$user = 'usuario'; // Cambia
$pass = 'contraseña'; // Cambia

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    echo "Error de conexión a la base de datos.";
    exit;
}

// Verificar si ya está inscrito
$stmt = $conn->prepare("SELECT COUNT(*) FROM inscripciones WHERE usuario_id = ? AND curso_id = ?");
$stmt->bind_param("ii", $usuario_id, $curso_id);
$stmt->execute();
$stmt->bind_result($existe);
$stmt->fetch();
$stmt->close();

if ($existe > 0) {
    echo "Ya estás inscrito en este curso.";
    $conn->close();
    exit;
}

// Insertar inscripción
$stmt = $conn->prepare("INSERT INTO inscripciones (usuario_id, curso_id) VALUES (?, ?)");
$stmt->bind_param("ii", $usuario_id, $curso_id);

if ($stmt->execute()) {
    echo "Te has inscrito correctamente.";
} else {
    echo "Error al inscribirse.";
}

$stmt->close();
$conn->close();