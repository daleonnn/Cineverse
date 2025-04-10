<?php
header('Content-Type: application/json');
require_once "../db.php";

if (!isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'ID no proporcionado']);
    exit;
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM carrusel WHERE id = ?");
$stmt->execute([$id]);
$pelicula = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$pelicula) {
    http_response_code(404);
    echo json_encode(['error' => 'Película no encontrada']);
    exit;
}

echo json_encode($pelicula);
?>