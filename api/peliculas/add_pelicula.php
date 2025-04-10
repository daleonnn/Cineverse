<?php
require_once "../db.php";

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validar campos requeridos
    $required = ['titulo', 'fecha_estreno'];
    foreach ($required as $field) {
        if (empty($_POST[$field])) {
            echo json_encode(["status" => "error", "message" => "El campo $field es requerido"]);
            exit;
        }
    }

    // Procesar datos
    $data = [
        'titulo' => trim($_POST["titulo"]),
        'descripcion' => trim($_POST["descripcion"] ?? ''),
        'duracion' => intval($_POST["duracion"] ?? 0),
        'genero' => trim($_POST["genero"] ?? ''),
        'clasificacion' => trim($_POST["clasificacion"] ?? ''),
        'titulo_original' => trim($_POST["titulo_original"] ?? ''),
        'edad_recomendada' => trim($_POST["edad_recomendada"] ?? ''), // Corregido: edad_recomendada
        'sinopsis' => trim($_POST["sinopsis"] ?? ''),
        'trailer_url' => trim($_POST["trailer_url"] ?? ''),
        'fecha_estreno' => $_POST["fecha_estreno"]
    ];

    // Verificar y crear directorio de imágenes si no existe
    $upload_dir = "../assets/img/peliculas/";
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    // Manejo de imagen
    $imagen_nombre = '';
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $extension = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (!in_array($extension, $allowed)) {
            echo json_encode(["status" => "error", "message" => "Formato de imagen no permitido"]);
            exit;
        }

        $imagen_nombre = uniqid('pel_') . '.' . $extension;
        $ruta_destino = $upload_dir . $imagen_nombre;
        
        if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_destino)) {
            echo json_encode(["status" => "error", "message" => "Error al subir la imagen. Verifica los permisos del directorio."]);
            exit;
        }
    } else {
        echo json_encode(["status" => "error", "message" => "La imagen es requerida", "upload_error" => $_FILES['imagen']['error'] ?? 'No se subió archivo']);
        exit;
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO peliculas 
            (titulo, descripcion, duracion, genero, clasificacion, imagen_nombre, 
             titulo_original, edad_recomendada, sinopsis, trailer_url, fecha_estreno) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        $stmt->execute([
            $data['titulo'], 
            $data['descripcion'], 
            $data['duracion'], 
            $data['genero'], 
            $data['clasificacion'], 
            $imagen_nombre, 
            $data['titulo_original'], 
            $data['edad_recomendada'], // Asegúrate que coincide con el nombre de la columna en la BD
            $data['sinopsis'], 
            $data['trailer_url'], 
            $data['fecha_estreno']
        ]);

        echo json_encode([
            "status" => "success", 
            "message" => "Película agregada correctamente", 
            "id" => $pdo->lastInsertId(),
            "data" => $data,
            "image" => $imagen_nombre
        ]);
    } catch (PDOException $e) {
        // Limpieza en caso de error
        if (!empty($imagen_nombre) && file_exists($upload_dir . $imagen_nombre)) {
            @unlink($upload_dir . $imagen_nombre);
        }
        echo json_encode([
            "status" => "error", 
            "message" => "Error en la base de datos",
            "error_details" => $e->getMessage(),
            "error_code" => $e->getCode()
        ]);
    }
} else {
    echo json_encode([
        "status" => "error", 
        "message" => "Método no permitido",
        "allowed_method" => "POST"
    ]);
}
?>