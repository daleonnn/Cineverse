<?php
require_once "../../api/db.php";

if (isset($_GET["id"])) {
    $id_pelicula = $_GET["id"];
    
    // Obtener el nombre de la imagen para eliminarla
    $stmt = $pdo->prepare("SELECT imagen_nombre FROM peliculas WHERE id_pelicula = ?");
    $stmt->execute([$id_pelicula]);
    $pelicula = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($pelicula && !empty($pelicula['imagen_nombre'])) {
        $ruta_imagen = "../../assets/img/peliculas/" . $pelicula['imagen_nombre'];
        if (file_exists($ruta_imagen)) {
            unlink($ruta_imagen);
        }
    }
    
    // Eliminar la película de la base de datos
    $stmt = $pdo->prepare("DELETE FROM peliculas WHERE id_pelicula = ?");
    $stmt->execute([$id_pelicula]);
}

header("Location: index.php");
exit;
?>