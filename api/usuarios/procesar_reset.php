<?php
session_start();
require __DIR__.'/../../libs/database.php';

// 1. Recibir datos
$token = $_POST['token'];
$nueva_contrasena = $_POST['nueva_contrasena'];

// 2. Validar token
$stmt = $pdo->prepare("SELECT id_usuario FROM usuarios 
                      WHERE token_recuperacion = ? 
                      AND token_expiracion > NOW()");
$stmt->execute([$token]);
$usuario = $stmt->fetch();

if (!$usuario) die("Token inválido o expirado");

// 3. Hashear y actualizar contraseña
$hash = password_hash($nueva_contrasena, PASSWORD_DEFAULT);
$stmt = $pdo->prepare("UPDATE usuarios 
                      SET contrasena = ?, 
                          token_recuperacion = NULL, 
                          token_expiracion = NULL 
                      WHERE id_usuario = ?");
if ($stmt->execute([$hash, $usuario['id_usuario']])) {
    echo "✅ Contraseña actualizada correctamente";
} else {
    echo "❌ Error al guardar la nueva contraseña";
}
?>