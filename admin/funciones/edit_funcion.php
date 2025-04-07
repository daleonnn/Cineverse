<?php
require_once "../../api/db.php";

$id_funcion = $_GET["id"] ?? null;
if (!$id_funcion) {
    die("ID de función no proporcionado.");
}

$stmt = $pdo->prepare("SELECT * FROM funciones WHERE id_funcion = ?");
$stmt->execute([$id_funcion]);
$funcion = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt_peliculas = $pdo->query("SELECT id_pelicula, titulo FROM peliculas");
$peliculas = $stmt_peliculas->fetchAll(PDO::FETCH_ASSOC);

$stmt_salas = $pdo->query("SELECT id_sala, nombre FROM salas");
$salas = $stmt_salas->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Función</title>
</head>
<body>
    <h2>Editar Función</h2>
    <form action="../../api/funciones/update_funcion.php" method="POST">
        <input type="hidden" name="id_funcion" value="<?= $funcion['id_funcion'] ?>">

        <label for="id_pelicula">Película:</label>
        <select name="id_pelicula" required>
            <?php foreach ($peliculas as $pelicula): ?>
                <option value="<?= $pelicula['id_pelicula'] ?>" <?= $funcion['id_pelicula'] == $pelicula['id_pelicula'] ? 'selected' : '' ?>>
                    <?= $pelicula['titulo'] ?>
                </option>
            <?php endforeach; ?>
        </select><br>

        <label for="fecha_hora">Fecha y Hora:</label>
        <input type="datetime-local" name="fecha_hora" value="<?= date('Y-m-d\TH:i', strtotime($funcion['fecha_hora'])) ?>" required><br>

        <label for="id_sala">Sala:</label>
        <select name="id_sala" required>
            <?php foreach ($salas as $sala): ?>
                <option value="<?= $sala['id_sala'] ?>" <?= $funcion['id_sala'] == $sala['id_sala'] ? 'selected' : '' ?>>
                    <?= $sala['nombre'] ?>
                </option>
            <?php endforeach; ?>
        </select><br>

        <button type="submit">Actualizar</button>
    </form>
    <a href="index.php">Volver</a>
</body>
</html>
