<?php
session_start();
require '../api/db.php';

// Verificar sesión
if (!isset($_SESSION['user_id'])) {
    header("Location: ../usuarios/login.php");
    exit;
}

if (empty($_GET['id_orden'])) {
    header("Location: ../usuarios/loginiciado.php");
    exit;
}

$id_orden = intval($_GET['id_orden']);
$user_id = $_SESSION['user_id'];

// Obtener información principal de la orden
$stmt = $pdo->prepare("
    SELECT o.*, r.id_funcion, r.id_asiento, 
           f.fecha_hora, f.precio as precio_funcion, p.titulo, p.imagen_nombre,
           s.nombre as sala_nombre, a.fila, a.numero
    FROM ordenes_compra o
    JOIN reservaciones r ON o.id_reservacion = r.id_reservacion
    JOIN funciones f ON r.id_funcion = f.id_funcion
    JOIN peliculas p ON f.id_pelicula = p.id_pelicula
    JOIN salas s ON f.id_sala = s.id_sala
    JOIN asientos a ON r.id_asiento = a.id_asiento
    WHERE o.id_orden = ? AND o.id_usuario = ?
    ORDER BY o.fecha_pago DESC
    LIMIT 1
");
$stmt->execute([$id_orden, $user_id]);
$orden = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$orden) {
    header("Location: ../usuarios/loginiciado.php");
    exit;
}

// Obtener todos los asientos reservados
$stmt = $pdo->prepare("
    SELECT a.fila, a.numero 
    FROM reservaciones r
    JOIN asientos a ON r.id_asiento = a.id_asiento
    WHERE r.id_usuario = ? AND r.id_funcion = ?
    ORDER BY a.fila, a.numero
");
$stmt->execute([$user_id, $orden['id_funcion']]);
$asientos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener todos los combos comprados
$stmt = $pdo->prepare("
    SELECT c.id_combo, c.nombre, c.precio, o.total as subtotal, 
           (o.total / c.precio) as cantidad
    FROM ordenes_compra o
    JOIN combos c ON o.id_combo = c.id_combo
    WHERE o.id_reservacion = ? AND o.id_combo IS NOT NULL
");
$stmt->execute([$orden['id_reservacion']]);
$combos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calcular totales correctamente
$total_entradas = count($asientos) * $orden['precio_funcion'];
$total_combos = array_sum(array_column($combos, 'subtotal'));
$gran_total = $total_entradas + $total_combos;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura - <?= htmlspecialchars($orden['titulo']) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --color-primary: #6C63FF;
            --color-secondary: #4A8FE7;
            --color-dark: #2C2C54;
            --color-light: #F8F9FA;
            --color-success: #4CAF50;
            --color-text: #333333;
            --color-border: #E0E0E0;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #F5F7FA;
            color: var(--color-text);
            line-height: 1.6;
            padding: 20px;
        }
        
        .factura-container {
            max-width: 800px;
            margin: 30px auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }
        
        .header-factura {
            background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));
            color: white;
            padding: 25px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo-cine {
            font-size: 24px;
            font-weight: 700;
            letter-spacing: 1px;
        }
        
        .numero-factura {
            font-size: 18px;
            background: rgba(255, 255, 255, 0.2);
            padding: 8px 15px;
            border-radius: 20px;
        }
        
        .resumen-header {
            display: flex;
            gap: 25px;
            padding: 30px;
            border-bottom: 1px solid var(--color-border);
        }
        
        .poster-pelicula {
            width: 140px;
            height: 200px;
            border-radius: 8px;
            object-fit: cover;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .info-pelicula {
            flex-grow: 1;
        }
        
        .info-pelicula h2 {
            color: var(--color-dark);
            margin-bottom: 10px;
            font-weight: 600;
        }
        
        .info-pelicula p {
            margin-bottom: 8px;
            font-size: 15px;
        }
        
        .info-pelicula strong {
            color: var(--color-dark);
            font-weight: 500;
        }
        
        .detalle-factura {
            padding: 30px;
        }
        
        .seccion-factura {
            margin-bottom: 35px;
        }
        
        .seccion-factura h3 {
            color: var(--color-primary);
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--color-border);
            font-weight: 600;
            font-size: 18px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        
        th {
            background-color: var(--color-light);
            color: var(--color-dark);
            font-weight: 500;
            text-align: left;
            padding: 12px 15px;
            border-bottom: 2px solid var(--color-border);
        }
        
        td {
            padding: 12px 15px;
            border-bottom: 1px solid var(--color-border);
        }
        
        tr:last-child td {
            border-bottom: none;
        }
        
        .total-factura {
            background: var(--color-light);
            padding: 20px;
            border-radius: 8px;
            margin-top: 30px;
        }
        
        .total-line {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 16px;
        }
        
        .total-line.grand-total {
            font-size: 20px;
            font-weight: 600;
            color: var(--color-primary);
            margin-top: 15px;
            padding-top: 15px;
            border-top: 2px solid var(--color-border);
        }
        
        .footer-factura {
            text-align: center;
            padding: 20px;
            color: #666;
            font-size: 14px;
            border-top: 1px solid var(--color-border);
        }
        
        .botones-accion {
            display: flex;
            justify-content: center;
            gap: 20px;
            padding: 0 30px 30px;
        }
        
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 25px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s ease;
            border: none;
        }
        
        .btn-primary {
            background-color: var(--color-primary);
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #5A52E0;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(108, 99, 255, 0.3);
        }
        
        .btn-secondary {
            background-color: var(--color-secondary);
            color: white;
        }
        
        .btn-secondary:hover {
            background-color: #3A7BD5;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(74, 143, 231, 0.3);
        }
        
        @media (max-width: 768px) {
            .resumen-header {
                flex-direction: column;
            }
            
            .poster-pelicula {
                width: 120px;
                margin: 0 auto 20px;
            }
            
            .botones-accion {
                flex-direction: column;
                gap: 15px;
            }
            
            .btn {
                width: 100%;
            }
        }
        
        @media print {
            body {
                background: white;
                padding: 0;
            }
            
            .botones-accion {
                display: none;
            }
        }
    </style>
