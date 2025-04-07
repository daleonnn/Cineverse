<?php
require_once "../../api/db.php";

if (isset($_GET["id"])) {
    $id_combo = $_GET["id"];
    $stmt = $pdo->prepare("SELECT * FROM combos WHERE id_combo = ?");
    $stmt->execute([$id_combo]);
    $combo = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$combo) {
        die("Combo no encontrado.");
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_combo = $_POST["id"];
    $nombre = $_POST["nombre"];
    $descripcion = $_POST["descripcion"];
    $precio = $_POST["precio"];

    $stmt = $pdo->prepare("UPDATE combos SET nombre = ?, descripcion = ?, precio = ? WHERE id_combo = ?");
    if ($stmt->execute([$nombre, $descripcion, $precio, $id_combo])) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error al actualizar el combo.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Combo</title>
    <link rel="stylesheet" href="../../assets/styles.css">
</head>
<body>
    <h2>Editar Combo</h2>
    <form method="POST">
        <input type="hidden" name="id" value="<?= $combo['id_combo'] ?>">

        <label>Nombre:</label>
        <input type="text" name="nombre" value="<?= $combo['nombre'] ?>" required>
        
        <label>Descripción:</label>
        <textarea name="descripcion" required><?= $combo['descripcion'] ?></textarea>
        
        <label>Precio:</label>
        <input type="number" name="precio" step="0.01" value="<?= $combo['precio'] ?>" required>
        
        <button type="submit">Actualizar</button>
        <a href="index.php">Volver</a>
    </form>
</body>
</html>
