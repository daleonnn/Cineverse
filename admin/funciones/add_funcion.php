<?php
require_once "../../api/db.php";
$stmt_peliculas = $pdo->query("SELECT id_pelicula, titulo FROM peliculas");
$peliculas = $stmt_peliculas->fetchAll(PDO::FETCH_ASSOC);

$stmt_salas = $pdo->query("SELECT id_sala, nombre FROM salas");
$salas = $stmt_salas->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Función - CineAdmin</title>
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
        
        h2 {
            color: var(--primary);
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        h2 i {
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
        
        select, input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s;
        }
        
        select:focus, input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(106, 17, 203, 0.1);
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
        <h2><i class="fas fa-calendar-plus"></i> Añadir Nueva Función</h2>
        
        <form action="../../api/funciones/add_funcion.php" method="POST">
            <div class="form-group">
                <label for="id_pelicula">Película</label>
                <select name="id_pelicula" id="id_pelicula" required>
                    <?php foreach ($peliculas as $pelicula): ?>
                        <option value="<?= $pelicula['id_pelicula'] ?>"><?= $pelicula['titulo'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group price-input">
                <label for="precio">Precio</label>
                <input type="number" name="precio" id="precio" min="0" step="1000" value="10000" required>
            </div>
            
            <div class="form-group">
                <label for="fecha_hora">Fecha y Hora</label>
                <input type="datetime-local" name="fecha_hora" id="fecha_hora" required>
            </div>
            
            <div class="form-group">
                <label for="id_sala">Sala</label>
                <select name="id_sala" id="id_sala" required>
                    <?php foreach ($salas as $sala): ?>
                        <option value="<?= $sala['id_sala'] ?>"><?= $sala['nombre'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="btn-group">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-plus-circle"></i> Crear Función
                </button>
                <a href="../../admin/index.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver al Panel
                </a>
            </div>
        </form>
    </div>
</body>
</html>