<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: sesion.html");
    exit();
}

$conexion = new mysqli("localhost", "root", "", "tfg");
if ($conexion->connect_error) {
    die("Error en la conexión: " . $conexion->connect_error);
}

$id_usuario = $_SESSION['usuario_id'];
$nuevo_nombre = trim($_POST['nombre']);
$nueva_contrasena = trim($_POST['nueva_contrasena']);

$nombre_archivo = null;

if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] === UPLOAD_ERR_OK) {
    $directorio_subida = "uploads/";
    if (!is_dir($directorio_subida)) {
        mkdir($directorio_subida, 0755, true);
    }

    $ext = pathinfo($_FILES['foto_perfil']['name'], PATHINFO_EXTENSION);
    $nombre_archivo = "avatar_" . $id_usuario . "_" . time() . "." . $ext;
    $ruta_archivo = $directorio_subida . $nombre_archivo;

    move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $ruta_archivo);
}

// Preparar sentencia SQL dinámica
$updates = "Nombre = ?";
$params = [$nuevo_nombre];
$types = "s";

if (!empty($nueva_contrasena)) {
    $hash = password_hash($nueva_contrasena, PASSWORD_DEFAULT);
    $updates .= ", Contraseña = ?";
    $params[] = $hash;
    $types .= "s";
}

if ($nombre_archivo) {
    $updates .= ", Foto_perfil = ?";
    $params[] = $nombre_archivo;
    $types .= "s";
}

$params[] = $id_usuario;
$types .= "i";

$sql = "UPDATE Usuarios SET $updates WHERE ID_usu = ?";
$stmt = $conexion->prepare("UPDATE Usuarios SET $updates WHERE ID_usu = ?");
$stmt->bind_param($types, ...$params);

if ($stmt->execute()) {
    // Actualizar sesión
    $_SESSION['usuario_nombre'] = $nuevo_nombre;
    if ($nombre_archivo) {
        $_SESSION['usuario_avatar'] = "uploads/" . $nombre_archivo;
    }

    header("Location: perfil.php");
    exit();
} else {
    echo "Error al actualizar el perfil: " . $stmt->error;
}

$stmt->close();
$conexion->close();
?>