</head>
<body>

<div class="factura-container">
    <div class="header-factura">
        <div class="logo-cine">CINEVERSE</div>
        <div class="numero-factura">Factura #<?= str_pad($orden['id_orden'], 6, '0', STR_PAD_LEFT) ?></div>
    </div>
    
    <div class="resumen-header">
        <img src="/cineapp/assets/img/peliculas/<?= htmlspecialchars($orden['imagen_nombre']) ?>" 
             alt="<?= htmlspecialchars($orden['titulo']) ?>" 
             class="poster-pelicula">
        
        <div class="info-pelicula">
            <h2><?= htmlspecialchars($orden['titulo']) ?></h2>
            <p><strong>Sala:</strong> <?= htmlspecialchars($orden['sala_nombre']) ?></p>
            <p><strong>Fecha:</strong> <?= date('d/m/Y', strtotime($orden['fecha_hora'])) ?></p>
            <p><strong>Hora:</strong> <?= date('H:i', strtotime($orden['fecha_hora'])) ?></p>
            <p><strong>Asientos:</strong> 
                <?php 
                $asientos_str = [];
                foreach ($asientos as $asiento) {
                    $asientos_str[] = $asiento['fila'] . $asiento['numero'];
                }
                echo implode(', ', $asientos_str);
                ?>
            </p>
        </div>
    </div>
    
    <div class="detalle-factura">
        <div class="seccion-factura">
            <h3>Detalle de Entradas</h3>
            <table>
                <thead>
                    <tr>
                        <th>Tipo</th>
                        <th>Cantidad</th>
                        <th>Precio Unitario</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Entrada General</td>
                        <td><?= count($asientos) ?></td>
                        <td>$<?= number_format($orden['precio_funcion'], 0, ',', '.') ?> COP</td>
                        <td>$<?= number_format($total_entradas, 0, ',', '.') ?> COP</td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <?php if (!empty($combos)): ?>
        <div class="seccion-factura">
            <h3>Detalle de Combos</h3>
            <table>
                <thead>
                    <tr>
                        <th>Combo</th>
                        <th>Cantidad</th>
                        <th>Precio Unitario</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($combos as $combo): ?>
                    <tr>
                        <td><?= htmlspecialchars($combo['nombre']) ?></td>
                        <td><?= intval($combo['cantidad']) ?></td>
                        <td>$<?= number_format($combo['precio'], 0, ',', '.') ?> COP</td>
                        <td>$<?= number_format($combo['subtotal'], 0, ',', '.') ?> COP</td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
        
        <div class="total-factura">
            <div class="total-line">
                <span>Subtotal Entradas:</span>
                <span>$<?= number_format($total_entradas, 0, ',', '.') ?> COP</span>
            </div>
            
            <?php if (!empty($combos)): ?>
            <div class="total-line">
                <span>Subtotal Combos:</span>
                <span>$<?= number_format($total_combos, 0, ',', '.') ?> COP</span>
            </div>
            <?php endif; ?>
            
            <div class="total-line grand-total">
                <span>Total a Pagar:</span>
                <span>$<?= number_format($gran_total, 0, ',', '.') ?> COP</span>
            </div>
        </div>
    </div>
    
    <div class="botones-accion">
        <a href="../usuarios/loginiciado.php" class="btn btn-primary">
            <i class="fas fa-home"></i> Volver al Inicio
        </a>
        <button onclick="window.print()" class="btn btn-secondary">
            <i class="fas fa-print"></i> Imprimir Factura
        </button>
    </div>
</div>

</body>
</html>