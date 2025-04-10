<?php
require_once "../../api/db.php";

$id_pelicula = $_GET["id"];
$stmt = $pdo->prepare("SELECT * FROM peliculas WHERE id_pelicula = ?");
$stmt->execute([$id_pelicula]);
$pelicula = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$pelicula) {
    die("Película no encontrada");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = $_POST["titulo"];
    $descripcion = $_POST["descripcion"];
    $duracion = $_POST["duracion"];
    $genero = $_POST["genero"];
    $clasificacion = $_POST["clasificacion"];
    $titulo_original = $_POST["titulo_original"];
    $edad_recomendada = $_POST["edad_recomendada"];
    $sinopsis = $_POST["sinopsis"];
    $trailer_url = $_POST["trailer_url"];
    $fecha_estreno = $_POST["fecha_estreno"];
    
    // Mantener la imagen actual si no se sube una nueva
    $imagen_nombre = $pelicula['imagen_nombre'];
    
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        // Eliminar la imagen anterior si existe
        if (!empty($imagen_nombre)) {
            $ruta_anterior = "../../assets/img/peliculas/" . $imagen_nombre;
            if (file_exists($ruta_anterior)) {
                unlink($ruta_anterior);
            }
        }
        
        // Subir la nueva imagen
        $extension = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
        $imagen_nombre = uniqid() . '.' . $extension;
        $ruta_destino = "../../assets/img/peliculas/" . $imagen_nombre;
        move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_destino);
    }

    $stmt = $pdo->prepare("UPDATE peliculas SET titulo = ?, descripcion = ?, duracion = ?, genero = ?, clasificacion = ?, imagen_nombre = ?, titulo_original = ?, edad_recomendada = ?, sinopsis = ?, trailer_url = ?, fecha_estreno = ? WHERE id_pelicula = ?");
    $stmt->execute([$titulo, $descripcion, $duracion, $genero, $clasificacion, $imagen_nombre, $titulo_original, $edad_recomendada, $sinopsis, $trailer_url, $fecha_estreno, $id_pelicula]);

    header("Location: ../../admin/index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Película - CineAdmin</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --primary: #6a11cb;
            --secondary: #2575fc;
            --success: #28a745;
            --danger: #dc3545;
            --light: #f8f9fa;
            --dark: #343a40;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        
        body {
            background-color: #f5f7fa;
            color: #333;
            line-height: 1.6;
        }
        
        .container {
            max-width: 1000px;
            margin: 50px auto;
            padding: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        h1 {
            color: var(--primary);
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        h1 i {
            font-size: 1.8rem;
        }
        
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        label {
            display: block;
            margin-bottom: 10px;
            font-weight: 500;
            color: var(--dark);
        }
        
        input[type="text"],
        input[type="number"],
        input[type="date"],
        input[type="url"],
        textarea,
        select {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s;
        }
        
        input:focus,
        textarea:focus,
        select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(106, 17, 203, 0.1);
        }
        
        textarea {
            min-height: 120px;
            resize: vertical;
        }
        
        .image-section {
            margin-bottom: 25px;
        }
        
        .current-image {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-top: 15px;
        }
        
        .current-image img {
            max-width: 150px;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        }
        
        .file-upload {
            position: relative;
            margin-top: 15px;
        }
        
        .file-upload-input {
            width: 100%;
            padding: 12px;
            border: 1px dashed #ccc;
            border-radius: 8px;
            text-align: center;
            transition: all 0.3s;
        }
        
        .file-upload-input:hover {
            border-color: var(--primary);
            background-color: rgba(106, 17, 203, 0.05);
        }
        
        .file-upload-preview {
            margin-top: 15px;
            max-width: 200px;
            border-radius: 8px;
            display: none;
        }
        
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
        }
        
        .btn-primary {
            background-color: var(--primary);
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #5a0cb0;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        
        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }
        
        .btn-secondary:hover {
            background-color: #5a6268;
        }
        
        .btn-group {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            grid-column: span 2;
        }
        
        .no-image {
            color: #6c757d;
            font-style: italic;
        }
        
        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .btn-group {
                grid-column: span 1;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><i class="fas fa-edit"></i> Editar Película: <?= htmlspecialchars($pelicula['titulo']) ?></h1>
        
        <form method="POST" enctype="multipart/form-data" class="form-grid">
            <div class="form-group">
                <label for="titulo">Título</label>
                <input type="text" id="titulo" name="titulo" value="<?= htmlspecialchars($pelicula['titulo']) ?>" required>
            </div>
            
            <div class="form-group">
                <label for="titulo_original">Título Original</label>
                <input type="text" id="titulo_original" name="titulo_original" value="<?= htmlspecialchars($pelicula['titulo_original']) ?>">
            </div>
            
            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <textarea id="descripcion" name="descripcion" required><?= htmlspecialchars($pelicula['descripcion']) ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="sinopsis">Sinopsis</label>
                <textarea id="sinopsis" name="sinopsis"><?= htmlspecialchars($pelicula['sinopsis']) ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="duracion">Duración (minutos)</label>
                <input type="number" id="duracion" name="duracion" min="1" value="<?= htmlspecialchars($pelicula['duracion']) ?>" required>
            </div>
            
            <div class="form-group">
                <label for="genero">Género</label>
                <input type="text" id="genero" name="genero" value="<?= htmlspecialchars($pelicula['genero']) ?>">
            </div>
            
            <div class="form-group">
                <label for="clasificacion">Clasificación</label>
                <input type="text" id="clasificacion" name="clasificacion" value="<?= htmlspecialchars($pelicula['clasificacion']) ?>">
            </div>
            
            <div class="form-group">
                <label for="edad_recomendada">Edad Recomendada</label>
                <input type="text" id="edad_recomendada" name="edad_recomendada" value="<?= htmlspecialchars($pelicula['edad_recomendada']) ?>">
            </div>
            
            <div class="form-group">
                <label for="trailer_url">URL del Trailer</label>
                <input type="url" id="trailer_url" name="trailer_url" value="<?= htmlspecialchars($pelicula['trailer_url']) ?>" placeholder="https://www.youtube.com/watch?v=...">
            </div>
            
            <div class="form-group">
                <label for="fecha_estreno">Fecha de Estreno</label>
                <input type="date" id="fecha_estreno" name="fecha_estreno" value="<?= htmlspecialchars($pelicula['fecha_estreno']) ?>">
            </div>
            
            <div class="form-group image-section">
                <label>Imagen Actual</label>
                <div class="current-image">
                    <?php if (!empty($pelicula['imagen_nombre'])): ?>
                        <img src="../../assets/img/peliculas/<?= htmlspecialchars($pelicula['imagen_nombre']) ?>" alt="Imagen actual de la película">
                        <div>
                            <small><?= htmlspecialchars($pelicula['imagen_nombre']) ?></small>
                        </div>
                    <?php else: ?>
                        <p class="no-image">No hay imagen actualmente</p>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="form-group">
                <label>Cambiar Imagen</label>
                <div class="file-upload">
                    <input type="file" class="file-upload-input" id="imagen" name="imagen" accept="image/*" onchange="previewImage(this)">
                    <small>Dejar en blanco para mantener la imagen actual</small>
                    <img id="imagePreview" class="file-upload-preview" src="#" alt="Vista previa de la nueva imagen">
                </div>
            </div>
            
            <div class="btn-group">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Guardar Cambios
                </button>
                <a href="../../admin/index.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver al Panel
                </a>
            </div>
        </form>
    </div>

    <script>
        function previewImage(input) {
            const preview = document.getElementById('imagePreview');
            const file = input.files[0];
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
            
            if (file) {
                reader.readAsDataURL(file);
            }
        }
    </script>
</body>
</html>