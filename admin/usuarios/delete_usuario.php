<?php
require_once "../../api/db.php";

if (isset($_GET["id"])) {
    $id_usuario = $_GET["id"];
    
    // Obtener información del usuario para mostrar
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id_usuario = ?");
    $stmt->execute([$id_usuario]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        header("Location: ../../admin/index.php");
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Verificar si el usuario a eliminar es el admin principal
        if ($usuario['email'] === 'admin@cineapp.com') {
            die("No se puede eliminar el usuario administrador principal");
        }
        
        // Eliminar el usuario de la base de datos
        $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id_usuario = ?");
        if ($stmt->execute([$id_usuario])) {
            header("Location: ../../admin/index.php");
            exit();
        } else {
            echo "Error al eliminar el usuario.";
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
    <title>Eliminar Usuario - CineAdmin</title>
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
        
        .user-info {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            border-left: 4px solid var(--danger);
            text-align: left;
        }
        
        .user-name {
            font-size: 1.3rem;
            color: var(--dark);
            margin-bottom: 10px;
        }
        
        .user-detail {
            margin-bottom: 8px;
        }
        
        .user-detail strong {
            display: inline-block;
            min-width: 100px;
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
        
        .admin-warning {
            color: var(--danger);
            background-color: rgba(220, 53, 69, 0.1);
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
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
        <h2><i class="fas fa-user-times"></i> Eliminar Usuario</h2>
        
        <div class="user-info">
            <div class="user-name"><?= htmlspecialchars($usuario['nombre']) ?></div>
            <div class="user-detail"><strong>ID:</strong> <?= $usuario['id_usuario'] ?></div>
            <div class="user-detail"><strong>Email:</strong> <?= htmlspecialchars($usuario['email']) ?></div>
            <div class="user-detail"><strong>Registrado:</strong> <?= date('d/m/Y', strtotime($usuario['fecha_registro'])) ?></div>
            
            <?php if ($usuario['email'] === 'admin@cineapp.com'): ?>
                <div class="admin-warning">
                    <i class="fas fa-shield-alt"></i>
                    Este es el usuario administrador principal y no puede ser eliminado
                </div>
            <?php endif; ?>
        </div>
        
        <?php if ($usuario['email'] !== 'admin@cineapp.com'): ?>
            <div class="warning-message">
                <i class="fas fa-exclamation-circle"></i>
                Esta acción no se puede deshacer
            </div>
            
            <form method="POST">
                <input type="hidden" name="id_usuario" value="<?= $usuario['id_usuario'] ?>">
                <div class="btn-group">
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Confirmar Eliminación
                    </button>
                    <a href="../../admin/index.php" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                </div>
            </form>
        <?php else: ?>
            <a href="../../admin/index.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver al Panel
            </a>
        <?php endif; ?>
    </div>
</body>
</html>