<?php
require_once "../../api/db.php";

if (!isset($_GET['id'])) {
    header("Location: ../index.php");
    exit;
}

$id = $_GET['id'];

// Verificar si existe el elemento
$stmt = $pdo->prepare("SELECT * FROM carrusel WHERE id = ?");
$stmt->execute([$id]);
$pelicula = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$pelicula) {
    header("Location: ../index.php");
    exit;
}

// Eliminar el elemento
$stmt = $pdo->prepare("DELETE FROM carrusel WHERE id = ?");
$stmt->execute([$id]);

// Opcional: eliminar también la imagen asociada
if ($pelicula['imagen_ruta'] && file_exists("../../" . parse_url($pelicula['imagen_ruta'], PHP_URL_PATH))) {
    unlink("../../" . parse_url($pelicula['imagen_ruta'], PHP_URL_PATH));
}

header("Location: ../index.php");
exit;
?>