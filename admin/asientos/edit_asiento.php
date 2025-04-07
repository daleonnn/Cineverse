<?php
require_once "../../api/db.php";

if (isset($_GET["id"])) {
    $id_asiento = $_GET["id"];
    $stmt = $pdo->prepare("SELECT * FROM asientos WHERE id_asiento = ?");
    $stmt->execute([$id_asiento]);
    $asiento = $stmt->fetch(PDO::FETCH_ASSOC);
}

// hola
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $numero = $_POST["numero"];
    $estado = $_POST["estado"];

    $stmt = $pdo->prepare("UPDATE asientos SET numero = ?, estado = ? WHERE id_asiento = ?");
    $stmt->execute([$numero, $estado, $id_asiento]);

    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Asiento</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <h2>Editar Asiento</h2>
    <form method="POST">
        <label for="numero">Número de Asiento:</label>
        <input type="number" name="numero" id="numero" value="<?= $asiento['numero'] ?>" required>

        <label for="estado">Estado del Asiento:</label>
        <select name="estado" id="estado">
            <option value="disponible" <?= ($asiento['estado'] == "disponible") ? "selected" : "" ?>>Disponible</option>
            <option value="ocupado" <?= ($asiento['estado'] == "ocupado") ? "selected" : "" ?>>Ocupado</option>
        </select>

        <button type="submit">Guardar Cambios</button>
    </form>
</body>
</html>
