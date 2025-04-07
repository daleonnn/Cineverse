<?php
require_once "../db.php";

if (!isset($_GET["id_sala"])) {
    echo json_encode(["status" => "error", "message" => "ID de sala requerido"]);
    exit;
}

$id_sala = $_GET["id_sala"];

$stmt = $pdo->prepare("SELECT * FROM salas WHERE id_sala = ?");
$stmt->execute([$id_sala]);
$sala = $stmt->fetch(PDO::FETCH_ASSOC);

if ($sala) {
    echo json_encode($sala);
} else {
    echo json_encode(["status" => "error", "message" => "Sala no encontrada"]);
}
?>
