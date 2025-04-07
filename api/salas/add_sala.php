<?php
require_once "../db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $capacidad = $_POST["capacidad"];

    $stmt = $pdo->prepare("INSERT INTO salas (nombre, capacidad) VALUES (?, ?)");

    try {
        $stmt->execute([$nombre, $capacidad]);
        echo json_encode(["status" => "success", "message" => "Sala agregada"]);
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => "Error al agregar sala: " . $e->getMessage()]);
    }
}
?>
