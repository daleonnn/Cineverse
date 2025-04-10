<?php
header('Content-Type: application/json');
require_once "../db.php";

$stmt = $pdo->query("SELECT * FROM carrusel ORDER BY id DESC");
$peliculas = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($peliculas);
?>