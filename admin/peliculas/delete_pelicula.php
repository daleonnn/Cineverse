<?php
require_once "../../api/db.php";

if (isset($_GET["id"])) {
    $id_pelicula = $_GET["id"];
    
    // Obtener información de la película para mostrar
    $stmt = $pdo->prepare("SELECT * FROM peliculas WHERE id_pelicula = ?");
    $stmt->execute([$id_pelicula]);
    $pelicula = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$pelicula) {
        die("Película no encontrada");
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Eliminar la imagen si existe
        if (!empty($pelicula['imagen_nombre'])) {
            $ruta_imagen = "../../assets/img/peliculas/" . $pelicula['imagen_nombre'];
            if (file_exists($ruta_imagen)) {
                unlink($ruta_imagen);
            }
        }
        
        // Eliminar la película de la base de datos
        $stmt = $pdo->prepare("DELETE FROM peliculas WHERE id_pelicula = ?");
        if ($stmt->execute([$id_pelicula])) {
            header("Location: ../../admin/index.php");
            exit();
        } else {
            echo "Error al eliminar la película.";
        }
    }
} else {
    header("Location: ../../admin/index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Película - CineAdmin</title>
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
            max-width: 600px;
            margin: 50px auto;
            padding: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        
        h2 {
            color: var(--danger);
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
        }
        
        .pelicula-info {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            border-left: 4px solid var(--danger);
            text-align: left;
        }
        
        .pelicula-header {
            font-size: 1.3rem;
            margin-bottom: 15px;
            color: var(--dark);
        }
        
        .pelicula-detail {
            margin-bottom: 10px;
            display: flex;
            gap: 10px;
        }
        
        .pelicula-detail strong {
            min-width: 120px;
            display: inline-block;
        }
        
        .pelicula-image {
            max-width: 200px;
            border-radius: 8px;
            margin: 15px auto;
            display: block;
        }
        
        .warning-message {
            color: var(--danger);
            font-weight: 500;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
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
        
        .btn-danger {
            background-color: var(--danger);
            color: white;
        }
        
        .btn-danger:hover {
            background-color: #c82333;
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
            justify-content: center;
        }
        
        form {
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2><i class="fas fa-exclamation-triangle"></i> Eliminar Película</h2>
        
        <div class="pelicula-info">
            <div class="pelicula-header"><?= htmlspecialchars($pelicula['titulo']) ?></div>
            
            <?php if (!empty($pelicula['imagen_nombre'])): ?>
                <img src="../../assets/img/peliculas/<?= htmlspecialchars($pelicula['imagen_nombre']) ?>" alt="<?= htmlspecialchars($pelicula['titulo']) ?>" class="pelicula-image">
            <?php endif; ?>
            
            <div class="pelicula-detail"><strong>Título Original:</strong> <?= htmlspecialchars($pelicula['titulo_original']) ?></div>
            <div class="pelicula-detail"><strong>Duración:</strong> <?= htmlspecialchars($pelicula['duracion']) ?> minutos</div>
            <div class="pelicula-detail"><strong>Género:</strong> <?= htmlspecialchars($pelicula['genero']) ?></div>
            <div class="pelicula-detail"><strong>Clasificación:</strong> <?= htmlspecialchars($pelicula['clasificacion']) ?></div>
            <div class="pelicula-detail"><strong>Fecha Estreno:</strong> <?= htmlspecialchars($pelicula['fecha_estreno']) ?></div>
        </div>
        
        <div class="warning-message">
            <i class="fas fa-exclamation-circle"></i>
            Esta acción eliminará permanentemente la película y no se puede deshacer
        </div>
        
        <form method="POST">
            <div class="btn-group">
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash"></i> Confirmar Eliminación
                </button>
                <a href="../../admin/index.php" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</body>
</html>