<?php
session_start();
require './header-iniciado.php';
// Obtener parámetros de la URL
$id_pelicula = isset($_GET['pelicula']) ? intval($_GET['pelicula']) : 0;
$id_sala = isset($_GET['sala']) ? intval($_GET['sala']) : 0;
$horario = isset($_GET['horario']) ? $_GET['horario'] : '';
// Validar parámetros
if ($id_pelicula <= 0 || $id_sala <= 0 || empty($horario)) {
    header("Location: index.php");
    exit;
}
// Obtener datos de la película
$stmt = $pdo->prepare("SELECT * FROM peliculas WHERE id_pelicula = ?");
$stmt->execute([$id_pelicula]);
$pelicula = $stmt->fetch(PDO::FETCH_ASSOC);
// Obtener datos de la sala
$stmt = $pdo->prepare("SELECT * FROM salas WHERE id_sala = ?");
$stmt->execute([$id_sala]);
$sala = $stmt->fetch(PDO::FETCH_ASSOC);
// Obtener datos de la función (si existe)
$stmt = $pdo->prepare("SELECT * FROM funciones WHERE id_pelicula = ? AND id_sala = ? AND fecha_hora = ?");
$stmt->execute([$id_pelicula, $id_sala, $horario]);
$funcion = $stmt->fetch(PDO::FETCH_ASSOC);
// Si no existe la función, redirigir
if (!$pelicula || !$sala || !$funcion) {
    header("Location: index.php");
    exit;
}
// Consulta MEJORADA para obtener asientos con su estado REAL
$stmt = $pdo->prepare("
    SELECT 
        a.id_asiento,
        a.id_sala,
        a.fila,
        a.numero,
        a.tipo_asiento,
        CASE 
            WHEN r.id_reservacion IS NOT NULL THEN 'ocupado'
            WHEN a.estado = 'ocupado' THEN 'ocupado'
            ELSE 'disponible'
        END as estado_final
    FROM asientos a
    LEFT JOIN reservaciones r ON a.id_asiento = r.id_asiento AND r.id_funcion = ?
    WHERE a.id_sala = ?
    ORDER BY a.fila, a.numero
");
$stmt->execute([$funcion['id_funcion'], $id_sala]);
$asientos = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Agrupar por filas
$asientos_por_fila = [];
foreach ($asientos as $asiento) {
    $asientos_por_fila[$asiento['fila']][] = $asiento;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservar Asientos - <?= htmlspecialchars($pelicula['titulo']) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --primary: #6C5CE7;
            --primary-dark: #5649C0;
            --secondary: #00CEFF;
            --danger: #FF5252;
            --success: #4CAF50;
            --success-dark: #3e8e41;
            --dark: #100919;
            --darker: #0A0612;
            --light: #D6D0E0;
            --lighter: #F5F3FA;
            --gray: #8E8B98;
            --border-radius: 12px;
            --box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
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
            padding: 0;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 30px 20px;
        }
        
        /* Header de la película */
        .movie-header {
            display: flex;
            gap: 30px;
            margin-bottom: 40px;
            background: rgba(44, 35, 89, 0.6);
            border-radius: var(--border-radius);
            padding: 25px;
            box-shadow: var(--box-shadow);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        
        .movie-poster {
            width: 220px;
            flex-shrink: 0;
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
            position: relative;
        }
        
        .movie-poster img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: var(--transition);
        }
        
        .movie-poster:hover img {
            transform: scale(1.03);
        }
        
        .movie-info {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .movie-title {
            color: white;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 15px;
            line-height: 1.2;
        }
        
        .movie-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .meta-item {
            background: rgba(108, 92, 231, 0.2);
            padding: 8px 15px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            border: 1px solid rgba(108, 92, 231, 0.3);
        }
        
        .meta-item i {
            font-size: 16px;
            color: var(--secondary);
        }
        
        /* Pantalla de cine */
        .screen-container {
            margin: 40px 0;
            text-align: center;
        }
        
        .screen {
            display: inline-block;
            width: 70%;
            height: 30px;
            background: linear-gradient(to bottom, rgba(255, 255, 255, 0.8), rgba(255, 255, 255, 0.1));
            border-radius: 50% 50% 0 0;
            margin-bottom: 40px;
            position: relative;
            box-shadow: 0 15px 25px rgba(0, 0, 0, 0.3);
        }
        
        .screen::after {
            content: 'PANTALLA';
            position: absolute;
            bottom: -30px;
            left: 50%;
            transform: translateX(-50%);
            color: white;
            font-weight: 600;
            font-size: 14px;
            letter-spacing: 2px;
        }
        
        /* Asientos */
        .seats-container {
            max-width: 800px;
            margin: 0 auto;
            background: rgba(44, 35, 89, 0.4);
            border-radius: var(--border-radius);
            padding: 30px;
            box-shadow: var(--box-shadow);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        
        .row {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-bottom: 15px;
        }
        
        .row-label {
            width: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: var(--secondary);
            margin-right: 10px;
        }
        
        .seat {
            width: 32px;
            height: 32px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition);
            position: relative;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            font-size: 11px;
            font-weight: 600;
        }
        
        .seat.disponible {
            background-color: var(--primary);
            color: white;
        }
        
        .seat.ocupado {
            background-color: var(--danger);
            cursor: not-allowed;
            opacity: 0.7;
        }
        
        .seat.seleccionado {
            background-color: var(--success);
            transform: scale(1.1);
            box-shadow: 0 0 15px rgba(76, 175, 80, 0.5);
        }
        
        .seat.disponible:hover {
            transform: scale(1.1);
            background-color: var(--primary-dark);
            box-shadow: 0 0 15px rgba(108, 92, 231, 0.6);
        }
        
        /* Panel de selección */
        .selection-panel {
            background: rgba(44, 35, 89, 0.8);
            border-radius: var(--border-radius);
            padding: 30px;
            margin-top: 40px;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
            box-shadow: var(--box-shadow);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        
        .panel-title {
            color: white;
            font-size: 22px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .panel-title i {
            color: var(--secondary);
        }
        
        .price-info {
            background: rgba(0, 206, 255, 0.1);
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            border: 1px solid rgba(0, 206, 255, 0.2);
        }
        
        .price-info i {
            color: var(--secondary);
            font-size: 18px;
        }
        
        .selected-seats {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 25px;
            min-height: 50px;
        }
        
        .selected-seat {
            background: var(--primary);
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        .remove-seat {
            background: none;
            border: none;
            color: white;
            cursor: pointer;
            font-size: 16px;
            display: flex;
            align-items: center;
            transition: var(--transition);
        }
        
        .remove-seat:hover {
            color: var(--secondary);
            transform: scale(1.2);
        }
        
        .summary {
            background: rgba(108, 92, 231, 0.1);
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 25px;
            border: 1px solid rgba(108, 92, 231, 0.2);
        }
        
        .summary-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 15px;
        }
        
        .summary-item:last-child {
            margin-bottom: 0;
        }
        
        .total {
            font-weight: 600;
            font-size: 18px;
            color: white;
            padding-top: 10px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .btn-reserve {
            background: var(--success);
            color: white;
            border: none;
            padding: 14px 30px;
            border-radius: 30px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            width: 100%;
            box-shadow: 0 5px 15px rgba(76, 175, 80, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        .btn-reserve:hover {
            background: var(--success-dark);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(76, 175, 80, 0.4);
        }
        
        .btn-reserve:disabled {
            background: var(--gray);
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }
        
        .legend {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 25px;
            flex-wrap: wrap;
        }
        
        .legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
        }
        
        .legend-color {
            width: 18px;
            height: 18px;
            border-radius: 4px;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .movie-header {
                flex-direction: column;
                gap: 20px;
            }
            
            .movie-poster {
                width: 180px;
                margin: 0 auto;
            }
            
            .screen {
                width: 90%;
            }
            
            .row {
                gap: 5px;
            }
            
            .seat {
                width: 26px;
                height: 26px;
                font-size: 10px;
            }
            
            .selection-panel {
                padding: 20px;
            }
        }
        
        @media (max-width: 480px) {
            .container {
                padding: 20px 15px;
            }
            
            .movie-title {
                font-size: 24px;
            }
            
            .meta-item {
                padding: 6px 12px;
                font-size: 12px;
            }
            
            .seats-container {
                padding: 20px 15px;
            }
            
            .selection-panel {
                padding: 20px 15px;
            }
            
            .panel-title {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Encabezado de la película -->
        <div class="movie-header">
            <div class="movie-poster">
                <img src="/cineapp/assets/img/peliculas/<?= htmlspecialchars($pelicula['imagen_nombre']) ?>" 
                     alt="<?= htmlspecialchars($pelicula['titulo']) ?>">
            </div>
            <div class="movie-info">
                <h1 class="movie-title"><?= htmlspecialchars($pelicula['titulo']) ?></h1>
                <div class="movie-meta">
                    <div class="meta-item">
                        <i class="fas fa-theater-masks"></i>
                        <span><?= htmlspecialchars($sala['nombre']) ?></span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-ticket-alt"></i>
                        <span>$<?= number_format($funcion['precio'], 0, '', '.') ?> COP</span>
                    </div>
                    <div class="meta-item">
                        <i class="far fa-clock"></i>
                        <span><?= date('d/m/Y H:i', strtotime($funcion['fecha_hora'])) ?></span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-hourglass-half"></i>
                        <span><?= htmlspecialchars($pelicula['duracion']) ?> min</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Pantalla de cine -->
        <div class="screen-container">
            <div class="screen"></div>
        </div>
        
        <!-- Contenedor de asientos -->
        <div class="seats-container">
            <?php foreach ($asientos_por_fila as $fila => $asientos_fila): ?>
                <div class="row">
                    <div class="row-label"><?= $fila ?></div>
                    <?php 
                    $contador = 0;
                    foreach ($asientos_fila as $asiento): 
                        if ($contador % 10 === 0 && $contador !== 0): 
                    ?>
                        </div><div class="row"><div class="row-label"><?= $fila ?></div>
                    <?php endif; ?>
                        <div class="seat <?= $asiento['estado_final'] ?>" 
                             data-asiento-id="<?= $asiento['id_asiento'] ?>"
                             data-fila="<?= $asiento['fila'] ?>"
                             data-numero="<?= $asiento['numero'] ?>"
                             <?= $asiento['estado_final'] == 'ocupado' ? 'title="Asiento ocupado"' : '' ?>>
                            <?= $asiento['numero'] ?>
                        </div>
                    <?php 
                        $contador++;
                    endforeach; 
                    ?>
                </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Panel de selección -->
        <div class="selection-panel">
            <h2 class="panel-title"><i class="fas fa-ticket-alt"></i> Tu selección</h2>
            
            <div class="price-info">
                <i class="fas fa-info-circle"></i>
                <span>Precio por asiento: <strong>$<?= number_format($funcion['precio'], 0, '', '.') ?> COP</strong></span>
            </div>
            
            <div class="selected-seats" id="asientos-seleccionados">
                <!-- Aquí aparecerán los asientos seleccionados -->
            </div>
            
            <div class="summary">
                <div class="summary-item">
                    <span>Asientos seleccionados:</span>
                    <span id="cantidad-asientos">0</span>
                </div>
                <div class="summary-item">
                    <span>Subtotal:</span>
                    <span id="subtotal">$0</span>
                </div>
                <div class="summary-item total">
                    <span>Total a pagar:</span>
                    <span id="total">$0</span>
                </div>
            </div>
            
            <button class="btn-reserve" id="btn-reservar" disabled>
                <i class="fas fa-check-circle"></i> Confirmar Reserva
            </button>
            
            <div class="legend">
                <div class="legend-item">
                    <div class="legend-color" style="background-color: var(--primary);"></div>
                    <span>Disponible</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color" style="background-color: var(--danger);"></div>
                    <span>Ocupado</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color" style="background-color: var(--success);"></div>
                    <span>Seleccionado</span>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const precioPorAsiento = <?= $funcion['precio'] ?>;
            const asientos = document.querySelectorAll('.seat.disponible');
            const asientosSeleccionados = document.getElementById('asientos-seleccionados');
            const cantidadAsientos = document.getElementById('cantidad-asientos');
            const subtotalElement = document.getElementById('subtotal');
            const totalElement = document.getElementById('total');
            const btnReservar = document.getElementById('btn-reservar');
            let seleccionados = [];
            
            function formatCOP(amount) {
                return '$' + amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            }
            
            // Manejar clic en asientos
            asientos.forEach(asiento => {
                asiento.addEventListener('click', function() {
                    const idAsiento = this.getAttribute('data-asiento-id');
                    const fila = this.getAttribute('data-fila');
                    const numero = this.getAttribute('data-numero');
                    const index = seleccionados.findIndex(a => a.id === idAsiento);
                    
                    if (index === -1) {
                        seleccionados.push({
                            id: idAsiento,
                            fila: fila,
                            numero: numero
                        });
                        this.classList.add('seleccionado');
                    } else {
                        seleccionados.splice(index, 1);
                        this.classList.remove('seleccionado');
                    }
                    
                    actualizarSeleccion();
                });
            });
            
            function actualizarSeleccion() {
                asientosSeleccionados.innerHTML = '';
                
                seleccionados.forEach(asiento => {
                    const div = document.createElement('div');
                    div.className = 'selected-seat';
                    div.innerHTML = `
                        <span>${asiento.fila}${asiento.numero}</span>
                        <button class="remove-seat" data-id="${asiento.id}">
                            <i class="fas fa-times"></i>
                        </button>
                    `;
                    asientosSeleccionados.appendChild(div);
                });
                
                const subtotal = seleccionados.length * precioPorAsiento;
                cantidadAsientos.textContent = seleccionados.length;
                subtotalElement.textContent = formatCOP(subtotal);
                totalElement.textContent = formatCOP(subtotal);
                btnReservar.disabled = seleccionados.length === 0;
                
                document.querySelectorAll('.remove-seat').forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        e.stopPropagation();
                        const id = this.getAttribute('data-id');
                        seleccionados = seleccionados.filter(a => a.id !== id);
                        document.querySelector(`.seat[data-asiento-id="${id}"]`).classList.remove('seleccionado');
                        actualizarSeleccion();
                    });
                });
            }
            
            // En el evento click del botón de reservar:
            btnReservar.addEventListener('click', function() {
                if (seleccionados.length === 0) return;
                
                // Verificar asientos en consola
                console.log('Asientos a enviar:', seleccionados.map(a => `${a.fila}${a.numero}`).join(', '));
                
                // Crear formulario
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '/cineapp/usuarios/seleccionar_combos.php';
                
                // Agregar asientos con su información completa
                seleccionados.forEach((asiento, index) => {
                    ['id', 'fila', 'numero'].forEach(key => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = `asientos[${index}][${key}]`;
                        input.value = asiento[key];
                        form.appendChild(input);
                    });
                });
                
                // Agregar función
                const funcionInput = document.createElement('input');
                funcionInput.type = 'hidden';
                funcionInput.name = 'id_funcion';
                funcionInput.value = '<?= $funcion['id_funcion'] ?>';
                form.appendChild(funcionInput);
                
                document.body.appendChild(form);
                form.submit();
            });
        });
    </script>
</body>
</html>