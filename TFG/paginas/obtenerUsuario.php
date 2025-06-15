<?php
// ../api/obtenerUsuario.php

require '../db.php';
session_start();
header('Content-Type: application/json');

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['error' => 'No hay sesión activa']);
    exit;
}

$usuarioId = $_SESSION['usuario_id'];

// Obtener datos del usuario
$sql = "SELECT ID_usu, Nombre, Email, Avatar FROM Usuarios WHERE ID_usu = ?";
$stmt = $conexion->prepare($sql);

if (!$stmt) {
    echo json_encode(['error' => 'Error al preparar la consulta: ' . $conexion->error]);
    exit;
}

$stmt->bind_param("i", $usuarioId);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 1) {
    $usuario = $resultado->fetch_assoc();
    echo json_encode([
        'id' => $usuario['ID_usu'],
        'nombre' => $usuario['Nombre'],
        'email' => $usuario['Email'],
        'avatar' => $usuario['Avatar'] ?? null // en caso de que no tenga avatar
    ]);
} else {
    echo json_encode(['error' => 'Usuario no encontrado']);
}

$stmt->close();
$conexion->close();