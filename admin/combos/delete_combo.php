<?php
require_once "../../api/db.php";

if (isset($_GET["id"])) {
    $id_combo = $_GET["id"];
    $stmt = $pdo->prepare("SELECT * FROM combos WHERE id_combo = ?");
    $stmt->execute([$id_combo]);
    $combo = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$combo) {
        die("Combo no encontrado.");
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_combo = $_POST["id"];
    $stmt = $pdo->prepare("DELETE FROM combos WHERE id_combo = ?");
    
    if ($stmt->execute([$id_combo])) {
        header("Location: ../../admin/index.php");
        exit();
    } else {
        echo "Error al eliminar el combo.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Combo - CineAdmin</title>
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
        
        .combo-info {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            border-left: 4px solid var(--danger);
        }
        
        .combo-name {
            font-size: 1.5rem;
            color: var(--dark);
            margin-bottom: 10px;
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
        <h2><i class="fas fa-exclamation-triangle"></i> Eliminar Combo</h2>
        
        <div class="combo-info">
            <div class="combo-name"><?= $combo['nombre'] ?></div>
            <div>Precio: $<?= number_format($combo['precio'], 0, ',', '.') ?></div>
        </div>
        
        <div class="warning-message">
            <i class="fas fa-exclamation-circle"></i>
            Esta acción no se puede deshacer
        </div>
        
        <form method="POST">
            <input type="hidden" name="id" value="<?= $combo['id_combo'] ?>">
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