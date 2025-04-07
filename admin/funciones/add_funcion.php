<?php
require_once "../../api/db.php";
$stmt_peliculas = $pdo->query("SELECT id_pelicula, titulo FROM peliculas");
$peliculas = $stmt_peliculas->fetchAll(PDO::FETCH_ASSOC);

$stmt_salas = $pdo->query("SELECT id_sala, nombre FROM salas");
$salas = $stmt_salas->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Añadir Función</title>
</head>
<body>
    <h2>Añadir Función</h2>
    <form action="../../api/funciones/add_funcion.php" method="POST">
        <label for="id_pelicula">Película:</label>
        <select name="id_pelicula" required>
            <?php foreach ($peliculas as $pelicula): ?>
                <option value="<?= $pelicula['id_pelicula'] ?>"><?= $pelicula['titulo'] ?></option>
            <?php endforeach; ?>
        </select><br>

        <label for="fecha_hora">Fecha y Hora:</label>
        <input type="datetime-local" name="fecha_hora" required><br>

        <label for="id_sala">Sala:</label>
        <select name="id_sala" required>
            <?php foreach ($salas as $sala): ?>
                <option value="<?= $sala['id_sala'] ?>"><?= $sala['nombre'] ?></option>
            <?php endforeach; ?>
        </select><br>

        <button type="submit">Añadir</button>
    </form>
    <a href="index.php">Volver</a>
</body>
</html>
