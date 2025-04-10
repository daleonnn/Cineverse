<?php
require_once "../db.php";

header('Content-Type: application/json');

if (!isset($_GET['q']) || empty($_GET['q'])) {
    echo json_encode([]);
    exit;
}

$query = '%' . $_GET['q'] . '%';

$stmt = $pdo->prepare("SELECT * FROM peliculas 
                      WHERE titulo LIKE :query 
                      OR genero LIKE :query 
                      OR descripcion LIKE :query
                      ORDER BY titulo ASC");
$stmt->bindParam(':query', $query, PDO::PARAM_STR);
$stmt->execute();

$peliculas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Limitar los datos que enviamos al frontend
$resultados = array_map(function($pelicula) {
    return [
        'id_pelicula' => $pelicula['id_pelicula'],
        'titulo' => $pelicula['titulo'],
        'genero' => $pelicula['genero'],
        'duracion' => $pelicula['duracion']
    ];
}, $peliculas);

echo json_encode($resultados);
?>