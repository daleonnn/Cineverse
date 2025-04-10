<?php
session_start();
require_once "../db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Acceso directo para el administrador (solo desarrollo)
    if ($email === "administrador@gmail.com" && $password === "123") {
        $_SESSION["user_id"] = 0;
        $_SESSION["user_name"] = "Administrador";
        $_SESSION["user_email"] = $email;
        $_SESSION["user_avatar"] = "default_admin.jpg";
        
        echo json_encode([
            "status" => "success",
            "redirect" => "/cineapp/admin/index.php"  // Ruta absoluta desde la raíz
        ]);
        exit;
    }

    // Proceso normal para otros usuarios
    $stmt = $pdo->prepare("SELECT id_usuario, nombre, contrasena, avatar FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user["contrasena"])) {
        $_SESSION["user_id"] = $user["id_usuario"];
        $_SESSION["user_name"] = $user["nombre"];
        $_SESSION["user_email"] = $email;
        $_SESSION["user_data"] = $user;
        $_SESSION["user_avatar"] = $user["avatar"];
        
        echo json_encode([
            "status" => "success", 
            "user" => $user,
            "avatar" => $user["avatar"],
            "redirect" => "/cineapp/usuarios/loginiciado.php"  // Ruta absoluta
        ]);
    } else {
        echo json_encode(["status" => "error", "message" => "Credenciales incorrectas"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Método no permitido"]);
}
?>