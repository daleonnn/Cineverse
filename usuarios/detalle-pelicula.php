<?php
session_start();
require './header-iniciado.php';

// Obtener ID de la película
$id_pelicula = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id_pelicula <= 0) {
    header("Location: index.php");
    exit;
}


$meses = [
    1 => 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
    'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
];


// Obtener datos de la película
$stmt = $pdo->prepare("SELECT * FROM peliculas WHERE id_pelicula = ?");
$stmt->execute([$id_pelicula]);
$pelicula = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$pelicula) {
    header("Location: index.php");
    exit;
}

// Obtener salas con sus funciones para esta película
$salas = $pdo->query("
    SELECT s.id_sala, s.nombre, s.capacidad, 
           GROUP_CONCAT(f.fecha_hora ORDER BY f.fecha_hora SEPARATOR '|') as horarios
    FROM salas s
    LEFT JOIN funciones f ON s.id_sala = f.id_sala AND f.id_pelicula = $id_pelicula
    GROUP BY s.id_sala
")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pelicula['titulo']) ?> - Detalles</title>
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
            padding-top: 80px;
        }
        
        .movie-details-container {
            display: flex;
            justify-content: space-between;
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 20px;
            gap: 40px;
        }
        
        .movie-poster {
            width: 30%;
            position: relative;
        }
        
        .movie-poster img {
            width: 100%;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            transition: transform 0.3s ease;
        }
        
        .movie-poster:hover img {
            transform: scale(1.02);
        }
        
        .movie-info {
            width: 70%;
        }
        
        .movie-info h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
            color: white;
            background: linear-gradient(90deg, #4a8fe7, #6A5ACD);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            display: inline-block;
        }
        
        .movie-info p {
            margin: 8px 0;
            font-size: 1rem;
            color: rgba(255, 255, 255, 0.8);
        }
        
        .movie-info strong {
            color: #4a8fe7;
            font-weight: 600;
        }
        
        .btn-play {
            display: inline-flex;
            align-items: center;
            background: linear-gradient(90deg, #4a8fe7, #6A5ACD);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 30px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s ease;
            text-decoration: none;
            margin-top: 15px;
            box-shadow: 0 4px 10px rgba(74, 143, 231, 0.3);
        }
        
        .btn-play:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(74, 143, 231, 0.4);
        }
        
        .btn-play i {
            margin-right: 8px;
        }
        
        .additional-info {
            margin-top: 30px;
        }
        
        .additional-info h2 {
            font-size: 1.5rem;
            margin-bottom: 15px;
            color: white;
            position: relative;
            padding-bottom: 5px;
        }
        
        .additional-info h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 3px;
            background: linear-gradient(90deg, #4a8fe7, #6A5ACD);
            border-radius: 3px;
        }
        
        .additional-info p {
            line-height: 1.6;
            font-size: 1rem;
            color: rgba(255, 255, 255, 0.8);
        }
        
        /* Sección de funciones mejorada */
        .cine-funciones {
            max-width: 1200px;
            margin: 3rem auto;
            padding: 0 20px;
        }
        
        .section-title {
            font-size: 1.8rem;
            color: white;
            margin-bottom: 1.5rem;
            position: relative;
            padding-bottom: 10px;
        }
        
        .salas-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
        }
        
        .sala-card {
            background: rgba(44, 35, 89, 0.8);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            animation: fadeIn 0.4s ease forwards;
            opacity: 0;
        }
        
        .sala-card:nth-child(1) { animation-delay: 0.1s; }
        .sala-card:nth-child(2) { animation-delay: 0.2s; }
        .sala-card:nth-child(3) { animation-delay: 0.3s; }
        .sala-card:nth-child(4) { animation-delay: 0.4s; }
        .sala-card:nth-child(5) { animation-delay: 0.5s; }
        .sala-card:nth-child(6) { animation-delay: 0.6s; }
        
        .sala-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
        }
        
        .sala-header {
            display: flex;
            align-items: center;
            padding: 15px;
            cursor: pointer;
            transition: background 0.2s ease;
        }
        
        .sala-header:hover {
            background: rgba(74, 143, 231, 0.1);
        }
        
        .sala-icon {
            width: 40px;
            height: 40px;
            background: rgba(74, 143, 231, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            color: #4a8fe7;
            font-size: 16px;
        }
        
        .sala-info {
            flex: 1;
        }
        
        .sala-info h3 {
            color: white;
            font-size: 1.1rem;
            margin-bottom: 5px;
        }
        
        .sala-capacity {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.85rem;
        }
        
        .toggle-btn {
            background: transparent;
            border: none;
            color: rgba(255, 255, 255, 0.7);
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .toggle-btn:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }
        
        .funciones-dropdown {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
        }
        
        .sala-card.active .funciones-dropdown {
            max-height: 500px;
        }
        
        .sala-card.active .toggle-btn i {
            transform: rotate(180deg);
        }
        
        .horarios-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            gap: 10px;
            padding: 0 15px 15px;
        }
        
        .horario-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: rgba(74, 143, 231, 0.2);
            border-radius: 8px;
            padding: 10px;
            text-decoration: none;
            transition: all 0.2s ease;
        }
        
        .horario-btn:hover {
            background: #4a8fe7;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .horario-fecha {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.75rem;
            margin-bottom: 3px;
        }
        
        .horario-hora {
            color: white;
            font-size: 1rem;
            font-weight: 500;
        }
        
        .no-funciones {
            text-align: center;
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.9rem;
            padding: 15px;
            font-style: italic;
        }
        
        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
            text-align: center;
            color: rgba(255, 255, 255, 0.5);
            background: rgba(44, 35, 89, 0.3);
            border-radius: 10px;
        }
        
        .empty-state i {
            font-size: 2.5rem;
            margin-bottom: 15px;
            color: rgba(74, 143, 231, 0.5);
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Responsive */
        @media (max-width: 900px) {
            .movie-details-container {
                flex-direction: column;
            }
            
            .movie-poster, .movie-info {
                width: 100%;
            }
            
            .movie-info {
                padding-left: 0;
                margin-top: 20px;
            }
            
            .salas-grid {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 480px) {
            .movie-info h1 {
                font-size: 2rem;
            }
            
            .horarios-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</head>
<body>

<div class="movie-details-container">
    <div class="movie-poster">
        <img src="/cineapp/assets/img/peliculas/<?= htmlspecialchars($pelicula['imagen_nombre']) ?>" 
             alt="<?= htmlspecialchars($pelicula['titulo']) ?>">
    </div>
    
    <div class="movie-info">
        <h1><?= htmlspecialchars($pelicula['titulo']) ?></h1>
        
        <?php if (!empty($pelicula['titulo_original'])): ?>
            <p><strong>Título original:</strong> <?= htmlspecialchars($pelicula['titulo_original']) ?></p>
        <?php endif; ?>
        
        <p><strong>Género:</strong> <?= htmlspecialchars($pelicula['genero']) ?></p>
        <p><strong>Duración:</strong> <?= htmlspecialchars($pelicula['duracion']) ?> min</p>
        <p><strong>Estreno:</strong> <?= date('d/m/Y', strtotime($pelicula['fecha_estreno'])) ?></p>
        
        <?php if (!empty($pelicula['edad_recomendada'])): ?>
            <p><strong>Recomendado para:</strong> <?= htmlspecialchars($pelicula['edad_recomendada']) ?></p>
        <?php endif; ?>
        
        <?php if (!empty($pelicula['trailer_url'])): ?>
            <?php 
            $video_id = '';
            $url = $pelicula['trailer_url'];
            
            if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $url, $matches)) {
                $video_id = $matches[1];
            }
            
            if ($video_id): ?>
                <a href="https://www.youtube.com/watch?v=<?= $video_id ?>" target="_blank" class="btn-play">
                    <i class="fas fa-play"></i> Ver Tráiler
                </a>
            <?php else: ?>
                <a href="<?= htmlspecialchars($url) ?>" target="_blank" class="btn-play">
                    <i class="fas fa-play"></i> Ver Tráiler
                </a>
            <?php endif; ?>
        <?php endif; ?>
        
        <div class="additional-info">
            <h2>Sinopsis</h2>
            <p><?= nl2br(htmlspecialchars($pelicula['sinopsis'])) ?></p>
        </div>
    </div>
</div>

<div class="cine-funciones">
    <h2 class="section-title">
        <i class="fas fa-calendar-alt"></i> Horarios Disponibles
    </h2>
    
    <?php if (count($salas) > 0): ?>
        <div class="timeline-container">
            <?php 
            $current_date = '';
            foreach ($salas as $sala): 
                if (!empty($sala['horarios'])):
                    $horarios = explode('|', $sala['horarios']);
                    foreach ($horarios as $horario): 
                        $fecha_completa = date('Y-m-d', strtotime($horario));
                        $fecha_mostrar = date('d F Y', strtotime($horario));
                        $hora = date('H:i', strtotime($horario));
                        
                        if ($current_date != $fecha_completa): 
                            $current_date = $fecha_completa;
            ?>
<div class="timeline-date" data-date="<?= $fecha_completa ?>">
    <div class="date-badge">
        <span class="day"><?= date('d', strtotime($horario)) ?></span>
        <span class="month"><?= $meses[date('n', strtotime($horario))] ?></span>
    </div>
    <h3><?= date('d', strtotime($horario)) ?> <?= $meses[date('n', strtotime($horario))] ?> <?= date('Y', strtotime($horario)) ?></h3>
</div>
            <?php endif; ?>
            
            <div class="timeline-item" data-date="<?= $fecha_completa ?>" data-time="<?= $hora ?>">
                <div class="timeline-content">
                    <div class="sala-info">
                        <h4>
                            <i class="fas fa-map-marker-alt"></i> 
                            <?= htmlspecialchars($sala['nombre']) ?>
                            <span class="badge"><?= htmlspecialchars($sala['capacidad']) ?> asientos</span>
                        </h4>
                        <div class="sala-features">
                            <span class="feature"><i class="fas fa-chair"></i> Asientos</span>
                            <span class="feature"><i class="fas fa-digital-tachograph"></i>Tiquetes</span>
                        </div>
                    </div>
                    <div class="time-slots">
                        <a href="reservar.php?pelicula=<?= $id_pelicula ?>&sala=<?= $sala['id_sala'] ?>&horario=<?= urlencode($horario) ?>" 
                           class="time-slot">
                            <span class="time"><?= $hora ?></span>
                        </a>
                    </div>
                </div>
            </div>
            <?php 
                    endforeach;
                endif;
            endforeach; 
            ?>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-calendar-times"></i>
            <h3>No hay funciones disponibles</h3>
            <p>Pronto tendremos nuevas funciones para esta película</p>
            <button class="btn-notify">Notificarme cuando estén disponibles</button>
        </div>
    <?php endif; ?>
</div>






























<style>
    /* Estilos mejorados para la sección de funciones */
    .cine-funciones {
        max-width: 1200px;
        margin: 3rem auto;
        padding: 0 20px;
    }
    
    .section-title {
        font-size: 1.8rem;
        color: white;
        margin-bottom: 1.5rem;
        position: relative;
        display: flex;
        align-items: center;
        gap: 15px;
    }
    
    .section-title i {
        color: #4a8fe7;
        font-size: 1.5rem;
    }
    
    .section-title .subtitle {
        font-size: 1rem;
        color: rgba(255, 255, 255, 0.7);
        margin-left: auto;
        font-weight: normal;
    }
    
    .filters {
        display: flex;
        gap: 15px;
        margin-bottom: 2rem;
        flex-wrap: wrap;
    }
    
    .filter-group {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .filter-group label {
        color: rgba(255, 255, 255, 0.8);
        font-size: 0.9rem;
    }
    
    .filter-group label i {
        color: #4a8fe7;
        margin-right: 5px;
    }
    
    .filter-select {
        background: rgba(44, 35, 89, 0.8);
        border: 1px solid rgba(74, 143, 231, 0.3);
        border-radius: 6px;
        padding: 8px 15px;
        color: white;
        font-family: 'Poppins', sans-serif;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .filter-select:hover {
        border-color: rgba(74, 143, 231, 0.6);
    }
    
    .timeline-container {
        position: relative;
        padding-left: 60px;
    }
    
    .timeline-date {
        display: flex;
        align-items: center;
        margin: 30px 0 15px;
        position: relative;
    }
    
    .timeline-date::before {
        content: '';
        position: absolute;
        left: -60px;
        top: 50%;
        width: 30px;
        height: 3px;
        background: linear-gradient(90deg, #4a8fe7, #6A5ACD);
        transform: translateY(-50%);
    }
    
    .date-badge {
        width: 50px;
        height: 50px;
        background: rgba(44, 35, 89, 0.8);
        border-radius: 8px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        border: 1px solid rgba(74, 143, 231, 0.3);
    }
    
    .date-badge .day {
        font-size: 1.2rem;
        font-weight: bold;
        color: white;
        line-height: 1;
    }
    
    .date-badge .month {
        font-size: 0.7rem;
        color: rgba(255, 255, 255, 0.7);
        text-transform: uppercase;
        margin-top: 3px;
    }
    
    .timeline-date h3 {
        color: white;
        font-size: 1.2rem;
        font-weight: 500;
    }
    
    .timeline-item {
        background: rgba(44, 35, 89, 0.5);
        border-radius: 10px;
        margin-bottom: 15px;
        overflow: hidden;
        transition: all 0.3s ease;
        border-left: 3px solid transparent;
    }
    
    .timeline-item:hover {
        transform: translateX(5px);
        border-left-color: #4a8fe7;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }
    
    .timeline-content {
        display: flex;
        padding: 15px;
        align-items: center;
    }
    
    .sala-info {
        flex: 1;
        padding-right: 15px;
    }
    
    .sala-info h4 {
        color: white;
        font-size: 1.1rem;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
    }
    
    .sala-info h4 i {
        color: #4a8fe7;
        margin-right: 10px;
        font-size: 0.9rem;
    }
    
    .sala-info .badge {
        background: rgba(74, 143, 231, 0.2);
        color: #4a8fe7;
        font-size: 0.7rem;
        padding: 3px 8px;
        border-radius: 10px;
        margin-left: 10px;
    }
    
    .sala-features {
        display: flex;
        gap: 10px;
    }
    
    .sala-features .feature {
        font-size: 0.8rem;
        color: rgba(255, 255, 255, 0.7);
        display: flex;
        align-items: center;
        gap: 5px;
    }
    
    .sala-features .feature i {
        color: #4a8fe7;
        font-size: 0.7rem;
    }
    
    .time-slots {
        display: flex;
        gap: 10px;
    }
    
    .time-slot {
        background: rgba(74, 143, 231, 0.2);
        border-radius: 6px;
        padding: 8px 15px;
        text-decoration: none;
        display: flex;
        flex-direction: column;
        align-items: center;
        min-width: 70px;
        transition: all 0.2s ease;
    }
    
    .time-slot:hover {
        background: #4a8fe7;
        transform: translateY(-2px);
    }
    
    .time-slot .time {
        color: white;
        font-size: 1rem;
        font-weight: 500;
    }
    
    .time-slot .price {
        color: rgba(255, 255, 255, 0.8);
        font-size: 0.7rem;
        margin-top: 3px;
    }
    
    .empty-state {
        text-align: center;
        padding: 40px 20px;
        background: rgba(44, 35, 89, 0.3);
        border-radius: 10px;
        margin-top: 20px;
    }
    
    .empty-state i {
        font-size: 3rem;
        color: rgba(74, 143, 231, 0.5);
        margin-bottom: 15px;
    }
    
    .empty-state h3 {
        color: white;
        margin-bottom: 10px;
    }
    
    .empty-state p {
        color: rgba(255, 255, 255, 0.6);
        margin-bottom: 20px;
    }
    
    .btn-notify {
        background: linear-gradient(90deg, #4a8fe7, #6A5ACD);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 30px;
        cursor: pointer;
        font-family: 'Poppins', sans-serif;
        transition: all 0.3s ease;
    }
    
    .btn-notify:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(74, 143, 231, 0.3);
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .timeline-container {
            padding-left: 30px;
        }
        
        .timeline-date::before {
            left: -30px;
            width: 15px;
        }
        
        .timeline-content {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .sala-info {
            padding-right: 0;
            margin-bottom: 15px;
            width: 100%;
        }
        
        .time-slots {
            width: 100%;
            flex-wrap: wrap;
        }
        
        .time-slot {
            flex: 1;
            min-width: 60px;
        }
    }
    
    @media (max-width: 480px) {
        .filters {
            flex-direction: column;
            gap: 10px;
        }
        
        .filter-group {
            width: 100%;
        }
        
        .filter-select {
            width: 100%;
        }
    }
</style>
























<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle de las funciones
        const salaCards = document.querySelectorAll('.sala-card');
        
        salaCards.forEach(card => {
            const toggleBtn = card.querySelector('.toggle-btn');
            
            toggleBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                card.classList.toggle('active');
                
                // Cerrar las demás tarjetas
                salaCards.forEach(otherCard => {
                    if (otherCard !== card && otherCard.classList.contains('active')) {
                        otherCard.classList.remove('active');
                    }
                });
            });
            
            // También se puede hacer clic en toda la tarjeta
            card.addEventListener('click', function() {
                this.classList.toggle('active');
            });
        });
        
        // Efecto hover mejorado para los botones de horario
        const horarioBtns = document.querySelectorAll('.horario-btn');
        
        horarioBtns.forEach(btn => {
            btn.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-3px)';
                this.style.boxShadow = '0 4px 8px rgba(0,0,0,0.2)';
            });
            
            btn.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = 'none';
            });
        });
    });
</script>

</body>
</html>