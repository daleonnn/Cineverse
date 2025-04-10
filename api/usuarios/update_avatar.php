<?php
session_start();
require_once __DIR__ . '/../../db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'No autorizado']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$allowedAvatars = ['avatar1.png', 'avatar2.png', 'avatar3.png', 'avatar4.png'];

if (!in_array($data['avatar'], $allowedAvatars)) {
    echo json_encode(['status' => 'error', 'message' => 'Avatar no válido']);
    exit;
}

try {
    // Actualizar en la base de datos
    $stmt = $pdo->prepare("UPDATE usuarios SET avatar = ? WHERE id_usuario = ?");
    $stmt->execute([$data['avatar'], $_SESSION['user_id']]);
    
    // Actualizar en la sesión
    $_SESSION['user_avatar'] = $data['avatar'];
    
    echo json_encode(['status' => 'success', 'avatar' => $data['avatar']]);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Error en la base de datos']);
}
?>