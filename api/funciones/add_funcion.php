<?php
require_once "../db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_pelicula = $_POST["id_pelicula"];
    $fecha_hora = $_POST["fecha_hora"];
    $id_sala = $_POST["id_sala"];
    $precio = $_POST["precio"];


    $stmt = $pdo->prepare("INSERT INTO funciones (id_pelicula, fecha_hora, precio, id_sala) VALUES (?, ?, ?, ?)");
    if ($stmt->execute([$id_pelicula, $fecha_hora, $precio, $id_sala])) {
        echo json_encode(["message" => "Función agregada con éxito"]);
    } else {
        echo json_encode(["error" => "Error al agregar la función"]);
    }
}
?>