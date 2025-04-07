<?php
require_once "../../api/db.php";

$id_pelicula = $_GET["id"];
$stmt = $pdo->prepare("SELECT * FROM peliculas WHERE id_pelicula = ?");
$stmt->execute([$id_pelicula]);
$pelicula = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$pelicula) {
    die("Película no encontrada");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = $_POST["titulo"];
    $descripcion = $_POST["descripcion"];
    $duracion = $_POST["duracion"];
    $genero = $_POST["genero"];
    $clasificacion = $_POST["clasificacion"];
    $titulo_original = $_POST["titulo_original"];
    $edad_recomendada = $_POST["edad_recomendada"];
    $sinopsis = $_POST["sinopsis"];
    $trailer_url = $_POST["trailer_url"];
    $fecha_estreno = $_POST["fecha_estreno"];
    
    // Mantener la imagen actual si no se sube una nueva
    $imagen_nombre = $pelicula['imagen_nombre'];
    
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        // Eliminar la imagen anterior si existe
        if (!empty($imagen_nombre)) {
            $ruta_anterior = "../../assets/img/peliculas/" . $imagen_nombre;
            if (file_exists($ruta_anterior)) {
                unlink($ruta_anterior);
            }
        }
        
        // Subir la nueva imagen
        $imagen_nombre = basename($_FILES['imagen']['name']);
        $ruta_destino = "../../assets/img/peliculas/" . $imagen_nombre;
        move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_destino);
    }

    $stmt = $pdo->prepare("UPDATE peliculas SET titulo = ?, descripcion = ?, duracion = ?, genero = ?, clasificacion = ?, imagen_nombre = ?, titulo_original = ?, edad_recomendada = ?, sinopsis = ?, trailer_url = ?, fecha_estreno = ? WHERE id_pelicula = ?");
    $stmt->execute([$titulo, $descripcion, $duracion, $genero, $clasificacion, $imagen_nombre, $titulo_original, $edad_recomendada, $sinopsis, $trailer_url, $fecha_estreno, $id_pelicula]);

    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Película</title>
    <link rel="stylesheet" href="../../assets/styles.css">
</head>
<body>
    <h1>Editar Película</h1>
    <form method="POST" enctype="multipart/form-data">
        <label>Título:</label>
        <input type="text" name="titulo" value="<?= $pelicula['titulo'] ?>" required>
        
        <label>Título Original:</label>
        <input type="text" name="titulo_original" value="<?= $pelicula['titulo_original'] ?>">
        
        <label>Descripción:</label>
        <textarea name="descripcion" required><?= $pelicula['descripcion'] ?></textarea>
        
        <label>Sinopsis:</label>
        <textarea name="sinopsis"><?= $pelicula['sinopsis'] ?></textarea>
        
        <label>Duración (min):</label>
        <input type="number" name="duracion" value="<?= $pelicula['duracion'] ?>" required>
        
        <label>Género:</label>
        <input type="text" name="genero" value="<?= $pelicula['genero'] ?>">
        
        <label>Clasificación:</label>
        <input type="text" name="clasificacion" value="<?= $pelicula['clasificacion'] ?>">
        
        <label>Edad Recomendada:</label>
        <input type="text" name="edad_recomendada" value="<?= $pelicula['edad_recomendada'] ?>">
        
        <label>URL del Trailer:</label>
        <input type="text" name="trailer_url" value="<?= $pelicula['trailer_url'] ?>">
        
        <label>Fecha de Estreno:</label>
        <input type="date" name="fecha_estreno" value="<?= $pelicula['fecha_estreno'] ?>">
        
        <label>Imagen actual:</label>
        <?php if (!empty($pelicula['imagen_nombre'])): ?>
            <img src="../../assets/img/peliculas/<?= $pelicula['imagen_nombre'] ?>" width="100">
            <input type="hidden" name="imagen_actual" value="<?= $pelicula['imagen_nombre'] ?>">
        <?php endif; ?>
        <label>Nueva imagen (dejar en blanco para mantener la actual):</label>
        <input type="file" name="imagen" accept="image/*">
        <button type="submit">Actualizar</button>
    </form>
    <a href="index.php">Volver</a>
</body>
</html>