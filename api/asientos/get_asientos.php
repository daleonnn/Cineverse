<?php
require_once "../db.php";

$stmt = $pdo->query("SELECT * FROM asientos");
$asientos = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($asientos);
?>
