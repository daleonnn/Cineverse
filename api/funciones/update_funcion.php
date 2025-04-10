<?php
require_once "../db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_funcion = $_POST["id_funcion"];
    $id_pelicula = $_POST["id_pelicula"];
    $fecha_hora = $_POST["fecha_hora"];
    $id_sala = $_POST["id_sala"];
    $precio = $_POST["precio"];

    $stmt = $pdo->prepare("UPDATE funciones SET id_pelicula = ?, fecha_hora = ?, precio = ?, id_sala = ? WHERE id_funcion = ?");
    if ($stmt->execute([$id_pelicula, $fecha_hora, $precio, $id_sala, $id_funcion,])) {
        echo json_encode(["message" => "Función actualizada con éxito"]);
    } else {
        echo json_encode(["error" => "Error al actualizar la función"]);
    }
}
?>
