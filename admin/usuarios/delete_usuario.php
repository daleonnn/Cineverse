<?php
require_once "../../api/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_usuario = $_POST["id_usuario"];
    $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id_usuario = ?");
    
    if ($stmt->execute([$id_usuario])) {
        header("Location: index.php");
    } else {
        echo "Error al eliminar el usuario.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Eliminar Usuario</title>
</head>
<body>
    <h2>Eliminar Usuario</h2>
    <p>¿Estás seguro de que quieres eliminar este usuario?</p>
    
    <form action="delete_usuario.php" method="POST">
        <input type="hidden" name="id_usuario" value="<?= $_GET['id'] ?>">
        <button type="submit">Eliminar</button>
    </form>

    <a href="index.php">Cancelar</a>
</body>
</html>
