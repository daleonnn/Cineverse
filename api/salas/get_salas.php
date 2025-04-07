<?php
require_once "../db.php";

$stmt = $pdo->query("SELECT * FROM salas");
$salas = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($salas);
?>
