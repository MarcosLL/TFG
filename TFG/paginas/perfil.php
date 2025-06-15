<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario_nombre']) || !isset($_SESSION['usuario_email']) || !isset($_SESSION['usuario_id'])) {
    header("Location: sesion.html");
    exit();
}

$nombre = $_SESSION['usuario_nombre'];
$email = $_SESSION['usuario_email'];
$avatar = isset($_SESSION['usuario_avatar']) ? $_SESSION['usuario_avatar'] : '../imagenes/avatar_default.png';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Perfil</title>
    <link rel="stylesheet" href="../estilos/menuTop.css" />
    <link rel="stylesheet" href="../estilos/inicio.css" />
    <link rel="stylesheet" href="../estilos/footer.css" />
    <link rel="stylesheet" href="../estilos/perfil.css" />
</head>
<body>
    <div class="container">
        <header class="menu">
            <div class="logo">
                <a href="inicio.html"><img src="../imagenes/logo.png" alt="Logo" /></a>
            </div>
            <nav>
                <ul>
                    <li class="search-bar">
                        <form action="#" method="get">
                            <input type="text" name="search" placeholder="Buscar..." aria-label="Buscar" />
                        </form>
                    </li>
                    <li><a href="creacurso.html">Publicar curso</a></li>
                    <li><a href="inicio.html">Home</a></li>
                    <li><a href="logout.php">Salir</a></li>
                </ul>
            </nav>
        </header>

        <section class="perfil">
            <div class="perfil-header">
                <div class="avatar">
                    <img src="<?php echo htmlspecialchars($avatar); ?>" alt="Avatar" id="user-avatar">
                </div>
                <div class="user-info">
                    <h1 id="user-name"><?php echo htmlspecialchars($nombre); ?></h1>
                    <p id="user-email"><?php echo htmlspecialchars($email); ?></p>
                    <button id="edit-profile"><a href="editar_perfil.php">Editar perfil</a></button>
                </div>
            </div>

            <div class="tabs">
                <button class="tab-button active" data-tab="published-courses">Cursos Publicados</button>
                <button class="tab-button" data-tab="subscribed-courses">Cursos Suscritos</button>
                <button class="tab-button" data-tab="reviews">Mis Reseñas</button>
            </div>

            <div class="tab-content active" id="published-courses">
                <h2>Mis Cursos Publicados</h2>
                <div class="courses-grid" id="published-courses-list">
                    <!-- Cursos publicados se cargan aquí -->
                </div>
            </div>

            <div class="tab-content" id="subscribed-courses">
                <h2>Cursos en los que estoy suscrito</h2>
                <div class="courses-grid" id="subscribed-courses-list">
                    <!-- Cursos suscritos se cargan aquí -->
                </div>
            </div>

            <div class="tab-content" id="reviews">
                <h2>Mis Reseñas</h2>
                <div class="reviews-list" id="user-reviews">
                    <!-- Aquí puedes añadir las reseñas del usuario -->
                </div>
            </div>
        </section>
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

    <script>
        // Pestañas
        document.querySelectorAll('.tab-button').forEach(button => {
            button.addEventListener('click', function () {
                document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
                document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));

                this.classList.add('active');
                const tabId = this.getAttribute('data-tab');
                document.getElementById(tabId).classList.add('active');
            });
        });

        // Cursos publicados
        window.addEventListener('DOMContentLoaded', () => {
            fetch('obtenerCursosPublicados.php')
                .then(res => res.json())
                .then(cursos => {
                    const container = document.getElementById('published-courses-list');

                    if (!cursos || cursos.length === 0) {
                        container.innerHTML = '<p>No has publicado ningún curso aún.</p>';
                        return;
                    }

                    cursos.forEach(curso => {
                        const div = document.createElement('div');
                        div.classList.add('course-card');
                        div.innerHTML = `
                            <img src="${curso.Imagen || '../imagenes/curso-default.jpg'}" alt="Imagen del curso" class="course-image" />
                            <h3>${curso.Titulo}</h3>
                            <p>${curso.Descripcion}</p>
                            <span class="precio">Precio: €${curso.Precio}</span>
                        `;
                        container.appendChild(div);
                    });
                })
                .catch(err => {
                    console.error(err);
                    document.getElementById('published-courses-list').innerHTML = '<p>Error al cargar cursos publicados.</p>';
                });

            // Cursos inscritos
            cargarCursosInscritos();
        });

        function cargarCursosInscritos() {
            const container = document.getElementById('subscribed-courses-list');
            container.innerHTML = ''; // Limpiar contenido antes de cargar

            fetch('obtenerCursosInscritos.php')
                .then(res => {
                    if (!res.ok) throw new Error("Error en la respuesta");
                    return res.json();
                })
                .then(cursos => {
                    if (!cursos || cursos.length === 0) {
                        container.innerHTML = '<p>No estás inscrito en ningún curso.</p>';
                        return;
                    }

                    cursos.forEach(curso => {
                        const div = document.createElement('div');
                        div.classList.add('course-card');
                        div.innerHTML = `
                            <img src="${curso.Imagen_curso || '../imagenes/curso-default.jpg'}" alt="Imagen del curso" class="course-image" />
                            <h3>${curso.Titulo}</h3>
                            <p>${curso.Descripcion}</p>
                            <span class="estado">Estado: ${curso.Estado}</span>
                        `;
                        container.appendChild(div);
                    });
                })
                .catch(err => {
                    console.error(err);
                    container.innerHTML = '<p>Error al cargar cursos inscritos.</p>';
                });
        }
    </script>
</body>
</html>