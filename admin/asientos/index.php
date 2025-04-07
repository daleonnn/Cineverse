<?php
require_once "../../api/db.php";

// Obtener salas
$salas = $pdo->query("SELECT * FROM salas")->fetchAll(PDO::FETCH_ASSOC);

// Obtener la sala seleccionada
$id_sala = isset($_GET["id_sala"]) ? $_GET["id_sala"] : ($salas[0]["id_sala"] ?? 0);

// Obtener la capacidad de la sala seleccionada
$stmt = $pdo->prepare("SELECT capacidad FROM salas WHERE id_sala = ?");
$stmt->execute([$id_sala]);
$capacidad = $stmt->fetchColumn() ?: 0;

// Obtener los asientos registrados de la sala seleccionada
$stmt = $pdo->prepare("SELECT * FROM asientos WHERE id_sala = ?");
$stmt->execute([$id_sala]);
$asientos_registrados = $stmt->fetchAll(PDO::FETCH_ASSOC);
$asientos_disponibles = array_column($asientos_registrados, "numero");

// Si hay asientos faltantes, insertarlos en la base de datos
for ($i = 1; $i <= $capacidad; $i++) {
    if (!in_array($i, $asientos_disponibles)) {
        $stmt = $pdo->prepare("INSERT INTO asientos (id_sala, numero, estado) VALUES (?, ?, 'disponible')");
        $stmt->execute([$id_sala, $i]);
    }
}

// Volver a obtener los asientos después de la inserción
$stmt = $pdo->prepare("SELECT * FROM asientos WHERE id_sala = ? ORDER BY numero ASC");
$stmt->execute([$id_sala]);
$asientos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Asientos</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <h2>Gestión de Asientos</h2>

    <!-- Seleccionar sala -->
    <form method="GET">
        <label for="id_sala">Selecciona una sala:</label>
        <select name="id_sala" id="id_sala" onchange="this.form.submit()">
            <?php foreach ($salas as $sala): ?>
                <option value="<?= $sala['id_sala'] ?>" <?= ($sala['id_sala'] == $id_sala) ? "selected" : "" ?>>
                    <?= $sala['nombre'] ?> (Capacidad: <?= $sala['capacidad'] ?>)
                </option>
            <?php endforeach; ?>
        </select>
    </form>

    <h3>Asientos de la Sala Seleccionada</h3>
    <table border="1">
        <tr>
            <th>Número</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
        <?php foreach ($asientos as $asiento): ?>
        <tr>
            <td><?= $asiento["numero"] ?></td>
            <td><?= $asiento["estado"] == "disponible" ? "✅ Disponible" : "❌ Ocupado" ?></td>
            <td>
                <a href="edit_asiento.php?id=<?= $asiento['id_asiento'] ?>">Editar</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
