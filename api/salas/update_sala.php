<?php
require_once "../db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_sala = $_POST["id_sala"];
    $nombre = $_POST["nombre"];
    $capacidad = $_POST["capacidad"];

    $stmt = $pdo->prepare("UPDATE salas SET nombre = ?, capacidad = ? WHERE id_sala = ?");
    
    try {
        $stmt->execute([$nombre, $capacidad, $id_sala]);
        echo json_encode(["status" => "success", "message" => "Sala actualizada"]);
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => "Error al actualizar sala: " . $e->getMessage()]);
    }
}
?>
