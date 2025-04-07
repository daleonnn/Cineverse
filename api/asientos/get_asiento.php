<?php
require_once "../db.php";

if (isset($_GET["id"])) {
    $id_asiento = $_GET["id"];
    $stmt = $pdo->prepare("SELECT * FROM asientos WHERE id_asiento = ?");
    $stmt->execute([$id_asiento]);
    $asiento = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($asiento) {
        echo json_encode($asiento);
    } else {
        echo json_encode(["error" => "Asiento no encontrado."]);
    }
}
?>
