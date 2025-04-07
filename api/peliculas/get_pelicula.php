<?php
require_once "../db.php";

header('Content-Type: application/json');

if (!isset($_GET["id_pelicula"])) {
    echo json_encode(["status" => "error", "message" => "ID de película requerido"]);
    exit;
}

$id_pelicula = intval($_GET["id_pelicula"]);

try {
    $stmt = $pdo->prepare("SELECT 
        id_pelicula, titulo, descripcion, duracion, genero, clasificacion, 
        imagen_nombre, titulo_original, edad_recomendada, sinopsis, trailer_url,
        DATE_FORMAT(fecha_estreno, '%Y-%m-%d') as fecha_estreno,
        DATE_FORMAT(fecha_creacion, '%Y-%m-%d %H:%i:%s') as fecha_creacion,
        DATE_FORMAT(fecha_actualizacion, '%Y-%m-%d %H:%i:%s') as fecha_actualizacion
        FROM peliculas WHERE id_pelicula = ?");
    $stmt->execute([$id_pelicula]);
    $pelicula = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($pelicula) {
        // Transformar campos
        $pelicula['duracion'] = intval($pelicula['duracion']);
        
        if (!empty($pelicula['imagen_nombre'])) {
            $pelicula['imagen_url'] = 'https://' . $_SERVER['HTTP_HOST'] . '/assets/img/peliculas/' . $pelicula['imagen_nombre'];
        } else {
            $pelicula['imagen_url'] = '';
        }
        
        // Opcional: eliminar campo interno
        unset($pelicula['imagen_nombre']);
        
        echo json_encode([
            "status" => "success",
            "data" => $pelicula
        ]);
    } else {
        echo json_encode(["status" => "error", "message" => "Película no encontrada"]);
    }
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => "Error en la base de datos: " . $e->getMessage()]);
}
?>