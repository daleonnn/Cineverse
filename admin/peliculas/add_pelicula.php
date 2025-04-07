<?php
require_once "../../api/db.php";

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
    
    // Manejo de la imagen
    $imagen_nombre = '';
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $imagen_nombre = basename($_FILES['imagen']['name']);
        $ruta_destino = "../../assets/img/peliculas/" . $imagen_nombre;
        move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_destino);
    }

    $stmt = $pdo->prepare("INSERT INTO peliculas (titulo, descripcion, duracion, genero, clasificacion, imagen_nombre, titulo_original, edad_recomendada, sinopsis, trailer_url, fecha_estreno) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$titulo, $descripcion, $duracion, $genero, $clasificacion, $imagen_nombre, $titulo_original, $edad_recomendada, $sinopsis, $trailer_url, $fecha_estreno]);

    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Añadir Película</title>
    <link rel="stylesheet" href="../../assets/styles.css">
</head>
<body>
    <h1>Añadir Nueva Película</h1>
    <form method="POST" enctype="multipart/form-data">
        <label>Título:</label>
        <input type="text" name="titulo" required>
        
        <label>Título Original:</label>
        <input type="text" name="titulo_original">
        
        <label>Descripción:</label>
        <textarea name="descripcion" required></textarea>
        
        <label>Sinopsis:</label>
        <textarea name="sinopsis"></textarea>
        
        <label>Duración (min):</label>
        <input type="number" name="duracion" required>
        
        <label>Género:</label>
        <input type="text" name="genero">
        
        <label>Clasificación:</label>
        <input type="text" name="clasificacion">
        
        <label>Edad Recomendada:</label>
        <input type="text" name="edad_recomendada">
        
        <label>URL del Trailer:</label>
        <input type="text" name="trailer_url">
        
        <label>Fecha de Estreno:</label>
        <input type="date" name="fecha_estreno">
        
        <label>Imagen:</label>
        <input type="file" name="imagen" accept="image/*" required>
        
        <button type="submit">Guardar</button>
    </form>
    <a href="index.php">Volver</a>
</body>
</html>