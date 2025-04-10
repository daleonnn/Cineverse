<?php
require_once "../db.php";

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["id_pelicula"])) {
        echo json_encode(["status" => "error", "message" => "ID de película requerido"]);
        exit;
    }

    $id_pelicula = intval($_POST["id_pelicula"]);
    
    try {
        $pdo->beginTransaction();
        
        // 1. Obtener información de la película
        $stmt = $pdo->prepare("SELECT imagen_nombre FROM peliculas WHERE id_pelicula = ? FOR UPDATE");
        $stmt->execute([$id_pelicula]);
        $pelicula = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$pelicula) {
            echo json_encode(["status" => "error", "message" => "Película no encontrada"]);
            exit;
        }
        
        // 2. Eliminar registros relacionados (si existen)
        // Ejemplo: $pdo->prepare("DELETE FROM programaciones WHERE id_pelicula = ?")->execute([$id_pelicula]);
        
        // 3. Eliminar la película
        $stmt = $pdo->prepare("DELETE FROM peliculas WHERE id_pelicula = ?");
        $stmt->execute([$id_pelicula]);
        
        // 4. Eliminar la imagen si existe
        if (!empty($pelicula['imagen_nombre'])) {
            $ruta_imagen = "../assets/img/peliculas/" . $pelicula['imagen_nombre'];
            if (file_exists($ruta_imagen)) {
                @unlink($ruta_imagen);
            }
        }
        
        $pdo->commit();
        
        echo json_encode([
            "status" => "success", 
            "message" => "Película eliminada",
            "deleted_id" => $id_pelicula,
            "image_deleted" => !empty($pelicula['imagen_nombre'])
        ]);
    } catch (PDOException $e) {
        $pdo->rollBack();
        echo json_encode(["status" => "error", "message" => "Error al eliminar película: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Método no permitido"]);
}
?>