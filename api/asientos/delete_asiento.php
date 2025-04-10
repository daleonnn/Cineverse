<?php
require_once "../db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_asiento = $_POST["id_asiento"];

    $stmt = $pdo->prepare("DELETE FROM asientos WHERE id_asiento = ?");
    
    if ($stmt->execute([$id_asiento])) {
        echo json_encode(["message" => "Asiento eliminado correctamente."]);
    } else {
        echo json_encode(["error" => "Error al eliminar el asiento."]);
    }
}
?>
