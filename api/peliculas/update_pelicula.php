<?php
require_once "../db.php";

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["id_pelicula"])) {
        echo json_encode(["status" => "error", "message" => "ID de película requerido"]);
        exit;
    }

    $id_pelicula = intval($_POST["id_pelicula"]);
    
    // Verificar si existe la película
    $stmt = $pdo->prepare("SELECT imagen_nombre FROM peliculas WHERE id_pelicula = ?");
    $stmt->execute([$id_pelicula]);
    $pelicula = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$pelicula) {
        echo json_encode(["status" => "error", "message" => "Película no encontrada"]);
        exit;
    }

    // Procesar datos
    $data = [
        'titulo' => trim($_POST["titulo"] ?? ''),
        'descripcion' => trim($_POST["descripcion"] ?? ''),
        'duracion' => intval($_POST["duracion"] ?? 0),
        'genero' => trim($_POST["genero"] ?? ''),
        'clasificacion' => trim($_POST["clasificacion"] ?? ''),
        'titulo_original' => trim($_POST["titulo_original"] ?? ''),
        'edad_recomendada' => trim($_POST["edad_recomendada"] ?? ''),
        'sinopsis' => trim($_POST["sinopsis"] ?? ''),
        'trailer_url' => trim($_POST["trailer_url"] ?? ''),
        'fecha_estreno' => $_POST["fecha_estreno"] ?? ''
    ];

    // Manejo de imagen
    $imagen_nombre = $pelicula['imagen_nombre'];
    $nueva_imagen = false;
    
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $extension = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (!in_array($extension, $allowed)) {
            echo json_encode(["status" => "error", "message" => "Formato de imagen no permitido"]);
            exit;
        }

        // Eliminar imagen anterior si existe
        if (!empty($imagen_nombre)) {
            $ruta_anterior = "../assets/img/peliculas/" . $imagen_nombre;
            if (file_exists($ruta_anterior)) {
                @unlink($ruta_anterior);
            }
        }

        // Subir nueva imagen
        $imagen_nombre = uniqid('pel_') . '.' . $extension;
        $ruta_destino = "../assets/img/peliculas/" . $imagen_nombre;
        
        if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_destino)) {
            echo json_encode(["status" => "error", "message" => "Error al subir la nueva imagen"]);
            exit;
        }
        
        $nueva_imagen = true;
    }

    try {
        $stmt = $pdo->prepare("UPDATE peliculas SET 
            titulo = ?, descripcion = ?, duracion = ?, genero = ?, clasificacion = ?, 
            imagen_nombre = ?, titulo_original = ?, edad_recomendada = ?, 
            sinopsis = ?, trailer_url = ?, fecha_estreno = ?, fecha_actualizacion = CURRENT_TIMESTAMP 
            WHERE id_pelicula = ?");
        
        $stmt->execute([
            $data['titulo'], $data['descripcion'], $data['duracion'], $data['genero'], 
            $data['clasificacion'], $imagen_nombre, $data['titulo_original'], 
            $data['edad_recomendada'], $data['sinopsis'], $data['trailer_url'], 
            $data['fecha_estreno'], $id_pelicula
        ]);

        echo json_encode([
            "status" => "success", 
            "message" => "Película actualizada",
            "updated_fields" => array_keys(array_filter($data)),
            "new_image" => $nueva_imagen
        ]);
    } catch (PDOException $e) {
        // Limpieza en caso de error con nueva imagen
        if ($nueva_imagen) {
            @unlink("../assets/img/peliculas/" . $imagen_nombre);
        }
        echo json_encode(["status" => "error", "message" => "Error en la base de datos: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Método no permitido"]);
}
?>