<?php
require_once "../../api/db.php";
//oli
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
    $stmt = $pdo->prepare("DELETE FROM combos WHERE id_combo = ?");
    
    if ($stmt->execute([$id_combo])) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error al eliminar el combo.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Eliminar Combo</title>
    <link rel="stylesheet" href="../../assets/styles.css">
</head>
<body>
    <h2>Eliminar Combo</h2>
    <p>¿Estás seguro de que deseas eliminar el combo <strong><?= $combo['nombre'] ?></strong>?</p>
    
    <form method="POST">
        <input type="hidden" name="id" value="<?= $combo['id_combo'] ?>">
        <button type="submit">Sí, eliminar</button>
        <a href="index.php">Cancelar</a>
    </form>
</body>
</html>
