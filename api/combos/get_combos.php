<?php
require_once "../db.php";

$stmt = $pdo->query("SELECT * FROM combos");
$combos = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($combos);
?>