
<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__.'/register_errors.log');

// Configuración de CORS (para desarrollo)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

require_once __DIR__ . '/../db.php';

// Función para respuestas estandarizadas
function jsonResponse($success, $message = '', $data = []) {
    http_response_code($success ? 200 : 400);
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data
    ]);
    exit;
}

try {
    // Solo permitir método POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        jsonResponse(false, 'Método no permitido');
    }

    // Obtener datos del formulario
    $input = json_decode(file_get_contents('php://input'), true);
    $data = $input ?: $_POST; // Compatible con JSON y FormData

    // Validar campos requeridos
    $requiredFields = ['nombre', 'email', 'password'];
    foreach ($requiredFields as $field) {
        if (empty($data[$field])) {
            jsonResponse(false, "El campo $field es requerido");
        }
    }

    // Sanitizar y validar datos
    $nombre = trim($data['nombre']);
    $email = trim($data['email']);
    $password = $data['password'];

    // Validar formato de email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        jsonResponse(false, 'El formato del email es inválido');
    }

    // Validar fortaleza de contraseña
    if (strlen($password) < 8) {
        jsonResponse(false, 'La contraseña debe tener al menos 8 caracteres');
    }

    // Verificar si el email ya existe
    $stmt = $pdo->prepare("SELECT id_usuario FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    
    if ($stmt->rowCount() > 0) {
        jsonResponse(false, 'El email ya está registrado');
    }

    // Crear hash de contraseña
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // Insertar nuevo usuario
    $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, email, contrasena) VALUES (?, ?, ?)");
    $stmt->execute([$nombre, $email, $passwordHash]);

    // Verificar inserción exitosa
    if ($stmt->rowCount() === 0) {
        jsonResponse(false, 'Error al registrar el usuario');
    }

    // Éxito
    jsonResponse(true, 'Registro exitoso', [
        'user_id' => $pdo->lastInsertId(),
        'nombre' => $nombre,
        'email' => $email
    ]);

} catch (PDOException $e) {
    error_log("Error PDO: " . $e->getMessage());
    jsonResponse(false, 'Error en la base de datos');
} catch (Exception $e) {
    error_log("Error: " . $e->getMessage());
    jsonResponse(false, $e->getMessage());
}