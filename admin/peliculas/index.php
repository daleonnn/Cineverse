<?php
require_once "../../api/db.php";

$stmt = $pdo->query("SELECT * FROM peliculas");
$peliculas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrar Películas</title>
    <link rel="stylesheet" href="../../assets/styles.css">
</head>
<body>
    <h1>Administrar Películas</h1>
    <a href="add_pelicula.php">Añadir Nueva Película</a>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Título</th>
            <th>Título Original</th>
            <th>Duración</th>
            <th>Género</th>
            <th>Clasificación</th>
            <th>Estreno</th>
            <th>Acciones</th>
        </tr>
        <?php foreach ($peliculas as $pelicula): ?>
            <tr>
                <td><?= $pelicula["id_pelicula"] ?></td>
                <td><?= $pelicula["titulo"] ?></td>
                <td><?= $pelicula["titulo_original"] ?></td>
                <td><?= $pelicula["duracion"] ?> min</td>
                <td><?= $pelicula["genero"] ?></td>
                <td><?= $pelicula["clasificacion"] ?></td>
                <td><?= $pelicula["fecha_estreno"] ?></td>
                <td>
                    <a href="edit_pelicula.php?id=<?= $pelicula['id_pelicula'] ?>">Editar</a>
                    <a href="delete_pelicula.php?id=<?= $pelicula['id_pelicula'] ?>" onclick="return confirm('¿Eliminar esta película?')">Eliminar</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>