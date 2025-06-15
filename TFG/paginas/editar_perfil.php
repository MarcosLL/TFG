<?php
session_start();

if (!isset($_SESSION['usuario_id'], $_SESSION['usuario_nombre'], $_SESSION['usuario_email'])) {
    header("Location: sesion.html");
    exit();
}

$conexion = new mysqli("localhost", "root", "", "tfg");
if ($conexion->connect_error) {
    die("Error en la conexión: " . $conexion->connect_error);
}

$id_usuario = $_SESSION['usuario_id'];
$sql = "SELECT Nombre, Email, Foto_perfil FROM Usuarios WHERE ID_usu = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$resultado = $stmt->get_result();
$usuario = $resultado->fetch_assoc();
$stmt->close();

$avatar = !empty($usuario['Foto_perfil']) ? "uploads/" . $usuario['Foto_perfil'] : "../imagenes/avatar_default.png";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Editar Perfil</title>
    <link rel="stylesheet" href="../estilos/menuTop.css" />
    <link rel="stylesheet" href="../estilos/inicio.css" />
    <link rel="stylesheet" href="../estilos/footer.css" />
    <link rel="stylesheet" href="../estilos/perfil.css" />
    <link rel="stylesheet" href="../estilos/editar_perfil.css" />
    
</head>
<body>
    <div class="container">
        <header class="menu">
            <div class="logo">
                <a href="index.html"><img src="../imagenes/logo.png" alt="Logo" /></a>
            </div>
            <nav>
                <ul>
                    <li class="search-bar">
                        <form action="#" method="get">
                            <input type="text" name="search" placeholder="Buscar..." aria-label="Buscar" />
                        </form>
                    </li>
                    <li><a href="creacurso.html">Publicar curso</a></li>
                    <li><a href="index.html">Home</a></li>
                    <li><a href="logout.php">Salir</a></li>
                </ul>
            </nav>
        </header>

        <form class="editar-perfil-form" action="procesar_editar_perfil.php" method="post" enctype="multipart/form-data">
            <h2>Editar Perfil</h2>

            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($usuario['Nombre']) ?>" required>

            <label for="nueva_contrasena">Nueva Contraseña:</label>
            <input type="password" id="nueva_contrasena" name="nueva_contrasena">


            <label>Foto de Perfil:</label>
            <img src="<?= htmlspecialchars($avatar) ?>" alt="Avatar actual">
            <input type="file" name="foto_perfil" accept="image/*">

            <button type="submit">Guardar Cambios</button>
            <a href="perfil.php">← Volver al Perfil</a>
        </form>
    </div>

    <footer class="pie">
        <div class="footer-content">
            <div class="footer-logo">
                <img id="minilogo" src="../imagenes/logo.png" alt="Logo" />
                <span class="copyright-text">© 2025 ProClass</span>
            </div>
            <div class="footer-divider"></div>
            <div class="footer-links">
                <a href="PoliticaPrivacidad.html">Política de privacidad</a>
                <a href="preguntas.html">Preguntas Frecuentes</a>
                <a href="contacto.html">Contacto</a>
            </div>
        </div>
    </footer>
</body>
</html>