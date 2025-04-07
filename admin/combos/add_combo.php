<?php
require_once "../../api/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $descripcion = $_POST["descripcion"];
    $precio = $_POST["precio"];

    $stmt = $pdo->prepare("INSERT INTO combos (nombre, descripcion, precio) VALUES (?, ?, ?)");
    if ($stmt->execute([$nombre, $descripcion, $precio])) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error al añadir el combo.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Combo</title>
    <link rel="stylesheet" href="../../assets/styles.css">
</head>
<body>
    <h2>Agregar Combo</h2>
    <form method="POST">
        <label>Nombre:</label>
        <input type="text" name="nombre" required>
        
        <label>Descripción:</label>
        <textarea name="descripcion" required></textarea>
        
        <label>Precio:</label>
        <input type="number" name="precio" step="0.01" required>
        
        <button type="submit">Guardar</button>
        <a href="index.php">Volver</a>
    </form>
</body>
</html>
