<?php
require_once "../../api/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $descripcion = $_POST["descripcion"];
    $precio = $_POST["precio"];
    
    // Procesar imagen
    $imagen_nombre = '';
    if (isset($_FILES["imagen"]) && $_FILES["imagen"]["error"] == 0) {
        $extension = pathinfo($_FILES["imagen"]["name"], PATHINFO_EXTENSION);
        $imagen_nombre = uniqid() . '.' . $extension;
        move_uploaded_file($_FILES["imagen"]["tmp_name"], "../../assets/img/combos/" . $imagen_nombre);
    }
    
    $stmt = $pdo->prepare("INSERT INTO combos (nombre, descripcion, precio, imagen_nombre) VALUES (?, ?, ?, ?)");
    $stmt->execute([$nombre, $descripcion, $precio, $imagen_nombre]);
    
    header("Location: ../../admin/index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Combo - CineAdmin</title>
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
            max-width: 800px;
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
        textarea,
        select {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s;
        }
        
        input[type="text"]:focus,
        input[type="number"]:focus,
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
        
        .file-upload {
            position: relative;
            margin-bottom: 25px;
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
        }
        
        .price-input {
            position: relative;
        }
        
        .price-input::before {
            content: '$';
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            font-weight: 500;
            color: var(--dark);
        }
        
        .price-input input {
            padding-left: 30px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><i class="fas fa-concierge-bell"></i> Añadir Nuevo Combo</h1>
        
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nombre">Nombre del Combo</label>
                <input type="text" id="nombre" name="nombre" required placeholder="Ej: Combo Familiar">
            </div>
            
            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <textarea id="descripcion" name="descripcion" required placeholder="Incluye: 2 hamburguesas, 2 gaseosas, 1 porción de papas fritas..."></textarea>
            </div>
            
            <div class="form-group price-input">
                <label for="precio">Precio</label>
                <input type="number" id="precio" name="precio" min="0" step="100" required placeholder="Ej: 12500">
            </div>
            
            <div class="form-group">
                <label>Imagen del Combo</label>
                <div class="file-upload">
                    <input type="file" class="file-upload-input" id="imagen" name="imagen" accept="image/*" required onchange="previewImage(this)">
                    <img id="imagePreview" class="file-upload-preview" src="#" alt="Vista previa de la imagen">
                </div>
            </div>
            
            <div class="btn-group">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Guardar Combo
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