<?php
require_once "../../api/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_sala = $_POST["id_sala"];
    $numero = $_POST["numero"];
    $estado = $_POST["estado"];

    $stmt = $pdo->prepare("INSERT INTO asientos (id_sala, numero, estado) VALUES (?, ?, ?)");
    if ($stmt->execute([$id_sala, $numero, $estado])) {
        header("Location: index.php");
        exit;
    } else {
        echo "Error al agregar el asiento.";
    }
}

$salas = $pdo->query("SELECT * FROM salas")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Asiento</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <h2>Agregar Asiento</h2>
    <form method="POST">
        <label>Sala:</label>
        <select name="id_sala" required>
            <?php foreach ($salas as $sala): ?>
                <option value="<?= $sala['id_sala'] ?>"><?= $sala['nombre'] ?></option>
            <?php endforeach; ?>
        </select>
        <label>Número de Asiento:</label>
        <input type="number" name="numero" required>
        <label>Estado:</label>
        <select name="estado">
            <option value="disponible">Disponible</option>
            <option value="ocupado">Ocupado</option>
        </select>
        <button type="submit">Guardar</button>
    </form>
    <a href="index.php">Volver</a>
</body>
</html>



