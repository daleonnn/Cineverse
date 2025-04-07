<?php
session_start();
require_once "../db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // 1. Consulta modificada para incluir el avatar
    $stmt = $pdo->prepare("SELECT id_usuario, nombre, contrasena, avatar FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user["contrasena"])) {
        // 2. Guarda el avatar en la sesión
        $_SESSION["user_id"] = $user["id_usuario"];
        $_SESSION["user_name"] = $user["nombre"];
        $_SESSION["user_email"] = $email;
        $_SESSION["user_data"] = $user;
        $_SESSION["user_avatar"] = $user["avatar"]; // ← Nuevo campo
        
        echo json_encode([
            "status" => "success", 
            "user" => $user,
            "avatar" => $user["avatar"], // ← Opcional para el frontend
            "redirect" => "../usuarios/loginiciado.php"
        ]);
    
    } else {
        echo json_encode(["status" => "error", "message" => "Credenciales incorrectas"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Método no permitido"]);
}
?>