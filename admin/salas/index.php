<?php
require_once "../../api/db.php";

$stmt = $pdo->query("SELECT * FROM salas");
$salas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrar Salas</title>
    <link rel="stylesheet" href="../../assets/styles.css">
</head>
<body>
    <h1>Administrar Salas</h1>
    <a href="add_sala.php">Añadir Nueva Sala</a>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Capacidad</th>
            <th>Acciones</th>
        </tr>
        <?php foreach ($salas as $sala): ?>
            <tr>
                <td><?= $sala["id_sala"] ?></td>
                <td><?= $sala["nombre"] ?></td>
                <td><?= $sala["capacidad"] ?></td>
                <td>
                    <a href="edit_sala.php?id=<?= $sala['id_sala'] ?>">Editar</a>
                    <a href="delete_sala.php?id=<?= $sala['id_sala'] ?>" onclick="return confirm('¿Eliminar esta sala?')">Eliminar</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
