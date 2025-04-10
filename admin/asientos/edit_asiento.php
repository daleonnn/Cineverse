<?php
require_once "../../api/db.php";

if (isset($_GET["id"])) {
    $id_asiento = $_GET["id"];
    $stmt = $pdo->prepare("SELECT * FROM asientos WHERE id_asiento = ?");
    $stmt->execute([$id_asiento]);
    $asiento = $stmt->fetch(PDO::FETCH_ASSOC);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $numero = $_POST["numero"];
    $estado = $_POST["estado"];

    $stmt = $pdo->prepare("UPDATE asientos SET numero = ?, estado = ? WHERE id_asiento = ?");
    $stmt->execute([$numero, $estado, $id_asiento]);

    header("Location: ../../admin/index.php"); // Corregido esta línea
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Asiento - CineAdmin</title>
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
        
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 14px;
            margin-left: 10px;
        }
        
        .status-available {
            background-color: rgba(40, 167, 69, 0.1);
            color: var(--success);
        }
        
        .status-occupied {
            background-color: rgba(220, 53, 69, 0.1);
            color: var(--danger);
        }
        
        .current-sala {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid var(--primary);
        }
    </style>
</head>
<body>
    <div class="container">
        <h2><i class="fas fa-edit"></i> Editar Asiento #<?= $asiento['numero'] ?></h2>
        
        <?php 
        // Obtener información de la sala para mostrar
        $stmt = $pdo->prepare("SELECT nombre FROM salas WHERE id_sala = ?");
        $stmt->execute([$asiento['id_sala']]);
        $sala = $stmt->fetch(PDO::FETCH_ASSOC);
        ?>
        
        <div class="current-sala">
            <strong>Sala Actual:</strong> <?= $sala['nombre'] ?>
        </div>
        
        <form method="POST">
            <div class="form-group">
                <label for="numero">Número de Asiento</label>
                <input type="number" name="numero" id="numero" value="<?= $asiento['numero'] ?>" min="1" required>
            </div>
            
            <div class="form-group">
                <label for="estado">Estado del Asiento</label>
                <select name="estado" id="estado" required>
                    <option value="disponible" <?= ($asiento['estado'] == "disponible") ? "selected" : "" ?>>
                        Disponible
                    </option>
                    <option value="ocupado" <?= ($asiento['estado'] == "ocupado") ? "selected" : "" ?>>
                        Ocupado
                    </option>
                </select>
                <span class="status-badge status-<?= $asiento['estado'] ?>">
                    <i class="fas <?= ($asiento['estado'] == "disponible") ? 'fa-check-circle' : 'fa-times-circle' ?>"></i>
                    Estado actual: <?= ucfirst($asiento['estado']) ?>
                </span>
            </div>
            
            <div class="btn-group">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Guardar Cambios
            </button>
            <a href="../../admin/index.php" class="btn btn-secondary"> <!-- Corregido este enlace -->
                <i class="fas fa-arrow-left"></i> Volver al Panel
            </a>
        </div>
        </form>
    </div>
</body>
</html>