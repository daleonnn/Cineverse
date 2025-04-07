<?php
require_once "../../api/db.php";

if (isset($_GET["id"])) {
    $id_sala = $_GET["id"];
    $stmt = $pdo->prepare("DELETE FROM salas WHERE id_sala = ?");
    $stmt->execute([$id_sala]);
}

header("Location: index.php");
exit;
?>
