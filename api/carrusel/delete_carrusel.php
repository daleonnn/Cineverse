<?php
header('Content-Type: application/json');
require_once "../db.php";

$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'ID no proporcionado']);
    exit;
}

$id = $data['id'];

// Verificar si existe la película
$stmt = $pdo->prepare("SELECT * FROM carrusel WHERE id = ?");
$stmt->execute([$id]);
$pelicula = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$pelicula) {
    http_response_code(404);
    echo json_encode(['error' => 'Película no encontrada']);
    exit;
}

// Eliminar la película
$stmt = $pdo->prepare("DELETE FROM carrusel WHERE id = ?");
$stmt->execute([$id]);

echo json_encode(['success' => 'Película eliminada correctamente']);
?>