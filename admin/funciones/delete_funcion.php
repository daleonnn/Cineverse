<?php
require_once "../../api/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_funcion = $_POST["id_funcion"];
    $stmt = $pdo->prepare("DELETE FROM funciones WHERE id_funcion = ?");
    
    if ($stmt->execute([$id_funcion])) {
        header("Location: ../../admin/index.php");
        exit();
    } else {
        echo "Error al eliminar la función.";
    }
}

// Obtener información de la función para mostrar
$id_funcion = $_GET['id'] ?? null;
$stmt = $pdo->prepare("SELECT funciones.*, peliculas.titulo as pelicula, salas.nombre as sala 
                      FROM funciones 
                      JOIN peliculas ON funciones.id_pelicula = peliculas.id_pelicula
                      JOIN salas ON funciones.id_sala = salas.id_sala
                      WHERE id_funcion = ?");
$stmt->execute([$id_funcion]);
$funcion = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Función - CineAdmin</title>
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
        
        .funcion-info {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            border-left: 4px solid var(--danger);
            text-align: left;
        }
        
        .funcion-detail {
            margin-bottom: 10px;
            display: flex;
            gap: 10px;
        }
        
        .funcion-detail strong {
            min-width: 100px;
            display: inline-block;
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
        <h2><i class="fas fa-exclamation-triangle"></i> Eliminar Función</h2>
        
        <div class="funcion-info">
            <div class="funcion-detail"><strong>Película:</strong> <?= $funcion['pelicula'] ?></div>
            <div class="funcion-detail"><strong>Sala:</strong> <?= $funcion['sala'] ?></div>
            <div class="funcion-detail"><strong>Fecha:</strong> <?= date('d/m/Y H:i', strtotime($funcion['fecha_hora'])) ?></div>
            <div class="funcion-detail"><strong>Precio:</strong> $<?= number_format($funcion['precio'], 0, ',', '.') ?></div>
        </div>
        
        <div class="warning-message">
            <i class="fas fa-exclamation-circle"></i>
            Esta acción no se puede deshacer
        </div>
        
        <form method="POST">
            <input type="hidden" name="id_funcion" value="<?= $funcion['id_funcion'] ?>">
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