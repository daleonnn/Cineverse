<?php
require_once "../../api/db.php";

$id_sala = $_GET["id"];
$stmt = $pdo->prepare("SELECT * FROM salas WHERE id_sala = ?");
$stmt->execute([$id_sala]);
$sala = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$sala) {
    die("Sala no encontrada");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $capacidad = $_POST["capacidad"];

    $stmt = $pdo->prepare("UPDATE salas SET nombre = ?, capacidad = ? WHERE id_sala = ?");
    $stmt->execute([$nombre, $capacidad, $id_sala]);

    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Sala</title>
    <link rel="stylesheet" href="../../assets/styles.css">
</head>
<body>
    <h1>Editar Sala</h1>
    <form method="POST">
        <label>Nombre:</label>
        <input type="text" name="nombre" value="<?= $sala['nombre'] ?>" required>
        <label>Capacidad:</label>
        <input type="number" name="capacidad" value="<?= $sala['capacidad'] ?>" required>
        <button type="submit">Actualizar</button>
    </form>
    <a href="index.php">Volver</a>
</body>
</html>
