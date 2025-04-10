<?php
require_once "../db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_combo = $_POST["id"];
    $nombre = $_POST["nombre"];
    $descripcion = $_POST["descripcion"];
    $precio = $_POST["precio"];

    $stmt = $pdo->prepare("UPDATE combos SET nombre = ?, descripcion = ?, precio = ? WHERE id_combo = ?");
    if ($stmt->execute([$nombre, $descripcion, $precio, $id_combo])) {
        echo json_encode(["status" => "success", "message" => "Combo actualizado correctamente"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error al actualizar el combo"]);
    }
}
?>