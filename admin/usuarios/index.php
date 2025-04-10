<?php
require_once "../../api/db.php";

$stmt = $pdo->query("SELECT * FROM usuarios");
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Usuarios</title>
</head>
<body>
    <h2>Usuarios Registrados</h2>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Fecha de Registro</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($usuarios as $usuario): ?>
                <tr>
                    <td><?= $usuario["id_usuario"] ?></td>
                    <td><?= $usuario["nombre"] ?></td>
                    <td><?= $usuario["email"] ?></td>
                    <td><?= $usuario["fecha_registro"] ?></td>
                    <td>
                        <a href="edit_usuario.php?id=<?= $usuario['id_usuario'] ?>">Editar</a> |
                        <a href="delete_usuario.php?id=<?= $usuario['id_usuario'] ?>">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
