<?php
require_once "../../api/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_sala = $_POST["id_sala"];
    $numero = $_POST["numero"];
    $estado = $_POST["estado"];

    $stmt = $pdo->prepare("INSERT INTO asientos (id_sala, numero, estado) VALUES (?, ?, ?)");
    if ($stmt->execute([$id_sala, $numero, $estado])) {
        header("Location: ../../admin/index.php"); // Corregido esta línea
        exit;
    } else {
        echo "Error al agregar el asiento.";
    }
}

$salas = $pdo->query("SELECT * FROM salas")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Asiento - CineAdmin</title>
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
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        h2 i {
            font-size: 1.5em;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--dark);
        }
        
        select, input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            transition: border 0.3s;
        }
        
        select:focus, input:focus {
            outline: none;
            border-color: var(--primary);
        }
        
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
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
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
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
        
        .status-option {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 0;
        }
        
        .status-available {
            color: var(--success);
        }
        
        .status-occupied {
            color: var(--danger);
        }
    </style>
</head>
<body>
    <div class="container">
        <h2><i class="fas fa-chair"></i> Agregar Nuevo Asiento</h2>
        
        <form method="POST">
            <div class="form-group">
                <label for="id_sala">Sala</label>
                <select name="id_sala" id="id_sala" required>
                    <?php foreach ($salas as $sala): ?>
                        <option value="<?= $sala['id_sala'] ?>"><?= $sala['nombre'] ?> (Capacidad: <?= $sala['capacidad'] ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="numero">Número de Asiento</label>
                <input type="number" name="numero" id="numero" min="1" required>
            </div>
            
            <div class="form-group">
                <label for="estado">Estado del Asiento</label>
                <select name="estado" id="estado" required>
                    <option value="disponible">
                        <span class="status-option status-available">
                            <i class="fas fa-check-circle"></i> Disponible
                        </span>
                    </option>
                    <option value="ocupado">
                        <span class="status-option status-occupied">
                            <i class="fas fa-times-circle"></i> Ocupado
                        </span>
                    </option>
                </select>
            </div>
            
            <div class="btn-group">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Guardar Asiento
                </button>
                <a href="../../admin/index.php" class="btn btn-secondary"> <!-- Corregido este enlace -->
                    <i class="fas fa-arrow-left"></i> Volver al Panel
                </a>
            </div>
        </form>
    </div>
</body>
</html>