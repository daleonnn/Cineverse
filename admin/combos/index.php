<?php
require_once "../../api/db.php";

$stmt = $pdo->query("SELECT * FROM combos");
$combos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administrar Combos</title>
    <link rel="stylesheet" href="../../assets/styles.css">
</head>
<body>
    <h2>Lista de Combos</h2>
    <a href="add_combo.php">➕ Agregar Combo</a>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Precio</th>
            <th>Acciones</th>
        </tr>
        <?php foreach ($combos as $combo) : ?>
        <tr>
            <td><?= $combo["id_combo"] ?></td>
            <td><?= $combo["nombre"] ?></td>
            <td><?= $combo["descripcion"] ?></td>
            <td>$<?= number_format($combo["precio"], 2) ?></td>
            <td>
                <a href="edit_combo.php?id=<?= $combo["id_combo"] ?>">✏️ Editar</a> | 
                <a href="delete_combo.php?id=<?= $combo["id_combo"] ?>">❌ Eliminar</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
