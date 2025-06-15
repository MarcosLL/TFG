<?php
// Conexión a la base de datos
$host = 'localhost';
$db = 'tfg';
$user = 'root';
$pass = ''; // Cambia si tienes contraseña

$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $user, $pass);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Error al conectar a la base de datos']);
    exit;
}

// Parámetros desde la URL
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$porPagina = isset($_GET['porPagina']) ? (int)$_GET['porPagina'] : 4;
$offset = ($pagina - 1) * $porPagina;

$categoria = $_GET['categoria'] ?? '';
$search = $_GET['search'] ?? '';
$precio = $_GET['precio'] ?? '';
$ubicacion = $_GET['ubicacion'] ?? '';

// Construir consulta principal para obtener cursos
$sql = "SELECT c.ID_curso, c.Titulo, c.Descripcion, c.Precio, cat.Nombre AS tema, c.Imagen_curso
        FROM Cursos c
        JOIN Categorias cat ON c.ID_cat = cat.ID_cat
        JOIN Publicaciones p ON c.ID_curso = p.ID_curso
        JOIN Ubicaciones u ON p.ID_ubicacion = u.ID_ubi
        WHERE 1 = 1";

$parametros = [];

// Filtros dinámicos para la consulta principal
if ($categoria !== '') {
    $sql .= " AND cat.Nombre = :categoria";
    $parametros[':categoria'] = $categoria;
}

if ($search !== '') {
    $sql .= " AND c.Titulo LIKE :search";
    $parametros[':search'] = "%$search%";
}

if ($precio !== '') {
    if ($precio === '200+') {
        $sql .= " AND c.Precio > 200";
    } else {
        // Si precio tiene formato "min-max"
        $partes = explode('-', $precio);
        if (count($partes) == 2) {
            $sql .= " AND c.Precio BETWEEN :minPrecio AND :maxPrecio";
            $parametros[':minPrecio'] = $partes[0];
            $parametros[':maxPrecio'] = $partes[1];
        }
    }
}

if ($ubicacion !== '') {
    $sql .= " AND (u.Ciudad LIKE :ubicacion OR u.Pais LIKE :ubicacion)";
    $parametros[':ubicacion'] = "%$ubicacion%";
}

// --------- Consulta para total de cursos ---------
// Construimos la consulta de conteo separada para evitar problemas
$sqlCount = "SELECT COUNT(*) as total
             FROM Cursos c
             JOIN Categorias cat ON c.ID_cat = cat.ID_cat
             JOIN Publicaciones p ON c.ID_curso = p.ID_curso
             JOIN Ubicaciones u ON p.ID_ubicacion = u.ID_ubi
             WHERE 1=1";

// Aplicamos los mismos filtros
if ($categoria !== '') {
    $sqlCount .= " AND cat.Nombre = :categoria";
}
if ($search !== '') {
    $sqlCount .= " AND c.Titulo LIKE :search";
}
if ($precio !== '') {
    if ($precio === '200+') {
        $sqlCount .= " AND c.Precio > 200";
    } else {
        if (count($partes) == 2) {
            $sqlCount .= " AND c.Precio BETWEEN :minPrecio AND :maxPrecio";
        }
    }
}
if ($ubicacion !== '') {
    $sqlCount .= " AND (u.Ciudad LIKE :ubicacion OR u.Pais LIKE :ubicacion)";
}

$stmtTotal = $pdo->prepare($sqlCount);

// Vincular los parámetros para la consulta total
foreach ($parametros as $clave => $valor) {
    $stmtTotal->bindValue($clave, $valor);
}

$stmtTotal->execute();

$totalCursos = $stmtTotal->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

// --------- Añadir paginación a consulta principal ---------
$sql .= " LIMIT :offset, :porPagina";

$stmt = $pdo->prepare($sql);

// Vincular parámetros para la consulta principal
foreach ($parametros as $clave => $valor) {
    $stmt->bindValue($clave, $valor);
}

// Parámetros para paginación (tipos enteros)
$stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
$stmt->bindValue(':porPagina', (int)$porPagina, PDO::PARAM_INT);

$stmt->execute();

$cursos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Ajustar rutas de imagen
foreach ($cursos as &$curso) {
    if (!empty($curso['Imagen_curso'])) {
        // Añadir ruta relativa para que la imagen se pueda cargar
        $curso['Imagen_curso'] = 'uploads/' . $curso['Imagen_curso'];
    } else {
        $curso['Imagen_curso'] = 'imagenes/default-course.jpg';  // Ruta a imagen por defecto
    }
}

// Devolver JSON
echo json_encode([
    'cursos' => $cursos,
    'total' => $totalCursos
]);
?>
