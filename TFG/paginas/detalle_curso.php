<?php
// detalle_curso.php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tfg";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID de curso no válido.");
}

$id_curso = intval($_GET['id']);

// Consulta para obtener los datos del curso con JOIN para traer categoría, usuario y ubicación
$sql = "SELECT c.Titulo, c.Descripcion, c.Duracion_horas, c.Precio, c.Imagen_curso, c.Fecha_inicio,
        cat.Nombre AS Categoria, u.Nombre AS Usuario, ubi.Ciudad, ubi.Pais
        FROM Cursos c
        JOIN Categorias cat ON c.ID_cat = cat.ID_cat
        JOIN Publicaciones p ON p.ID_curso = c.ID_curso
        JOIN Usuarios u ON p.ID_usu = u.ID_usu
        JOIN Ubicaciones ubi ON p.ID_ubicacion = ubi.ID_ubi
        WHERE c.ID_curso = ? LIMIT 1";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_curso);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Curso no encontrado.");
}

$curso = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="../estilos/inicio.css" />
    <link rel="stylesheet" href="../estilos/menuNav.css" />
    <link rel="stylesheet" href="../estilos/menuTop.css" />
    <link rel="stylesheet" href="../estilos/footer.css" />
    <link rel="stylesheet" href="../estilos/carrusel.css" />
    <link rel="stylesheet" href="../estilos/preguntas.css" />
    <link rel="stylesheet" href="../estilos/politicas.css" />
    <link rel="stylesheet" href="../estilos/contacto.css" />
    <link rel="stylesheet" href="../estilos/suscripciones.css" />
    <title>ProClass - Detalle del Curso</title>
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
                    <form id="searchForm" method="get">
                        <input type="text" name="search" placeholder="Buscar..." aria-label="Buscar" />
                        <button type="button" class="toggle-advanced">Búsqueda avanzada ▼</button>
                        <div class="search-advanced">
                            <div class="filtro-grupo">
                                <label for="search-categoria">Categoría:</label>
                                <select id="search-categoria" name="categoria">
                                    <option value="">Todas las categorías</option>
                                    <option value="Cocina">Cocina</option>
                                    <option value="Música">Música</option>
                                    <option value="Artesanía">Artesanía</option>
                                    <option value="Idiomas">Idiomas</option>
                                    <option value="Arte">Arte</option>
                                    <option value="Danza">Danza</option>
                                </select>
                            </div>
                            <div class="filtro-grupo">
                                <label for="search-precio">Rango de precios:</label>
                                <select id="search-precio" name="precio">
                                    <option value="">Todos los precios</option>
                                    <option value="0-50">$0 - $50</option>
                                    <option value="50-100">$50 - $100</option>
                                    <option value="100-200">$100 - $200</option>
                                    <option value="200+">Más de $200</option>
                                </select>
                            </div>
                            <div class="filtro-grupo">
                                <label for="search-ubicacion">Ubicación:</label>
                                <input type="text" id="search-ubicacion" name="ubicacion" placeholder="Ciudad o barrio" />
                            </div>
                            <button type="submit" class="btn-buscar-advanced">Aplicar Filtros</button>
                        </div>
                    </form>
                </li>
                <li><a href="creacurso.html">Publicar curso</a></li>
                <li><a href="index.html">Home</a></li>
                <li><a href="perfil.php">Perfil</a></li>
                <li><a href="sesion.html">Ingresar</a></li>
            </ul>
        </nav>
    </header>

    <section class="contenido1">
        <nav>
            <ul>
                <li>
                    <ul>
                        <li><a href="#" class="filtro-categoria" data-categoria="Cocina">Cocina</a></li>
                        <li><a href="#" class="filtro-categoria" data-categoria="Música">Música</a></li>
                        <li><a href="#" class="filtro-categoria" data-categoria="Artesanía">Artesanía</a></li>
                        <li><a href="#" class="filtro-categoria" data-categoria="Idiomas">Idiomas</a></li>
                        <li><a href="#" class="filtro-categoria" data-categoria="Arte">Arte</a></li>
                        <li><a href="#" class="filtro-categoria" data-categoria="Danza">Danza</a></li>
                    </ul>
                </li>
                <li><a href="#" id="quitar-filtros">Ver todos</a></li>
            </ul>
        </nav>
    </section>

    <main>
        <section class="detalle-curso">
            <h1><?php echo htmlspecialchars($curso['Titulo']); ?></h1>
            <img src="uploads/<?php echo htmlspecialchars($curso['Imagen_curso']); ?>" alt="Imagen del curso" style="max-width: 500px; height: auto; margin-bottom: 20px;" />
            <p><strong>Categoría:</strong> <?php echo htmlspecialchars($curso['Categoria']); ?></p>
            <p><strong>Publicado por:</strong> <?php echo htmlspecialchars($curso['Usuario']); ?></p>
            <p><strong>Ubicación:</strong> <?php echo htmlspecialchars($curso['Ciudad'] . ", " . $curso['Pais']); ?></p>
            <p><strong>Fecha de inicio:</strong> <?php echo htmlspecialchars($curso['Fecha_inicio']); ?></p>
            <p><strong>Duración:</strong> <?php echo htmlspecialchars($curso['Duracion_horas']); ?> horas</p>
            <p><strong>Precio:</strong> €<?php echo htmlspecialchars($curso['Precio']); ?></p>
            <h2>Descripción</h2>
            <p><?php echo nl2br(htmlspecialchars($curso['Descripcion'])); ?></p>
        </section>
    </main>

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
</div>
</body>
</html>