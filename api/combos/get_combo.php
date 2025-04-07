<?php
require_once "../db.php";

if (isset($_GET["id"])) {
    $id_combo = $_GET["id"];
    $stmt = $pdo->prepare("SELECT * FROM combos WHERE id_combo = ?");
    $stmt->execute([$id_combo]);
    $combo = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($combo) {
        echo json_encode($combo);
    } else {
        echo json_encode(["status" => "error", "message" => "Combo no encontrado"]);
    }
}
?>