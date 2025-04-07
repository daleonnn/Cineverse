<?php
require_once "../db.php";

if (isset($_GET["id"])) {
    $id_funcion = $_GET["id"];
    $stmt = $pdo->prepare("SELECT * FROM funciones WHERE id_funcion = ?");
    $stmt->execute([$id_funcion]);
    $funcion = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode($funcion ?: ["error" => "Función no encontrada"]);
}
?>
