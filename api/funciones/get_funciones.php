<?php
require_once "../db.php";

$stmt = $pdo->query("SELECT * FROM funciones");
$funciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($funciones);
?>
