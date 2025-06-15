<?php
// procesar_login.php
require '../db.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $contraseña = $_POST['contraseña'];

    // Buscar el usuario en la base de datos
    $sql = "SELECT * FROM Usuarios WHERE Email = ?";
    $stmt = $conexion->prepare($sql);
    
    if (!$stmt) {
        die("Error preparando la consulta: " . $conexion->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $usuario = $result->fetch_assoc();
        
        // Verificar la contraseña
        if (password_verify($contraseña, $usuario['Contraseña'])) {
            // Inicio de sesión exitoso
            $_SESSION['usuario_id'] = $usuario['ID_usu'];
            $_SESSION['usuario_nombre'] = $usuario['Nombre'];
            $_SESSION['usuario_email'] = $usuario['Email'];
            $_SESSION['usuario_avatar'] = !empty($usuario['Foto_perfil']) 
                ? "uploads/" . $usuario['Foto_perfil'] 
                : "../imagenes/avatar_default.png";
            
            // Redirigir al usuario a la página principal
            header("Location: index.html");
            exit();
        } else {
            // Contraseña incorrecta, volver al login con mensaje de error
            header("Location: sesion.html?error=contraseña_incorrecta");
            exit();
        }
    } else {
        // Usuario no encontrado, volver al login con mensaje de error
        header("Location: sesion.html?error=usuario_no_encontrado");
        exit();
    }

    $stmt->close();
    $conexion->close();
} else {
    // Método no permitido
    header("Location: sesion.html?error=metodo_no_permitido");
    exit();
}
?>