<?php
require_once "../../api/db.php";

$stmt = $pdo->query("SELECT funciones.*, peliculas.titulo AS pelicula, salas.nombre AS sala 
                     FROM funciones
                     JOIN peliculas ON funciones.id_pelicula = peliculas.id_pelicula
                     JOIN salas ON funciones.id_sala = salas.id_sala");
$funciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Funciones</title>
</head>
<body>
    <h2>Funciones de Cine</h2>
    <a href="add_funcion.php">Añadir Función</a>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Película</th>
                <th>Precio</th>
                <th>Fecha y Hora</th>
                <th>Sala</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($funciones as $funcion): ?>
                <tr>
                    <td><?= $funcion["id_funcion"] ?></td>
                    <td><?= $funcion["pelicula"] ?></td>
                    <td><?= $funcion["fecha_hora"] ?></td>
                    <td>$<?= number_format($funcion["precio"], 0, ',', '.') ?> COP</td>
                    <td><?= $funcion["sala"] ?></td>
                    <td>
                        <a href="edit_funcion.php?id=<?= $funcion['id_funcion'] ?>">Editar</a> |
                        <a href="delete_funcion.php?id=<?= $funcion['id_funcion'] ?>">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
