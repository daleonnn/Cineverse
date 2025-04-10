<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Recuperar Contraseña</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to right,rgb(85, 9, 9),rgb(107, 8, 8));
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }

    .cuadro-recuperar {
      background-color: burlywood;
      padding: 30px;
      border-radius: 20px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      width: 90%;
      max-width: 400px;
      text-align: center;
    }

    .cuadro-recuperar h2 {
      margin-bottom: 20px;
      color: #333;
    }

    .cuadro-recuperar input {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 10px;
      margin-bottom: 15px;
      font-size: 16px;
    }

    .cuadro-recuperar button {
      background-color:rgb(51, 2, 13);
      color: white;
      padding: 10px 15px;
      border: none;
      border-radius: 10px;
      cursor: pointer;
      font-size: 16px;
      transition: background-color 0.3s ease;
    }

    .cuadro-recuperar button:hover {
      background-color: #ff6f8d;
    }
  </style>
</head>
<body>

  <div class="cuadro-recuperar">
    <!-- Formulario HTML -->
    <form action="http://<?php echo $_SERVER['HTTP_HOST']; ?>/cineapp/api/usuarios/recuperar.php" method="POST">   
      <h2>Recuperar Contraseña</h2>
      <input type="email" name="email" placeholder="Tu email" required>
      <button type="submit">Enviar Enlace</button>
    </form>
  </div>

</body>
</html>
