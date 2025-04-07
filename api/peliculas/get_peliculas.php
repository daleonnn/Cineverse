<?php
require_once "../db.php";

header('Content-Type: application/json');

try {
    // Opciones de paginación
    $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
    $per_page = isset($_GET['per_page']) ? min(max(1, intval($_GET['per_page'])), 100) : 10;
    $offset = ($page - 1) * $per_page;

    // Consulta base con conteo total
    $count_stmt = $pdo->query("SELECT COUNT(*) as total FROM peliculas");
    $total = $count_stmt->fetchColumn();
    
    // Consulta principal con paginación
    $stmt = $pdo->prepare("SELECT 
        id_pelicula, titulo, duracion, genero, clasificacion, imagen_nombre,
        titulo_original, DATE_FORMAT(fecha_estreno, '%Y-%m-%d') as fecha_estreno
        FROM peliculas 
        ORDER BY fecha_estreno DESC 
        LIMIT :limit OFFSET :offset");
    
    $stmt->bindValue(':limit', $per_page, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    
    $peliculas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Transformar datos
    $base_url = 'https://' . $_SERVER['HTTP_HOST'] . '/assets/img/peliculas/';
    
    foreach ($peliculas as &$pelicula) {
        $pelicula['duracion'] = intval($pelicula['duracion']);
        
        if (!empty($pelicula['imagen_nombre'])) {
            $pelicula['imagen_url'] = $base_url . $pelicula['imagen_nombre'];
        } else {
            $pelicula['imagen_url'] = $base_url . 'default.jpg';
        }
        
        // Opcional: eliminar campo interno
        unset($pelicula['imagen_nombre']);
    }

    echo json_encode([
        "status" => "success",
        "data" => $peliculas,
        "pagination" => [
            "total" => intval($total),
            "page" => $page,
            "per_page" => $per_page,
            "total_pages" => ceil($total / $per_page)
        ]
    ]);
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => "Error en la base de datos: " . $e->getMessage()]);
}
?>