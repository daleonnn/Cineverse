<?php
session_start();
require './header-iniciado.php';
require '../api/db.php';

$stmt = $pdo->query("SELECT * FROM combos ORDER BY nombre");
$combos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
</head>
<body>
    <!-- Sección de combos -->
    <section class="combos-section">
        <h2 class="section-title">Combos de Comida</h2>
        
        <div class="combos-grid">
            <?php foreach ($combos as $index => $combo): ?>
                <div class="combo-card" style="--order: <?= $index ?>">
                    <div class="combo-imagen-container">
                        <img src="/cineapp/assets/img/combos/<?= htmlspecialchars($combo['imagen_nombre']) ?>" 
                             alt="<?= htmlspecialchars($combo['nombre']) ?>" 
                             class="combo-imagen">
                    </div>
                    <div class="combo-info">
                        <h3 class="combo-nombre"><?= htmlspecialchars($combo['nombre']) ?></h3>
                        <p class="combo-descripcion"><?= htmlspecialchars($combo['descripcion']) ?></p>
                        <div class="combo-footer">
                            <span class="combo-precio">$<?= number_format($combo['precio'], 0, ',', '.') ?> COP</span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    <style>
        :root {
            --primary: #6a11cb;
            --secondary: #2575fc;
            --accent: #ff4d4d;
            --dark: #0f0523;
            --light: #f8f9fa;
            --text: #e0e0e0;
            --text-dark: #333;
            --card-bg: rgba(30, 15, 65, 0.7);
            --glow: 0 0 15px rgba(106, 17, 203, 0.5);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            color: var(--text);
            min-height: 100vh;
            padding: 2rem;
            overflow-x: hidden;
        }
        
        .hero-section {
            text-align: center;
            margin-bottom: 3rem;
            position: relative;
            overflow: hidden;
            padding: 2rem 0;
        }
        
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at center, rgba(106, 17, 203, 0.2) 0%, transparent 70%);
            z-index: -1;
        }
        
        .hero-title {
            font-size: 3.5rem;
            font-weight: 700;
            background: linear-gradient(to right, #6a11cb, #2575fc);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            margin-bottom: 1rem;
            text-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            animation: fadeInDown 1s both;
        }
        
        .hero-subtitle {
            font-size: 1.2rem;
            opacity: 0.8;
            max-width: 700px;
            margin: 0 auto 2rem;
            animation: fadeIn 1.5s both 0.3s;
        }
        
        .combos-section {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
            position: relative;
        }
        
        .section-title {
            font-size: 2.2rem;
            padding-top: 23px;
            margin-bottom: 2rem;
            position: relative;
            display: inline-block;
            animation: fadeIn 1s both; /* Cambiado a fadeIn para mejor efecto al centrar */
            text-align: center;
            left: 50%;
            transform: translateX(-50%);
    }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%; /* Centramos el pseudo-elemento */
            transform: translateX(-50%); /* Ajuste fino para centrado perfecto */
            width: 100px;
            height: 4px;
            background: linear-gradient(to right, var(--primary), var(--secondary));
            border-radius: 2px;
    }
        
        .combos-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }
        
        .combo-card {
            background: var(--card-bg);
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.3);
            position: relative;
            border: 1px solid rgba(106, 17, 203, 0.3);
            animation: fadeInUp 0.8s both;
            animation-delay: calc(var(--order) * 0.1s);
        }
        
        .combo-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 15px 40px -5px rgba(106, 17, 203, 0.4);
            border-color: rgba(106, 17, 203, 0.6);
        }
        
        .combo-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, transparent, rgba(106, 17, 203, 0.1), transparent);
            z-index: 1;
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .combo-card:hover::before {
            opacity: 1;
        }
        
        .combo-imagen-container {
            position: relative;
            overflow: hidden;
            height: 220px;
        }
        
        .combo-imagen {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        
        .combo-card:hover .combo-imagen {
            transform: scale(1.05);
        }
        
        .combo-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: linear-gradient(to right, var(--primary), var(--secondary));
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            box-shadow: var(--glow);
            z-index: 2;
        }
        
        .combo-info {
            padding: 1.5rem;
            position: relative;
            z-index: 2;
        }
        
        .combo-nombre {
            font-size: 1.4rem;
            font-weight: 600;
            margin-bottom: 0.8rem;
            color: white;
            position: relative;
            display: inline-block;
        }
        
        .combo-nombre::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 40px;
            height: 2px;
            background: var(--secondary);
            transition: width 0.3s ease;
        }
        
        .combo-card:hover .combo-nombre::after {
            width: 80px;
        }
        
        .combo-descripcion {
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 1.5rem;
            color: rgba(255, 255, 255, 0.8);
        }
        
        .combo-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 1rem;
        }
        
        .combo-precio {
            font-size: 1.5rem;
            font-weight: 700;
            background: linear-gradient(to right, #ff8a00, #ff4d4d);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        
        .combo-cta {
            color: var(--text);
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 5px;
            transition: color 0.3s ease;
        }
        
        .combo-cta i {
            transition: transform 0.3s ease;
        }
        
        .combo-card:hover .combo-cta {
            color: var(--secondary);
        }
        
        .combo-card:hover .combo-cta i {
            transform: translateX(3px);
        }
        
        /* Efectos especiales */
        .floating {
            animation: floating 6s ease-in-out infinite;
        }
        
        @keyframes floating {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
            100% { transform: translateY(0px); }
        }
        
        .pulse {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(106, 17, 203, 0.4); }
            70% { box-shadow: 0 0 0 15px rgba(106, 17, 203, 0); }
            100% { box-shadow: 0 0 0 0 rgba(106, 17, 203, 0); }
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }
            
            .combos-grid {
                grid-template-columns: 1fr;
            }
            
            .section-title {
                font-size: 1.8rem;
            }
        }
    </style>
    <script>
        // Efecto de partículas
        document.addEventListener('DOMContentLoaded', function() {
            const particlesContainer = document.getElementById('particles');
            const particleCount = 20;
            
            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.classList.add('particle');
                
                // Tamaño aleatorio entre 5px y 15px
                const size = Math.random() * 10 + 5;
                particle.style.width = `${size}px`;
                particle.style.height = `${size}px`;
                
                // Posición inicial aleatoria
                particle.style.left = `${Math.random() * 100}%`;
                particle.style.bottom = `-${size}px`;
                
                // Duración de animación aleatoria
                const duration = Math.random() * 30 + 20;
                particle.style.animationDuration = `${duration}s`;
                
                // Retraso aleatorio
                particle.style.animationDelay = `${Math.random() * 10}s`;
                
                // Opacidad aleatoria
                particle.style.opacity = Math.random() * 0.5 + 0.1;
                
                particlesContainer.appendChild(particle);
            }
            
            // Efecto hover más pronunciado
            const cards = document.querySelectorAll('.combo-card');
            cards.forEach(card => {
                card.addEventListener('mouseenter', () => {
                    card.classList.add('pulse');
                });
                
                card.addEventListener('mouseleave', () => {
                    card.classList.remove('pulse');
                });
            });
        });
    </script>
    </style>
</body>
</html>