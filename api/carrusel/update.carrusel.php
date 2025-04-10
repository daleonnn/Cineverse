<?php
header('Content-Type: application/json');
require_once "../db.php";

$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Datos no válidos']);
    exit;
}

$id = $data['id'];
$titulo = $data['titulo'] ?? '';
$titulo_original = $data['titulo_original'] ?? '';
$genero = $data['genero'] ?? '';
$duracion = $data['duracion'] ?? '';
$estreno = $data['estreno'] ?? '';
$recomendado_por = $data['recomendado_por'] ?? '';
$trailer_url = $data['trailer_url'] ?? '';
$sinopsis = $data['sinopsis'] ?? '';
$imagen_nombre = $data['imagen_nombre'] ?? '';
$imagen_ruta = $data['imagen_ruta'] ?? '';
$imagen_tipo = $data['imagen_tipo'] ?? '';
$imagen_tamanio = $data['imagen_tamanio'] ?? 0;

// Verificar si existe la película
$stmt = $pdo->prepare("SELECT * FROM carrusel WHERE id = ?");
$stmt->execute([$id]);
$pelicula = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$pelicula) {
    http_response_code(404);
    echo json_encode(['error' => 'Película no encontrada']);
    exit;
}

// Actualizar la película
$stmt = $pdo->prepare("UPDATE carrusel SET titulo = ?, titulo_original = ?, genero = ?, duracion = ?, estreno = ?, recomendado_por = ?, trailer_url = ?, sinopsis = ?, imagen_nombre = ?, imagen_ruta = ?, imagen_tipo = ?, imagen_tamanio = ? WHERE id = ?");
$stmt->execute([$titulo, $titulo_original, $genero, $duracion, $estreno, $recomendado_por, $trailer_url, $sinopsis, $imagen_nombre, $imagen_ruta, $imagen_tipo, $imagen_tamanio, $id]);

$pelicula = $pdo->query("SELECT * FROM carrusel WHERE id = $id")->fetch(PDO::FETCH_ASSOC);

echo json_encode($pelicula);
?>