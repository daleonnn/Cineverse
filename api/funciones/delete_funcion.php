<?php
require_once "../db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_funcion = $_POST["id_funcion"];

    $stmt = $pdo->prepare("DELETE FROM funciones WHERE id_funcion = ?");
    if ($stmt->execute([$id_funcion])) {
        echo json_encode(["message" => "Función eliminada con éxito"]);
    } else {
        echo json_encode(["error" => "Error al eliminar la función"]);
    }
}
?>
