<?php
// Configuración inicial
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Variables para mostrar mensajes en la interfaz
$mensaje = '';
$tipo_mensaje = ''; // 'exito' o 'error'

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $email = $_POST['email'];

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $mensaje = "❌ Email inválido.";
            $tipo_mensaje = 'error';
        } else {
            $pdo = new PDO('mysql:host=localhost;dbname=cinedb;charset=utf8', 'root', '');
            $stmt = $pdo->prepare("SELECT id_usuario FROM usuarios WHERE email = ?");
            $stmt->execute([$email]);
            $usuario = $stmt->fetch();

            if (!$usuario) {
                $mensaje = "❌ Email no registrado.";
                $tipo_mensaje = 'error';
            } else {
                // Generar token y guardar
                $token = bin2hex(random_bytes(32));
                $expiracion = date('Y-m-d H:i:s', strtotime('+1 hour'));

                $stmt = $pdo->prepare("UPDATE usuarios SET token_recuperacion = ?, token_expiracion = ? WHERE id_usuario = ?");
                $stmt->execute([$token, $expiracion, $usuario['id_usuario']]);

                // Preparar y enviar el correo
                require '../../libs/email.php';
                $enlace_recuperacion = "http://localhost/cineapp/usuarios/resetear.php?token=".$token;
                $asunto = "Recupera tu contraseña en CineApp";
                $cuerpo = "
                    <h2>¡Hola!</h2>
                    <p>Haz clic en este enlace para restablecer tu contraseña:</p>
                    <a href='$enlace_recuperacion'>Restablecer contraseña</a>
                    <p><small>El enlace expira en 1 hora.</small></p>
                ";

                if (enviarEmail($email, $asunto, $cuerpo)) {
                    $mensaje = "✅ Se envió un correo a <strong>$email</strong>. Revisa tu bandeja de entrada (y spam).";
                    $tipo_mensaje = 'exito';
                } else {
                    $mensaje = "❌ Error al enviar el correo.";
                    $tipo_mensaje = 'error';
                }
            }
        }
    } catch (Exception $e) {
        $mensaje = "❌ Error del servidor: " . $e->getMessage();
        $tipo_mensaje = 'error';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Recuperar Contraseña</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
  body {
    font-family: 'Segoe UI', sans-serif;
    background-color: #f4f4f4;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
  }

  .cuadro-recuperar {
    background-color: #fff;
    padding: 35px;
    border-radius: 16px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    width: 90%;
    max-width: 400px;
    text-align: center;
  }

  .cuadro-recuperar h2 {
    margin-bottom: 25px;
    color: #333;
    font-weight: 500;
  }

  .cuadro-recuperar input {
    width: 100%;
    padding: 12px;
    border: 1px solid #ccc;
    border-radius: 8px;
    margin-bottom: 20px;
    font-size: 15px;
    transition: border-color 0.2s;
  }

  .cuadro-recuperar input:focus {
    border-color: #999;
    outline: none;
  }

  .cuadro-recuperar button {
    background-color: #333;
    color: white;
    padding: 12px 20px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 15px;
    transition: background-color 0.3s ease;
  }

  .cuadro-recuperar button:hover {
    background-color: #000;
  }

  .mensaje {
    padding: 12px 16px;
    border-radius: 8px;
    margin-bottom: 20px;
    font-size: 14px;
    text-align: left;
  }

  .mensaje.exito {
    background-color: #e6f4ea;
    color: #226d33;
    border-left: 4px solid #2e7d32;
  }

  .mensaje.error {
    background-color: #fbeaea;
    color: #b3261e;
    border-left: 4px solid #c62828;
  }
</style>

</head>
<body>

  <div class="cuadro-recuperar">
    <?php if ($mensaje): ?>
      <div class="mensaje <?php echo $tipo_mensaje; ?>">
        <?php echo $mensaje; ?>
      </div>
    <?php endif; ?>

    <form method="POST">
      <h2>Recuperar Contraseña</h2>
      <input type="email" name="email" placeholder="Tu email" required>
      <button type="submit">Enviar Enlace</button>
    </form>
  </div>

</body>
</html>
