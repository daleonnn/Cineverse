<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Conexión con ruta absoluta garantizada
require 'C:/xampp/htdocs/cineapp/libs/database.php';

// Verificar token
$token = $_GET['token'] ?? null;
if (empty($token)) {
    die("Token no proporcionado");
}

// Validar en BD
$stmt = $pdo->prepare("SELECT id_usuario FROM usuarios WHERE token_recuperacion = ? AND token_expiracion > NOW()");
$stmt->execute([$token]);
$usuario = $stmt->fetch();

if (!$usuario) {
    die("Token inválido o expirado");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar contraseña</title>
</head>
<body>
<div class="cuadro-resetear">
    <form action="/cineapp/api/usuarios/resetear.php" method="POST">
        <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
        <input type="password" name="nueva_contrasena" placeholder="Nueva contraseña" required>
        <button type="submit">Guardar</button>
    </form>
</div>
</body>
</html>