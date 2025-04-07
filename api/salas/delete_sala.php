<?php
require_once "../db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_sala = $_POST["id_sala"];

    $stmt = $pdo->prepare("DELETE FROM salas WHERE id_sala = ?");
    
    try {
        $stmt->execute([$id_sala]);
        echo json_encode(["status" => "success", "message" => "Sala eliminada"]);
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => "Error al eliminar sala: " . $e->getMessage()]);
    }
}
?>
