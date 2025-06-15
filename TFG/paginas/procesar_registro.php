<?php
require '../db.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['usuario'];
    $email = $_POST['email'];
    $clave = $_POST['clave'];
    $confirmar_clave = $_POST['confirmar-clave'];

    if ($clave !== $confirmar_clave) {
        header("Location: registro.html?error=claves_no_coinciden");
        exit;
    }

    $clave_encriptada = password_hash($clave, PASSWORD_DEFAULT);

    $sql = "INSERT INTO Usuarios (Nombre, Email, Contraseña) VALUES (?, ?, ?)";
    $stmt = $conexion->prepare($sql);

    if (!$stmt) {
        header("Location: registro.html?error=fallo_preparacion");
        exit;
    }

    $stmt->bind_param("sss", $nombre, $email, $clave_encriptada);

    if ($stmt->execute()) {
        header("Location: sesion.html?registro=exitoso");
        exit;
    } else {
        if ($conexion->errno == 1062) {
            // Email duplicado
            header("Location: registro.html?error=email_duplicado");
        } else {
            header("Location: registro.html?error=registro_fallido");
        }
        exit;
    }

    $stmt->close();
    $conexion->close();
} else {
    header("Location: registro.html?error=metodo_no_permitido");
    exit;
}
?>



