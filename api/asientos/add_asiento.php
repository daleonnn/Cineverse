<?php
require_once "../db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_sala = $_POST["id_sala"];
    $numero = $_POST["numero"];
    $estado = $_POST["estado"];

    $stmt = $pdo->prepare("INSERT INTO asientos (id_sala, numero, estado) VALUES (?, ?, ?)");
    
    if ($stmt->execute([$id_sala, $numero, $estado])) {
        echo json_encode(["message" => "Asiento agregado correctamente."]);
    } else {
        echo json_encode(["error" => "Error al agregar el asiento."]);
    }
}
?>
