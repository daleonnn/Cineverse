<?php
session_start();
require '../api/db.php';

// Verificar que el usuario esté logueado
if (!isset($_SESSION['user_id'])) {
    header("Location: ../usuarios/login.php");
    exit;
}

// Obtener datos del usuario
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id_usuario = ?");
$stmt->execute([$user_id]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

// Verificar datos de la reserva
if (empty($_POST['id_funcion']) || empty($_POST['asientos'])) {
    header("Location: ../usuarios/index.php");
    exit;
}

$id_funcion = intval($_POST['id_funcion']);
$asientos_seleccionados = is_array($_POST['asientos']) ? $_POST['asientos'] : explode(',', $_POST['asientos']);

// Obtener combos seleccionados
$combos_seleccionados = [];
if (!empty($_POST['combos'])) {
    // Si los combos vienen como array asociativo (combos[id]=cantidad)
    if (is_array($_POST['combos'])) {
        foreach ($_POST['combos'] as $id_combo => $cantidad) {
            $id_combo = intval($id_combo);
            $cantidad = intval($cantidad);
            if ($id_combo > 0 && $cantidad > 0) {
                $combos_seleccionados[$id_combo] = $cantidad;
            }
        }
    }
    // Si los combos vienen como array simple (combos[]=id)
    else {
        $combos_ids = explode(',', $_POST['combos']);
        foreach ($combos_ids as $id_combo) {
            $id_combo = intval($id_combo);
            if ($id_combo > 0) {
                $combos_seleccionados[$id_combo] = 1; // Cantidad por defecto 1
            }
        }
    }
}

// Obtener información de la función
$stmt = $pdo->prepare("
    SELECT f.*, p.titulo, p.imagen_nombre, s.nombre as sala_nombre, s.id_sala
    FROM funciones f
    JOIN peliculas p ON f.id_pelicula = p.id_pelicula
    JOIN salas s ON f.id_sala = s.id_sala
    WHERE f.id_funcion = ?
");
$stmt->execute([$id_funcion]);
$funcion = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$funcion) {
    header("Location: ../usuarios/loginiciado.php");
    exit;
}

// Calcular totales
$subtotal_asientos = count($asientos_seleccionados) * $funcion['precio'];
$subtotal_combos = 0;

// Obtener información de los combos seleccionados
$combos_info = [];
if (!empty($combos_seleccionados)) {
    $placeholders = str_repeat('?,', count(array_keys($combos_seleccionados)) - 1) . '?';
    $stmt = $pdo->prepare("SELECT * FROM combos WHERE id_combo IN ($placeholders)");
    $stmt->execute(array_keys($combos_seleccionados));
    $combos_info = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($combos_info as $combo) {
        $cantidad = $combos_seleccionados[$combo['id_combo']];
        $subtotal_combos += $combo['precio'] * $cantidad;
    }
}

$total = $subtotal_asientos + $subtotal_combos;

// Procesar el pago cuando se envía el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['procesar_pago'])) {
    // Validar datos de tarjeta
    $numero_tarjeta = str_replace(' ', '', $_POST['numero_tarjeta']);
    if (strlen($numero_tarjeta) !== 16 || !ctype_digit($numero_tarjeta)) {
        $error = "El número de tarjeta debe tener 16 dígitos";
    } 
    elseif (empty($_POST['nombre_tarjeta'])) {
        $error = "Ingrese el nombre en la tarjeta";
    }
    elseif (!preg_match('/^(0[1-9]|1[0-2])\/[0-9]{2}$/', $_POST['fecha_expiracion'])) {
        $error = "Formato de fecha de expiración inválido (MM/AA)";
    }
    elseif (!preg_match('/^[0-9]{3}$/', $_POST['cvv'])) {
        $error = "CVV debe tener 3 dígitos";
    }
    else {
        try {
            $pdo->beginTransaction();
            
            // 1. Crear las reservaciones
            $id_reservacion = null;
            foreach ($asientos_seleccionados as $id_asiento) {
                $stmt = $pdo->prepare("
                    INSERT INTO reservaciones 
                    (id_usuario, id_funcion, id_asiento, fecha_reserva, estado) 
                    VALUES (?, ?, ?, NOW(), 'confirmada')
                ");
                $stmt->execute([$user_id, $id_funcion, $id_asiento]);
                
                if ($id_reservacion === null) {
                    $id_reservacion = $pdo->lastInsertId();
                }
                
                // Actualizar estado del asiento
                $stmt = $pdo->prepare("UPDATE asientos SET estado = 'ocupado' WHERE id_asiento = ?");
                $stmt->execute([$id_asiento]);
            }
            
            // 2. Crear la orden de compra principal
            $stmt = $pdo->prepare("
                INSERT INTO ordenes_compra 
                (id_usuario, id_reservacion, total, estado_pago, fecha_pago) 
                VALUES (?, ?, ?, 'completado', NOW())
            ");
            $stmt->execute([$user_id, $id_reservacion, $total]);
            $id_orden = $pdo->lastInsertId();
            
            // 3. Agregar combos a la orden si existen
            if (!empty($combos_seleccionados)) {
                foreach ($combos_info as $combo) {
                    $cantidad = $combos_seleccionados[$combo['id_combo']];
                    $subtotal = $combo['precio'] * $cantidad;
                    
                    $stmt = $pdo->prepare("
                        INSERT INTO ordenes_compra 
                        (id_usuario, id_reservacion, id_combo, total, estado_pago, fecha_pago) 
                        VALUES (?, ?, ?, ?, 'completado', NOW())
                    ");
                    $stmt->execute([$user_id, $id_reservacion, $combo['id_combo'], $subtotal]);
                }
            }
            
            // 4. Crear registro de pago
            $stmt = $pdo->prepare("
                INSERT INTO pagos 
                (id_orden, monto, metodo_pago, codigo_qr) 
                VALUES (?, ?, ?, ?)
            ");
            $stmt->execute([
                $id_orden,
                $total,
                'tarjeta',
                'qr_generado_' . time()
            ]);
            
            $pdo->commit();
            
            // Redirigir a página de confirmación
            header("Location: confirmacion.php?id_orden=$id_orden");
            exit;
            
        } catch (Exception $e) {
            $pdo->rollBack();
            $error = "Error al procesar el pago: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proceso de Pago - <?= htmlspecialchars($funcion['titulo']) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #100919;
            color: #D6D0E0;
            padding: 20px;
        }
        
        .pago-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            gap: 30px;
        }
        
        .resumen-compra {
            flex: 1;
            background: rgba(44, 35, 89, 0.8);
            border-radius: 10px;
            padding: 30px;
        }
        
        .formulario-pago {
            flex: 1;
            background: rgba(44, 35, 89, 0.8);
            border-radius: 10px;
            padding: 30px;
        }
        
        h1, h2 {
            color: white;
            margin-bottom: 20px;
        }
        
        .reserva-header {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .reserva-poster {
            width: 120px;
            flex-shrink: 0;
        }
        
        .reserva-poster img {
            width: 100%;
            border-radius: 8px;
        }
        
        .reserva-info {
            flex-grow: 1;
        }
        
        .reserva-info h2 {
            font-size: 1.3rem;
            margin-bottom: 10px;
        }
        
        .reserva-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 15px;
        }
        
        .reserva-meta-item {
            background: rgba(58, 47, 122, 0.5);
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 0.9rem;
        }
        
        .resumen-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .resumen-total {
            font-weight: bold;
            font-size: 1.2rem;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 2px solid #4a8fe7;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            color: white;
        }
        
        input[type="text"],
        input[type="number"] {
            width: 100%;
            padding: 12px;
            border-radius: 5px;
            border: 1px solid #4a8fe7;
            background: rgba(255, 255, 255, 0.1);
            color: white;
            font-size: 16px;
        }
        
        .tarjeta-datos {
            display: flex;
            gap: 15px;
            margin-top: 15px;
        }
        
        .tarjeta-datos .form-group {
            flex: 1;
        }
        
        .btn-pagar {
            background: #4CAF50;
            color: white;
            border: none;
            padding: 15px 25px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease;
            width: 100%;
            margin-top: 20px;
        }
        
        .btn-pagar:hover {
            background: #3e8e41;
        }
        
        .asientos-seleccionados {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin: 10px 0;
        }
        
        .asiento-seleccionado {
            background: #4a8fe7;
            padding: 4px 8px;
            border-radius: 15px;
            font-size: 0.8rem;
        }
        
        .combo-seleccionado {
            font-size: 0.9rem;
            margin-bottom: 5px;
        }
        
        .error {
            color: #ff6b6b;
            margin-bottom: 15px;
            padding: 10px;
            background: rgba(255, 0, 0, 0.1);
            border-radius: 5px;
        }
        
        @media (max-width: 768px) {
            .pago-container {
                flex-direction: column;
            }
            
            .reserva-header {
                flex-direction: column;
            }
            
            .reserva-poster {
                width: 100px;
                margin: 0 auto;
            }
        }
    </style>
</head>
<body>

<div class="pago-container">
    <div class="resumen-compra">
        <h1>Resumen de tu compra</h1>
        
        <div class="reserva-header">
            <div class="reserva-poster">
                <img src="/cineapp/assets/img/peliculas/<?= htmlspecialchars($funcion['imagen_nombre']) ?>" 
                     alt="<?= htmlspecialchars($funcion['titulo']) ?>">
            </div>
            
            <div class="reserva-info">
                <h2><?= htmlspecialchars($funcion['titulo']) ?></h2>
                
                <div class="reserva-meta">
                    <div class="reserva-meta-item">
                        <h3>Sala</h3>
                        <p><?= htmlspecialchars($funcion['sala_nombre']) ?></p>
                    </div>
                    
                    <div class="reserva-meta-item">
                        <h3>Fecha y Hora</h3>
                        <p><?= date('d/m/Y H:i', strtotime($funcion['fecha_hora'])) ?></p>
                    </div>
                </div>
                
                <div class="reserva-meta-item">
                    <h3>Asientos seleccionados</h3>
                    <div class="asientos-seleccionados">
                        <?php foreach ($asientos_seleccionados as $id_asiento): 
                            $stmt = $pdo->prepare("SELECT * FROM asientos WHERE id_asiento = ?");
                            $stmt->execute([$id_asiento]);
                            $asiento = $stmt->fetch(PDO::FETCH_ASSOC);
                        ?>
                            <span class="asiento-seleccionado">
                                <?= htmlspecialchars($asiento['fila']) ?><?= htmlspecialchars($asiento['numero']) ?>
                            </span>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="resumen-item">
            <span>Asientos (<?= count($asientos_seleccionados) ?>):</span>
            <span>$<?= number_format($subtotal_asientos, 0, '', '.') ?> COP</span>
        </div>
        
        <?php if (!empty($combos_info)): ?>
            <h3>Combos seleccionados</h3>
            <?php foreach ($combos_info as $combo): 
                $cantidad = $combos_seleccionados[$combo['id_combo']];
                if ($cantidad > 0):
            ?>
                <div class="resumen-item">
                    <span class="combo-seleccionado">
                        <?= htmlspecialchars($combo['nombre']) ?> (<?= $cantidad ?>)
                    </span>
                    <span>$<?= number_format($combo['precio'] * $cantidad, 0, '', '.') ?> COP</span>
                </div>
            <?php endif; endforeach; ?>
        <?php endif; ?>
        
        <div class="resumen-item resumen-total">
            <span>Total a pagar:</span>
            <span>$<?= number_format($total, 0, '', '.') ?> COP</span>
        </div>
    </div>
    
    <form method="post" class="formulario-pago">
        <input type="hidden" name="id_funcion" value="<?= $id_funcion ?>">
        
        <?php foreach ($asientos_seleccionados as $id_asiento): ?>
            <input type="hidden" name="asientos[]" value="<?= $id_asiento ?>">
        <?php endforeach; ?>
        
        <?php foreach ($combos_seleccionados as $id_combo => $cantidad): ?>
            <input type="hidden" name="combos[<?= $id_combo ?>]" value="<?= $cantidad ?>">
        <?php endforeach; ?>
        
        <h2>Información de pago</h2>
        
        <?php if (isset($error)): ?>
            <div class="error">
                <?= $error ?>
            </div>
        <?php endif; ?>
        
        <div class="form-group">
            <label for="numero_tarjeta">Número de tarjeta</label>
            <input type="text" id="numero_tarjeta" name="numero_tarjeta" 
                   placeholder="1234 5678 9012 3456" required
                   maxlength="19"
                   value="<?= isset($_POST['numero_tarjeta']) ? htmlspecialchars($_POST['numero_tarjeta']) : '' ?>">
        </div>
        
        <div class="form-group">
            <label for="nombre_tarjeta">Nombre en la tarjeta</label>
            <input type="text" id="nombre_tarjeta" name="nombre_tarjeta" 
                   placeholder="JUAN PEREZ" required
                   value="<?= isset($_POST['nombre_tarjeta']) ? htmlspecialchars($_POST['nombre_tarjeta']) : '' ?>">
        </div>
        
        <div class="tarjeta-datos">
            <div class="form-group">
                <label for="fecha_expiracion">Fecha de expiración</label>
                <input type="text" id="fecha_expiracion" name="fecha_expiracion" 
                       placeholder="MM/AA" required
                       maxlength="5"
                       value="<?= isset($_POST['fecha_expiracion']) ? htmlspecialchars($_POST['fecha_expiracion']) : '' ?>">
            </div>
            
            <div class="form-group">
                <label for="cvv">CVV</label>
                <input type="text" id="cvv" name="cvv" 
                       placeholder="123" required
                       maxlength="3"
                       value="<?= isset($_POST['cvv']) ? htmlspecialchars($_POST['cvv']) : '' ?>">
            </div>
        </div>
        
        <button type="submit" name="procesar_pago" class="btn-pagar">
            Confirmar y Pagar
        </button>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Formatear número de tarjeta (agregar espacios cada 4 dígitos)
    const numeroTarjeta = document.getElementById('numero_tarjeta');
    numeroTarjeta.addEventListener('input', function(e) {
        let value = this.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
        let formatted = '';
        
        for (let i = 0; i < value.length && i < 16; i++) {
            if (i > 0 && i % 4 === 0) {
                formatted += ' ';
            }
            formatted += value[i];
        }
        
        this.value = formatted;
    });

    // Validar que solo números en CVV
    document.getElementById('cvv').addEventListener('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
    });

    // Formatear fecha de expiración (MM/AA)
    document.getElementById('fecha_expiracion').addEventListener('input', function(e) {
        let value = this.value.replace(/[^0-9]/g, '');
        
        if (value.length > 2) {
            this.value = value.substring(0, 2) + '/' + value.substring(2, 4);
        }
    });
});
</script>

</body>
</html>