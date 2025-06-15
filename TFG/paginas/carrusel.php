<?php
$host = 'localhost';
$db = 'tfg';
$user = 'root';
$pass = '';
$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";

try {
    $stmt = $conn->prepare("SELECT ID_curso, Titulo, tema, Precio, Descripcion, Imagen_curso FROM cursos ORDER BY RAND() LIMIT 3");
    $stmt->execute();
    $cursos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($cursos);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Error al obtener cursos: ' . $e->getMessage()]);
}

// Obtener 4 cursos aleatorios
$sql = "SELECT c.ID_curso, c.Titulo, c.Precio, cat.Nombre AS tema, c.Imagen_curso
        FROM Cursos c
        JOIN Categorias cat ON c.ID_cat = cat.ID_cat
        JOIN Publicaciones p ON c.ID_curso = p.ID_curso
        ORDER BY RAND()
        LIMIT 4";

$stmt = $pdo->query($sql);
$cursos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Ajustar imagen
foreach ($cursos as &$curso) {
    if (!empty($curso['Imagen_curso'])) {
        $curso['Imagen_curso'] = 'uploads/' . $curso['Imagen_curso'];
    } else {
        $curso['Imagen_curso'] = 'imagenes/default-course.jpg';
    }
}

echo json_encode(['cursos' => $cursos]);
?>