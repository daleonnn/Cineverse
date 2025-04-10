<?php
require_once "../db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id"])) {
    $id_combo = $_POST["id"];
    $stmt = $pdo->prepare("DELETE FROM combos WHERE id_combo = ?");
    
    if ($stmt->execute([$id_combo])) {
        echo json_encode(["status" => "success", "message" => "Combo eliminado correctamente"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error al eliminar el combo"]);
    }
}
?>