<?php
require_once "../../api/db.php";

if (!isset($_GET['id'])) {
    header("Location: ../index.php");
    exit;
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM carrusel WHERE id = ?");
$stmt->execute([$id]);
$pelicula = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$pelicula) {
    header("Location: ../index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Procesar el formulario de edición
    $titulo = $_POST['titulo'] ?? '';

    // Procesar la imagen si se subió una nueva
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $imagen_nombre = basename($_FILES['imagen']['name']);
        $temp_name = $_FILES['imagen']['tmp_name'];
        
        // Mover el archivo a la ubicación deseada
        $upload_dir = '../../imagenes/carrusel/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        $imagen_ruta = $upload_dir . $imagen_nombre;
        move_uploaded_file($temp_name, $imagen_ruta);
        
        // Para acceder desde la web
        $imagen_ruta = '/cineapp/imagenes/carrusel/' . $imagen_nombre;

        // Actualizar con nueva imagen
        $stmt = $pdo->prepare("UPDATE carrusel SET titulo = ?, imagen_ruta = ? WHERE id = ?");
        $stmt->execute([$titulo, $imagen_ruta, $id]);
    } else {
        // Actualizar sin cambiar la imagen
        $stmt = $pdo->prepare("UPDATE carrusel SET titulo = ? WHERE id = ?");
        $stmt->execute([$titulo, $id]);
    }

    header("Location: ../index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Carrusel - Cineverse Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --primary: #6a11cb;
            --secondary: #2575fc;
            --dark: #1a1a2e;
            --light: #f8f9fa;
            --success: #28a745;
            --danger: #dc3545;
            --warning: #ffc107;
            --info: #17a2b8;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            color: #333;
        }
        
        .container {
            max-width: 800px;
            margin: 30px auto;
            padding: 20px;
        }
        
        .card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        
        h1 {
            color: var(--primary);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .btn {
            padding: 8px 15px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            text-decoration: none;
        }
        
        .btn-primary {
            background: var(--primary);
            color: white;
        }
        
        .btn-primary:hover {
            background: #5a0cb0;
            transform: translateY(-2px);
        }
        
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        
        .btn-secondary:hover {
            background: #5a6268;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
        }
        
        input[type="text"],
        input[type="file"],
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        
        .current-image {
            margin-top: 10px;
        }
        
        .current-image img {
            max-width: 100%;
            max-height: 200px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        
        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <h1><i class="fas fa-edit"></i> Editar Carrusel</h1>
            
            <a href="../index.php" class="btn btn-secondary mb-3">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
            
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="titulo">Título</label>
                    <input type="text" id="titulo" name="titulo" value="<?= htmlspecialchars($pelicula['titulo']) ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="imagen">Nueva Imagen (dejar en blanco para mantener la actual)</label>
                    
                    <?php if ($pelicula['imagen_ruta']): ?>
                        <div class="current-image">
                            <p>Imagen actual:</p>
                            <img src="<?= htmlspecialchars($pelicula['imagen_ruta']) ?>" alt="Imagen actual">
                        </div>
                    <?php endif; ?>
                    
                    <input type="file" id="imagen" name="imagen" accept="image/*">
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>