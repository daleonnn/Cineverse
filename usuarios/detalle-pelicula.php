<?php
session_start();
require_once "../api/db.php";

// Obtener ID de la película
$id_pelicula = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id_pelicula <= 0) {
    header("Location: index.php");
    exit;
}

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
    <!-- Fuente personalizada -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <!-- Iconos de Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Estilos generales */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #100919;
            color: #D6D0E0;
        }
        
        /* Contenedor principal */
        .movie-details-container {
            display: flex;
            justify-content: space-between;
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 20px;
            gap: 40px;
        }
        
        /* Sección del póster */
        .movie-poster {
            width: 30%;
            position: relative;
        }
        
        .movie-poster img {
            width: 100%;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }
        
        /* Sección de información */
        .movie-info {
            width: 70%;
        }
        
        .movie-info h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
            color: white;
        }
        
        .movie-info p {
            margin: 8px 0;
            font-size: 1rem;
        }
        
        .movie-info strong {
            color: #4a8fe7;
        }
        
        /* Botón de tráiler */
        .btn-play {
            display: inline-flex;
            align-items: center;
            background-color: #4a8fe7;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s ease;
            text-decoration: none;
            margin-top: 15px;
        }
        
        .btn-play:hover {
            background-color: #6A5ACD;
        }
        
        .btn-play i {
            margin-right: 8px;
        }
        
        /* Sección de sinopsis */
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
        }
        
        .additional-info p {
            line-height: 1.6;
            font-size: 1rem;
        }
        
        /* Sección de funciones */
        .cine-funciones {
            background: rgba(44, 35, 89, 0.8);
            border-radius: 10px;
            padding: 20px;
            margin-top: 40px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        
        /* Selector de fechas */
        .fechas-container {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
            position: relative;
        }
        
        .mes-actual {
            font-weight: bold;
            font-size: 14px;
            color: #D6D0E0;
            min-width: 30px;
        }
        
        .dias-container {
            flex: 1;
            overflow: hidden;
        }
        
        .dias-scroll {
            display: flex;
            gap: 8px;
            transition: transform 0.3s ease;
        }
        
        .dia-btn {
            border: none;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 8px 12px;
            font-size: 14px;
            cursor: pointer;
            white-space: nowrap;
            transition: all 0.2s ease;
            color: #D6D0E0;
        }
        
        .dia-btn.active {
            background: #4a8fe7;
            color: white;
        }
        
        .dia-btn:hover:not(.active) {
            background: rgba(255, 255, 255, 0.2);
        }
        
        .calendario-btn {
            border: none;
            background: #4a8fe7;
            color: white;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s ease;
        }
        
        .calendario-btn:hover {
            background: #3a7bd5;
        }
        
        /* Calendario desplegable */
        .calendario-dropdown {
            position: absolute;
            top: 100%;
            left: 40px;
            background: #2C2359;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            padding: 10px;
            z-index: 100;
            display: none;
            width: 280px;
        }
        
        .calendario-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            padding: 0 5px;
            color: #D6D0E0;
        }
        
        .nav-mes {
            background: none;
            border: none;
            cursor: pointer;
            color: #4a8fe7;
            font-size: 16px;
        }
        
        .dias-semana {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            text-align: center;
            font-weight: bold;
            font-size: 12px;
            margin-bottom: 5px;
            color: #D6D0E0;
            gap: 2px;
        }
        
        .dias-mes {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 2px;
        }
        
        .dia-calendario {
            padding: 5px;
            text-align: center;
            border-radius: 50%;
            cursor: pointer;
            font-size: 12px;
            aspect-ratio: 1/1;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            color: #D6D0E0;
        }
        
        .dia-calendario:hover {
            background: rgba(255, 255, 255, 0.1);
        }
        
        .dia-calendario.selected {
            background: #4a8fe7;
            color: white;
            font-weight: bold;
        }
        
        .dia-calendario.otro-mes {
            color: rgba(255, 255, 255, 0.3);
        }
        
        /* Contenido de funciones */
        .funciones-content {
            background: rgba(58, 47, 122, 0.5);
            border-radius: 10px;
            padding: 15px;
        }
        
        .funciones-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .funciones-header h3 {
            font-size: 16px;
            color: white;
            margin: 0;
        }
        
        .filtro-btn {
            border: none;
            background: rgba(255, 255, 255, 0.1);
            width: 30px;
            height: 30px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #D6D0E0;
        }
        
        /* Lista de funciones */
        .funciones-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        
        .multiplex-item {
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding-bottom: 15px;
        }
        
        .multiplex-item:last-child {
            border-bottom: none;
        }
        
        .multiplex-nombre {
            font-size: 15px;
            font-weight: bold;
            margin: 0 0 5px 0;
            color: white;
        }
        
        .multiplex-tipo {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.7);
            margin: 0 0 10px 0;
        }
        
        .horas-container {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }
        
        .hora-btn {
            border: none;
            background: #4a8fe7;
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 13px;
            cursor: pointer;
            transition: background 0.2s ease;
        }
        
        .hora-btn:hover {
            background: #3a7bd5;
        }
        
        .no-funciones {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.7);
            text-align: center;
            padding: 20px 0;
        }
        
        /* Notificación */
        .notificacion-container {
            position: fixed;
            top: 20px;
            left: 0;
            right: 0;
            display: flex;
            justify-content: center;
            z-index: 1000;
            pointer-events: none;
        }
        
        .notificacion {
            background-color: #ff4444;
            color: white;
            padding: 12px 20px;
            border-radius: 5px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
            display: flex;
            align-items: center;
            gap: 10px;
            max-width: 90%;
            opacity: 0;
            transform: translateY(-20px);
            transition: all 0.3s ease;
            pointer-events: auto;
        }
        
        .notificacion.mostrar {
            opacity: 1;
            transform: translateY(0);
        }
        
        .notificacion-close {
            background: none;
            border: none;
            color: white;
            cursor: pointer;
            font-size: 16px;
            padding: 0 0 0 10px;
            display: flex;
            align-items: center;
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
            
            .cine-funciones {
                width: 100%;
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Encabezado -->
     <?php require './headeriniciado.php' ?>




     
    <!-- Contenido principal -->
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
                // Convertir URL de YouTube a embed
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
    <h2>Salas Disponibles</h2>
    
    <?php if (count($salas) > 0): ?>
        <div class="salas-container">
            <?php foreach ($salas as $sala): ?>
                <div class="sala-item" onclick="toggleFunciones(this)" 
                     data-sala-id="<?= $sala['id_sala'] ?>">
                    <div class="sala-header">
                        <h3><?= htmlspecialchars($sala['nombre']) ?></h3>
                        <p>Capacidad: <?= htmlspecialchars($sala['capacidad']) ?> personas</p>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    
                    <div class="funciones-container" style="display: none;">
                        <?php if (!empty($sala['horarios'])): ?>
                            <?php 
                            $horarios = explode('|', $sala['horarios']);
                            foreach ($horarios as $horario): 
                                $fecha = date('d/m/Y', strtotime($horario));
                                $hora = date('H:i', strtotime($horario));
                            ?>
                                <div class="funcion-item">
                                    <span><?= $fecha ?></span>
                                    <a href="reservar.php?pelicula=<?= $id_pelicula ?>&sala=<?= $sala['id_sala'] ?>&horario=<?= urlencode($horario) ?>" 
                                       class="hora-btn">
                                        <?= $hora ?>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="no-funciones">No hay funciones programadas</p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p class="no-salas">No hay salas disponibles en este momento.</p>
    <?php endif; ?>
</div>





<style>
    /* Estilos para la sección de salas */
    .salas-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }

    .sala-item {
        background: rgba(58, 47, 122, 0.5);
        border-radius: 8px;
        padding: 15px;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .sala-item:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }

    .sala-header {
        display: flex;
        flex-direction: column;
        position: relative;
    }

    .sala-header h3 {
        color: white;
        margin-bottom: 5px;
        font-size: 18px;
    }

    .sala-header p {
        color: rgba(255, 255, 255, 0.7);
        font-size: 14px;
        margin: 0;
    }

    .sala-header i {
        position: absolute;
        right: 0;
        top: 50%;
        transform: translateY(-50%);
        transition: transform 0.3s ease;
    }

    .sala-item.active .sala-header i {
        transform: translateY(-50%) rotate(180deg);
    }

    .funciones-container {
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
    }

    .funcion-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }

    .funcion-item span {
        color: rgba(255, 255, 255, 0.8);
        font-size: 14px;
    }

    .hora-btn {
        background: #4a8fe7;
        color: white;
        padding: 6px 12px;
        border-radius: 20px;
        text-decoration: none;
        font-size: 14px;
        transition: background 0.2s ease;
    }

    .hora-btn:hover {
        background: #3a7bd5;
    }

    .no-funciones {
        color: rgba(255, 255, 255, 0.5);
        font-style: italic;
        text-align: center;
        padding: 10px 0;
    }

    .no-salas {
        text-align: center;
        color: rgba(255, 255, 255, 0.7);
        padding: 20px 0;
    }
</style>











    <style>
        /* Estilos para la sección de salas */
.salas-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.sala-item {
    background: rgba(58, 47, 122, 0.5);
    border-radius: 8px;
    padding: 15px;
    transition: transform 0.3s ease;
}

.sala-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
}

.sala-item h3 {
    color: white;
    margin-bottom: 10px;
    font-size: 18px;
}

.sala-item p {
    color: rgba(255, 255, 255, 0.7);
    font-size: 14px;
    margin: 5px 0;
}

.no-salas {
    text-align: center;
    color: rgba(255, 255, 255, 0.7);
    padding: 20px 0;
}
    </style>





<script>
    function toggleFunciones(element) {
        // Toggle la clase active
        element.classList.toggle('active');
        
        // Mostrar/ocultar las funciones
        const funcionesContainer = element.querySelector('.funciones-container');
        if (element.classList.contains('active')) {
            funcionesContainer.style.display = 'block';
        } else {
            funcionesContainer.style.display = 'none';
        }
    }
</script>
</body>
</html>