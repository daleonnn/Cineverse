<?php
require_once "../../api/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $capacidad = $_POST["capacidad"];

    $stmt = $pdo->prepare("INSERT INTO salas (nombre, capacidad) VALUES (?, ?)");
    $stmt->execute([$nombre, $capacidad]);

    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Añadir Sala</title>
    <link rel="stylesheet" href="../../assets/styles.css">
</head>
<body>
    <h1>Añadir Nueva Sala</h1>
    <form method="POST">
        <label>Nombre:</label>
        <input type="text" name="nombre" required>
        <label>Capacidad:</label>
        <input type="number" name="capacidad" required>
        <button type="submit">Guardar</button>
    </form>
    <a href="index.php">Volver</a>
</body>
</html>
