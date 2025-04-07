<?php
require_once "../../api/db.php";

$id_usuario = $_GET["id"] ?? null;
if (!$id_usuario) {
    die("ID de usuario no proporcionado.");
}

$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id_usuario = ?");
$stmt->execute([$id_usuario]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuario</title>
</head>
<body>
    <h2>Editar Usuario</h2>
    <form action="../../api/usuarios/update_usuario.php" method="POST">
        <input type="hidden" name="id_usuario" value="<?= $usuario['id_usuario'] ?>">

        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" value="<?= $usuario['nombre'] ?>" required><br>

        <label for="email">Email:</label>
        <input type="email" name="email" value="<?= $usuario['email'] ?>" required><br>

        <label for="contrasena">Contraseña (dejar en blanco si no desea cambiarla):</label>
        <input type="password" name="contrasena"><br>

        <button type="submit">Actualizar</button>
    </form>
    <a href="index.php">Volver</a>
</body>
</html>
