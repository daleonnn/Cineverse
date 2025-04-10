<?php
session_start();
require './header-iniciado.php';
require '../api/db.php';

// Verificar que los datos de la reserva estén presentes
if (empty($_POST['id_funcion']) || empty($_POST['asientos'])) {
    header("Location: index.php");
    exit;
}

// Obtener datos de la función
$id_funcion = intval($_POST['id_funcion']);
$asientos_seleccionados = is_array($_POST['asientos']) ? $_POST['asientos'] : explode(',', $_POST['asientos']);

// Consultar la función
$stmt = $pdo->prepare("
    SELECT f.*, p.titulo, p.imagen_nombre, s.nombre as sala_nombre 
    FROM funciones f
    JOIN peliculas p ON f.id_pelicula = p.id_pelicula
    JOIN salas s ON f.id_sala = s.id_sala
    WHERE f.id_funcion = ?
");
$stmt->execute([$id_funcion]);
$funcion = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$funcion) {
    header("Location: index.php");
    exit;
}

// Obtener todos los combos disponibles
$stmt = $pdo->query("SELECT * FROM combos ORDER BY nombre");
$combos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calcular subtotal de asientos
$subtotal_asientos = count($asientos_seleccionados) * $funcion['precio'];
$subtotal_combos = 0;
$total = $subtotal_asientos;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seleccionar Combos - <?= htmlspecialchars($funcion['titulo']) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --primary: #6C4DF6;
            --primary-dark: #5A3DDE;
            --secondary: #FF7D59;
            --dark: #0F0A28;
            --darker: #0A061D;
            --light: #F8F5FF;
            --gray: #A5A3B2;
            --success: #4CAF50;
            --success-dark: #3e8e41;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--darker);
            color: var(--light);
            line-height: 1.6;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }
        
        /* Header de reserva */
        .reserva-header {
            display: flex;
            gap: 2.5rem;
            margin-bottom: 3rem;
            background: linear-gradient(135deg, rgba(31, 24, 72, 0.8) 0%, rgba(15, 10, 40, 0.9) 100%);
            padding: 2rem;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            position: relative;
            overflow: hidden;
        }
        
        .reserva-poster {
            width: 220px;
            flex-shrink: 0;
            position: relative;
            z-index: 1;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.5);
            transition: transform 0.3s ease;
        }
        
        .reserva-poster:hover {
            transform: scale(1.03);
        }
        
        .reserva-poster img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }
        
        .reserva-info {
            flex-grow: 1;
            position: relative;
            z-index: 1;
        }
        
        .reserva-info h1 {
            color: white;
            font-size: 2.2rem;
            margin-bottom: 1rem;
            font-weight: 700;
            background: linear-gradient(to right, #fff, #C1B8F3);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .reserva-meta {
            display: flex;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }
        
        .reserva-meta-item {
            background: rgba(108, 77, 246, 0.15);
            backdrop-filter: blur(5px);
            padding: 0.8rem 1.2rem;
            border-radius: 10px;
            border: 1px solid rgba(108, 77, 246, 0.3);
        }
        
        .reserva-meta-item h3 {
            font-size: 0.8rem;
            color: var(--gray);
            margin-bottom: 0.3rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .reserva-meta-item p {
            font-size: 1rem;
            color: white;
            font-weight: 600;
        }
        
        .asientos-seleccionados {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-top: 0.5rem;
        }
        
        .asiento-seleccionado {
            background: var(--primary);
            color: white;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            display: flex;
            align-items: center;
        }
        
        /* Sección de combos */
        .section-title {
            font-size: 1.8rem;
            color: white;
            margin-bottom: 1.5rem;
            position: relative;
            padding-bottom: 0.5rem;
            font-weight: 600;
        }
        
        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 4px;
            background: linear-gradient(to right, var(--primary), var(--secondary));
            border-radius: 2px;
        }
        
        .combos-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-top: 1.5rem;
        }
        
        .combo-card {
            background: linear-gradient(145deg, rgba(31, 24, 72, 0.8) 0%, rgba(15, 10, 40, 0.9) 100%);
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s ease;
            border: 1px solid rgba(108, 77, 246, 0.2);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        
        .combo-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(108, 77, 246, 0.3);
            border-color: rgba(108, 77, 246, 0.4);
        }
        
        .combo-imagen {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-bottom: 1px solid rgba(108, 77, 246, 0.2);
        }
        
        .combo-info {
            padding: 1.5rem;
        }
        
        .combo-nombre {
            color: white;
            font-size: 1.2rem;
            margin-bottom: 0.8rem;
            font-weight: 600;
        }
        
        .combo-descripcion {
            font-size: 0.9rem;
            margin-bottom: 1.2rem;
            color: var(--gray);
        }
        
        .combo-precio {
            font-weight: 700;
            color: var(--secondary);
            margin-bottom: 1.5rem;
            display: block;
            font-size: 1.1rem;
        }
        
        .combo-controls {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .cantidad-control {
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }
        
        .btn-cantidad {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: var(--primary);
            color: white;
            border: none;
            font-size: 1rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }
        
        .btn-cantidad:hover {
            background: var(--primary-dark);
            transform: scale(1.1);
        }
        
        .btn-cantidad:active {
            transform: scale(0.95);
        }
        
        .cantidad-value {
            width: 36px;
            text-align: center;
            font-weight: 600;
            font-size: 1rem;
        }
        
        /* Resumen de compra */
        .resumen-compra {
            background: linear-gradient(145deg, rgba(31, 24, 72, 0.8) 0%, rgba(15, 10, 40, 0.9) 100%);
            border-radius: 12px;
            padding: 2rem;
            margin-top: 3rem;
            border: 1px solid rgba(108, 77, 246, 0.2);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        
        .resumen-compra h2 {
            color: white;
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
            font-weight: 600;
            position: relative;
            padding-bottom: 0.5rem;
        }
        
        .resumen-compra h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 3px;
            background: var(--primary);
            border-radius: 2px;
        }
        
        .resumen-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.8rem;
            padding-bottom: 0.8rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .resumen-total {
            font-weight: 700;
            font-size: 1.3rem;
            margin-top: 1.2rem;
            padding-top: 1.2rem;
            border-top: 2px solid var(--primary);
            color: white;
        }
        
        .btn-confirmar {
            background: linear-gradient(to right, var(--primary), var(--primary-dark));
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
            margin-top: 1.5rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 15px rgba(108, 77, 246, 0.3);
        }
        
        .btn-confirmar:hover {
            background: linear-gradient(to right, var(--primary-dark), var(--primary));
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(108, 77, 246, 0.4);
        }
        
        .btn-confirmar:active {
            transform: translateY(0);
        }
        
        /* Efectos y animaciones */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .reserva-header, .combos-section, .resumen-compra {
            animation: fadeIn 0.6s ease-out forwards;
        }
        
        .combos-section {
            animation-delay: 0.1s;
        }
        
        .resumen-compra {
            animation-delay: 0.2s;
        }
        
        /* Responsive */
        @media (max-width: 992px) {
            .reserva-header {
                flex-direction: column;
            }
            
            .reserva-poster {
                width: 180px;
                margin: 0 auto 1.5rem;
            }
            
            .reserva-info h1 {
                font-size: 1.8rem;
                text-align: center;
            }
            
            .reserva-meta {
                justify-content: center;
            }
        }
        
        @media (max-width: 768px) {
            .container {
                padding: 1.5rem;
            }
            
            .combos-grid {
                grid-template-columns: 1fr;
            }
            
            .reserva-header {
                padding: 1.5rem;
            }
            
            .section-title {
                font-size: 1.5rem;
            }
        }
        
        @media (max-width: 576px) {
            .reserva-meta {
                flex-direction: column;
                gap: 0.8rem;
            }
            
            .reserva-meta-item {
                width: 100%;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="reserva-header">
        <div class="reserva-poster">
            <img src="/cineapp/assets/img/peliculas/<?= htmlspecialchars($funcion['imagen_nombre']) ?>" 
                 alt="<?= htmlspecialchars($funcion['titulo']) ?>">
        </div>
        
        <div class="reserva-info">
            <h1><?= htmlspecialchars($funcion['titulo']) ?></h1>
            
            <div class="reserva-meta">
                <div class="reserva-meta-item">
                    <h3>Sala</h3>
                    <p><?= htmlspecialchars($funcion['sala_nombre']) ?></p>
                </div>
                
                <div class="reserva-meta-item">
                    <h3>Fecha y Hora</h3>
                    <p><?= date('d/m/Y H:i', strtotime($funcion['fecha_hora'])) ?></p>
                </div>
                
                <div class="reserva-meta-item">
                    <h3>Asientos seleccionados</h3>
                    <div class="asientos-seleccionados">
                        <?php foreach ($asientos_seleccionados as $asiento): ?>
                            <?php if (is_array($asiento)): ?>
                                <span class="asiento-seleccionado"><?= htmlspecialchars($asiento['fila']) ?><?= htmlspecialchars($asiento['numero']) ?></span>
                            <?php else: ?>
                                <?php 
                                    $stmt = $pdo->prepare("SELECT * FROM asientos WHERE id_asiento = ?");
                                    $stmt->execute([$asiento]);
                                    $asiento_detalle = $stmt->fetch(PDO::FETCH_ASSOC);
                                ?>
                                <?php if ($asiento_detalle): ?>
                                    <span class="asiento-seleccionado"><?= htmlspecialchars($asiento_detalle['fila']) ?><?= htmlspecialchars($asiento_detalle['numero']) ?></span>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="combos-section">
        <h2 class="section-title">Selecciona tus combos</h2>
        <p style="color: var(--gray); margin-bottom: 1rem;">Añade deliciosos combos para disfrutar durante la película</p>
        
        <div class="combos-grid" id="combos-grid">
            <?php foreach ($combos as $combo): ?>
                <div class="combo-card" data-combo-id="<?= $combo['id_combo'] ?>">
                    <img src="/cineapp/assets/img/combos/<?= htmlspecialchars($combo['imagen_nombre']) ?>" 
                         alt="<?= htmlspecialchars($combo['nombre']) ?>" 
                         class="combo-imagen">
                    <div class="combo-info">
                        <h3 class="combo-nombre"><?= htmlspecialchars($combo['nombre']) ?></h3>
                        <p class="combo-descripcion"><?= htmlspecialchars($combo['descripcion']) ?></p>
                        <span class="combo-precio">$<?= number_format($combo['precio'], 0, '', '.') ?> COP</span>
                        <div class="combo-controls">
                            <div class="cantidad-control">
                                <button class="btn-cantidad btn-restar">-</button>
                                <span class="cantidad-value">0</span>
                                <button class="btn-cantidad btn-sumar">+</button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <div class="resumen-compra">
        <h2>Resumen de tu compra</h2>
        
        <div class="resumen-item">
            <span>Asientos (<?= count($asientos_seleccionados) ?>):</span>
            <span>$<?= number_format($subtotal_asientos, 0, '', '.') ?> COP</span>
        </div>
        
        <div id="combos-resumen">
            <!-- Aquí se agregarán los combos seleccionados dinámicamente -->
        </div>
        
        <div class="resumen-item resumen-total">
            <span>Total:</span>
            <span id="total-pagar">$<?= number_format($total, 0, '', '.') ?> COP</span>
        </div>
        
        <form action="../pagos/pago.php" method="post" id="form-confirmar">
            <input type="hidden" name="id_funcion" value="<?= $id_funcion ?>">
            
            <!-- Asientos seleccionados -->
            <?php foreach ($asientos_seleccionados as $asiento): ?>
                <input type="hidden" name="asientos[]" value="<?= is_array($asiento) ? $asiento['id'] : $asiento ?>">
            <?php endforeach; ?>

            <!-- Contenedor para los combos seleccionados dinámicamente -->
            <div id="combos-hidden-inputs"></div>
            
            <button type="submit" class="btn-confirmar">
                <i class="fas fa-ticket-alt" style="margin-right: 8px;"></i> Continuar al pago
            </button>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const combos = <?= json_encode($combos) ?>;
    let combosSeleccionados = {};
    let subtotalCombos = 0;
    let total = <?= $total ?>;
    
    function formatCOP(amount) {
        return '$' + amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }
    
    // Actualizar el resumen
    function actualizarResumen() {
        const combosResumen = document.getElementById('combos-resumen');
        combosResumen.innerHTML = '';
        
        subtotalCombos = 0;
        
        // Agregar combos seleccionados al resumen
        for (const comboId in combosSeleccionados) {
            if (combosSeleccionados[comboId] > 0) {
                const combo = combos.find(c => c.id_combo == comboId);
                const subtotalCombo = combosSeleccionados[comboId] * combo.precio;
                subtotalCombos += subtotalCombo;
                
                const div = document.createElement('div');
                div.className = 'resumen-item';
                div.innerHTML = `
                    <span>${combo.nombre} (${combosSeleccionados[comboId]}):</span>
                    <span>${formatCOP(subtotalCombo)} COP</span>
                `;
                combosResumen.appendChild(div);
            }
        }
        
        // Calcular total
        total = <?= $subtotal_asientos ?> + subtotalCombos;
        document.getElementById('total-pagar').textContent = formatCOP(total);
        
        // Actualizar campos ocultos del formulario
        const combosContainer = document.getElementById('combos-hidden-inputs');
        combosContainer.innerHTML = '';
        
        for (const comboId in combosSeleccionados) {
            if (combosSeleccionados[comboId] > 0) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = `combos[${comboId}]`;
                input.value = combosSeleccionados[comboId];
                combosContainer.appendChild(input);
            }
        }
    }
    
    // Manejar clic en botones de cantidad
    document.querySelectorAll('.combo-card').forEach(card => {
        const comboId = card.getAttribute('data-combo-id');
        const btnRestar = card.querySelector('.btn-restar');
        const btnSumar = card.querySelector('.btn-sumar');
        const cantidadValue = card.querySelector('.cantidad-value');
        
        // Inicializar en 0
        combosSeleccionados[comboId] = 0;
        cantidadValue.textContent = '0';
        
        btnRestar.addEventListener('click', function() {
            if (combosSeleccionados[comboId] > 0) {
                combosSeleccionados[comboId]--;
                cantidadValue.textContent = combosSeleccionados[comboId];
                actualizarResumen();
                
                // Efecto visual
                card.style.transform = 'translateY(-2px)';
                setTimeout(() => {
                    card.style.transform = 'translateY(0)';
                }, 100);
            }
        });
        
        btnSumar.addEventListener('click', function() {
            combosSeleccionados[comboId]++;
            cantidadValue.textContent = combosSeleccionados[comboId];
            actualizarResumen();
            
            // Efecto visual
            card.style.transform = 'scale(1.03)';
            setTimeout(() => {
                card.style.transform = 'scale(1)';
            }, 100);
        });
    });
    
    // Inicializar resumen
    actualizarResumen();
});
</script>

</body>
</html>