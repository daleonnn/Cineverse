<?php
require_once "../db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $descripcion = $_POST["descripcion"];
    $precio = $_POST["precio"];
    

    $stmt = $pdo->prepare("INSERT INTO combos (nombre, descripcion, precio) VALUES (?, ?, ?)");
    if ($stmt->execute([$nombre, $descripcion, $precio])) {
        echo json_encode(["status" => "success", "message" => "Combo añadido correctamente"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error al añadir el combo"]);
    }
}
?>