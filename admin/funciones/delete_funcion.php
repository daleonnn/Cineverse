<?php
require_once "../../api/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_funcion = $_POST["id_funcion"];
    $stmt = $pdo->prepare("DELETE FROM funciones WHERE id_funcion = ?");
    
    if ($stmt->execute([$id_funcion])) {
        header("Location: index.php");
    } else {
        echo "Error al eliminar la función.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Eliminar Función</title>
</head>
<body>
    <h2>Eliminar Función</h2>
    <p>¿Estás seguro de que quieres eliminar esta función?</p>
    
    <form action="delete_funcion.php" method="POST">
        <input type="hidden" name="id_funcion" value="<?= $_GET['id'] ?>">
        <button type="submit">Eliminar</button>
    </form>

    <a href="index.php">Cancelar</a>
</body>
</html>
