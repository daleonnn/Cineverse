<?php
require '../../libs/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Método no permitido']);
    exit;
}

$token = $_POST['token'] ?? null;
$nuevaContrasena = $_POST['nueva_contrasena'] ?? null;

if (!$token || !$nuevaContrasena) {
    echo json_encode(['error' => 'Datos incompletos']);
    exit;
}

// Buscar usuario con token válido
$stmt = $pdo->prepare("SELECT id_usuario FROM usuarios WHERE token_recuperacion = ? AND token_expiracion > NOW()");
$stmt->execute([$token]);
$usuario = $stmt->fetch();

if (!$usuario) {
    echo json_encode(['error' => 'Token inválido o expirado']);
    exit;
}

// Hashear nueva contraseña
$hash = password_hash($nuevaContrasena, PASSWORD_DEFAULT);

// Actualizar contraseña y limpiar token
$stmt = $pdo->prepare("UPDATE usuarios SET contrasena = ?, token_recuperacion = NULL, token_expiracion = NULL WHERE id_usuario = ?");
$stmt->execute([$hash, $usuario['id_usuario']]);

echo json_encode(['success' => 'Contraseña actualizada. Ya puedes iniciar sesión.']);
?>