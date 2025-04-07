<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '/xampp/htdocs/cineapp/vendor/autoload.php'; // Asegúrate que esta ruta es correcta

function enviarEmail($destinatario, $asunto, $cuerpo) {
    $mail = new PHPMailer(true);
    
    try {
        // Configuración SMTP (para Gmail)
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'dleon2053@gmail.com'; // Tu correo
        $mail->Password = 'rbxr fjaa tyrp zkgu'; // Contraseña de aplicación
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Remitente y destinatario
        $mail->setFrom('tu_email@gmail.com', 'CineApp');
        $mail->addAddress($destinatario);

        // Contenido
        $mail->isHTML(true);
        $mail->Subject = $asunto;
        $mail->Body = $cuerpo;

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Error al enviar email: " . $mail->ErrorInfo);
        return false;
    }
}
?>