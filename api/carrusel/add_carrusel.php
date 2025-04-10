<?php
header('Content-Type: application/json');
require_once "../db.php";

$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    http_response_code(400);
    echo json_encode(['error' => 'Datos no válidos']);
    exit;
}

// Validar datos requeridos
if (empty($data['titulo'])) {
    http_response_code(400);
    echo json_encode(['error' => 'El título es requerido']);
    exit;
}

$titulo = $data['titulo'];
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

$stmt = $pdo->prepare("INSERT INTO carrusel (titulo, titulo_original, genero, duracion, estreno, recomendado_por, trailer_url, sinopsis, imagen_nombre, imagen_ruta, imagen_tipo, imagen_tamanio) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->execute([$titulo, $titulo_original, $genero, $duracion, $estreno, $recomendado_por, $trailer_url, $sinopsis, $imagen_nombre, $imagen_ruta, $imagen_tipo, $imagen_tamanio]);

$id = $pdo->lastInsertId();
$pelicula = $pdo->query("SELECT * FROM carrusel WHERE id = $id")->fetch(PDO::FETCH_ASSOC);

http_response_code(201);
echo json_encode($pelicula);
?>