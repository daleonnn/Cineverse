<?php
require_once "../db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_asiento = $_POST["id_asiento"];
    $id_sala = $_POST["id_sala"];
    $numero = $_POST["numero"];
    $estado = $_POST["estado"];

    $stmt = $pdo->prepare("UPDATE asientos SET id_sala = ?, numero = ?, estado = ? WHERE id_asiento = ?");
    
    if ($stmt->execute([$id_sala, $numero, $estado, $id_asiento])) {
        echo json_encode(["message" => "Asiento actualizado correctamente."]);
    } else {
        echo json_encode(["error" => "Error al actualizar el asiento."]);
    }
}
?>
