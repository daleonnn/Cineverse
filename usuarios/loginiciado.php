<?php
session_start();
require_once '../api/db.php';

// Verificar sesión y cargar datos del usuario
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}

// Establecer el avatar por defecto
$_SESSION['user_avatar'] = 'default.png';


// Obtener las películas para el carrusel
$stmt = $pdo->query("SELECT * FROM carrusel ORDER BY id");
$peliculas_carrusel = $stmt->fetchAll(PDO::FETCH_ASSOC);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Procesar el formulario de agregar
    $titulo = $_POST['titulo'] ?? '';
    
    // Procesar la imagen
    $imagen_ruta = '';
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $imagen_nombre = basename($_FILES['imagen']['name']);
        $temp_name = $_FILES['imagen']['tmp_name'];
        
        // Mover el archivo a la ubicación deseada
        $upload_dir = '../../imagenes/carrusel/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        $imagen_ruta = $upload_dir . $imagen_nombre;
        move_uploaded_file($temp_name, $imagen_ruta);
        
        // Para acceder desde la web
        $imagen_ruta = '/cineapp/imagenes/carrusel/' . $imagen_nombre;
    }

    // Insertar en la base de datos
    $stmt = $pdo->prepare("INSERT INTO carrusel (titulo, imagen_ruta) VALUES (?, ?)");
    $stmt->execute([$titulo, $imagen_ruta]);

    header("Location: index.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
     <header>
<style>
  a {
    text-decoration: none; /* Quita el subrayado */
    color: inherit; /* Hereda el color del texto del contenedor */
  }

          @import url('https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap');
          header {
    position: fixed;
    width: 100%;
    background-color: #100919;
    z-index: 1000; /* Asegura que esté por encima del carrusel */
    top: 0;
    left: 0;
 }

 body {
    background-color: #100919;
    padding-top: 4.5rem; /* Ajuste para que el carrusel no quede tapado */
 }

 .barra-de-navegacion {
    max-width: 1100px;
    margin: 0 auto;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1rem 2rem;
    position: relative;
 }
 .barra-de-navegacion a, span {
    font-family: 'Poppins', sans-serif;
 }

 .logo{
    z-index: 20;
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 1rem;
 }
 .logo img{
    width: 3rem;
    border-radius: 90%;
 }
 .logo span{
    color: #EFEBFA;
    font-weight: 700;
    font-size: 1.5rem;
 }

 .list {
    color: #D6D0E0;
    list-style: none;
    display: flex;
    gap: 2.5rem;
    margin-right: auto; /* Esta línea es la clave para moverlo a la izquierda */
    padding-left: 90px; /* Espacio después del logo */
 }
 .list li a{
    color: #D6D0E0;
    text-decoration: none;
    font-size: 1.1rem;
    font-weight: 500;
 }
 .list li a:hover{
    color: white;
 }

 .btn{
    display: none;
 }
 .btn button{
    width: 1.8rem;
    height: 2rem;
    background-color: transparent;
    border: none;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    align-items: center;
    justify-content: center;
    cursor: pointer;
 }
 .btn button > div{
    background-color: white;
    width: 100%;
    height: 1.5px;
    transform-origin: left;
    transition: all 0.5s ease;
 }
 .btn.btn-toggle button div:first-child{
    transform: rotate(45deg);
 }
 .btn.btn-toggle button div:nth-child(2){
    opacity: 0;
 }
 .btn.btn-toggle button div:last-child{
    transform: rotate(-45deg);
 }
 @media (max-width: 680px){
    header{
        height: 100%;
        background-color: transparent;
    }
    .barra-de-navegacion{
        background-color: #100919;
    }
    .btn{
        display: block;
    }
    .nav{
        overflow: hidden;
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
        background-color:  #2C2359;   
    }
    .list{
        flex-direction: column;
        padding-top: 100px;
        width: 0;
        transition: all 0.5s ease;
    }
    .list.list-toggle{
        width: 250px;
        margin-left: 35px;
    }
 }
</style>    

<div class="barra-de-navegacion">
    <div class="logo">
        <a href="#home">
            <img src="imagenes/icono.jpg" alt="img">
            <a href="loginiciado.php"><span>CINEVERSE</span></a>
        </a>
    </div>

    <nav class="nav">
        <ul class="list">
        <li><a href="#cartelera" id="cartelera-link">Cartelera</a></li>
            <li><a href="#">Pronto</a></li>
            <li><a href="combos.php"><span>Comidas</span></a></li>
        </ul>
    </nav>


    <script>
    document.addEventListener('DOMContentLoaded', function() {
    const carteleraLink = document.getElementById('cartelera-link');
    const carrusel2 = document.querySelector('.carrusel2');
    const carteleraSection = document.querySelector('.cartelera');
    const peliculasContainer = document.querySelector('.peliculas-container');

    carteleraLink.addEventListener('click', function(e) {
        e.preventDefault();
        
        // Oculta el carrusel y activa la cartelera
        carrusel2.style.display = 'none';
        carteleraSection.classList.add('activa');
        
        // Scroll suave hacia la cartelera
        carteleraSection.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
        
        // Reinicia las animaciones de las películas
        const cards = document.querySelectorAll('.card');
        cards.forEach((card, index) => {
            card.style.animation = 'none';
            setTimeout(() => {
                card.style.animation = `fadeInUp 0.8s ease forwards ${index * 0.2}s`;
            }, 10);
        });
    });
});
</script>

<style>
    /* Ajuste para reducir espacio al mostrar solo cartelera */
    .cartelera.activa {
        padding-top: 80px !important;  /* Reduce el espacio superior */
        margin-top: 0 !important;
    }

    /* Efecto de aparición para las películas */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .card {
        opacity: 0;  /* Inicialmente invisible */
        animation: fadeInUp 0.8s ease forwards;
    }

    /* Retraso escalonado para cada película */
    .card:nth-child(1) { animation-delay: 0.1s; }
    .card:nth-child(2) { animation-delay: 0.3s; }
    .card:nth-child(3) { animation-delay: 0.5s; }
    .card:nth-child(4) { animation-delay: 0.7s; }
    .card:nth-child(5) { animation-delay: 0.9s; }
    .card:nth-child(6) { animation-delay: 0.11s; }
    .card:nth-child(7) { animation-delay: 0.13s; }
    .card:nth-child(8) { animation-delay: 0.15s; }
    .card:nth-child(9) { animation-delay: 0.17s; }
    .card:nth-child(10) { animation-delay: 0.19s; }
    /* Añade más si tienes más de 4 columnas */
</style>


    <div class="search-container">
    <input type="text" placeholder="Buscar películas..." class="search-input" id="searchInput">
    <div class="search-results" id="searchResults"></div>
</div>

<!--estilos barra de busqueda-->
<style>

 .barra-de-navegacion img {
    width: 2rem;
    margin-bottom: -6px;
 }

    /* Estilos para la barra de búsqueda */
    .search-container {
    position: relative;
    margin-left: auto;
    margin-right: 1.5rem;
    width: 200px;
 }

 .search-input {
    width: 100%;
    padding: 8px 15px;
    border: none;
    border-radius: 20px;
    background-color: rgba(255, 255, 255, 0.1);
    color: white;
    font-family: 'Poppins', sans-serif;
    border: 1px solid rgba(255, 255, 255, 0.2);
 }


 .search-container.active .search-input {
    width: 180px;
    padding: 8px 15px;
    opacity: 1;
    visibility: visible;
    background-color: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    position: absolute;
    right: 0; /* Se despliega desde la derecha */
 }


 .search-button {
    background: transparent;
    border: none;
    color: #D6D0E0;
    font-size: 1.1rem;
    cursor: pointer;
    margin-left: 5px;
 } 

 .search-button:hover {
    color: white;
 }

 .search-results {
    position: absolute;
    top: 100%;
    width: 100%;
    max-height: 300px;
    overflow-y: auto;
    background-color: #2C2359;
    border-radius: 8px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    padding: 10px;
    display: none;
    z-index: 1002;
 }

 .search-result-item {
    padding: 8px;
    color: #EFEBFA;
    font-family: 'Poppins', sans-serif;
    cursor: pointer;
    border-radius: 4px;
 }

 .search-result-item:hover {
    background-color: #3A2F7A;
 }

 /* Ajustes para móviles */
 @media (max-width: 680px) {
    .search-container {
        position: absolute;
        top: 1rem;
        right: 4.5rem;
        width: auto;
    }
    
    .search-container.active {
        width: calc(100% - 8rem);
    }
    
    .search-container.active .search-input {
        width: 100%;
    }
 }
</style>
<!--JavaScript de la barra de búsqueda-->
<script>
    document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const searchResults = document.getElementById('searchResults');
    
    // Función para buscar películas
    searchInput.addEventListener('input', function() {
        const query = this.value.trim();
        
        if (query.length > 1) { // Solo busca si hay al menos 3 caracteres
            buscarPeliculas(query);
        } else {
            searchResults.style.display = 'none';
        }
    });
    
    // Función para buscar en la base de datos
    function buscarPeliculas(query) {
        fetch(`../api/peliculas/buscar_peliculas.php?q=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => {
                mostrarResultados(data);
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }
    
    // Mostrar resultados
    function mostrarResultados(peliculas) {
        searchResults.innerHTML = '';
        
        if (peliculas.length > 0) {
            peliculas.forEach(pelicula => {
                const item = document.createElement('div');
                item.classList.add('search-result-item');
                item.innerHTML = `
                    <div>${pelicula.titulo}</div>
                    <small>${pelicula.genero} • ${pelicula.duracion} min</small>
                `;
                
                item.addEventListener('click', function() {
                    window.location.href = `detalle-pelicula.php?id=${pelicula.id_pelicula}`;
                });
                
                searchResults.appendChild(item);
            });
            
            searchResults.style.display = 'block';
        } else {
            searchResults.style.display = 'none';
        }
    }
    
    // Ocultar resultados al hacer clic fuera
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.search-container')) {
            searchResults.style.display = 'none';
        }
    });
});
</script>
           
<div class="btn">
        <button>
            <div></div>
            <div></div>
            <div></div>
        </button>
    </div>
    
    <div class="user-icon">
        <i class="fas fa-user"></i>
    </div>
</div>

<div class="login-flotante">
  <div class="user-info active" id="user-info">
    <div class="user-info-header">
<div class="avatar-container">
<img src="../assets/avatars/default.png" alt="Avatar" class="user-avatar" id="user-avatar">
        </div>
     <div class="user-details">
    <p class="user-name"><?php echo htmlspecialchars($_SESSION['user_name']); ?></p>
    <p class="user-email"><?php echo htmlspecialchars($_SESSION['user_email']); ?></p>
    </div>
    </div>
    <div class="user-options">
        <a href="#" class="option-item"><i class="fas fa-user"></i> Perfil</a>
        <a href="#" class="option-item" id="logout"><i class="fas fa-sign-out-alt"></i> Cerrar sesión</a>
    </div>
 </div>
</div>

<script>
    // Agrega esto al final de tu script existente o donde manejas los eventos del menú de usuario
document.getElementById('logout')?.addEventListener('click', function(e) {
    e.preventDefault();
    
    // Realizar la petición de cierre de sesión
    fetch('../api/usuarios/logout.php', {
        method: 'POST',
        credentials: 'same-origin'
    })
    .then(response => {
        if(response.ok) {
            // Redirigir a la página de inicio de sesión
            window.location.href = '../usuarios/index.php';
        } else {
            console.error('Error al cerrar sesión');
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
});
</script>

<Style>
 /* Estilos para el ícono de usuario */
 .user-icon {
    margin-left: 2rem;
    color: #D6D0E0;
    font-size: 1.5rem;
    cursor: pointer;
    transition: all 0.3s ease;
    z-index: 1001;
 }
 .user-icon:hover {
    color: white;
 }

 /* Estilos para el menú flotante de usuario */
 .login-flotante {
    position: absolute;
    top: 70px;
    right: 30px;
    width: 280px;
    max-height: 0;
    opacity: 0;
    background: #2C2359;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
    transition: all 0.3s ease;
    overflow: hidden;
    z-index: 1000;
    pointer-events: none;
 }

 .login-flotante.active {
    opacity: 1;
    max-height: 500px;
    padding: 20px;
    pointer-events: auto;
}


 .barra-de-navegacion:hover .login-flotante {
    opacity: 1;
    transform: translateY(0);
    visibility: visible;
 }

 .user-info {
    display: block;
    width: 100%;
    color: white;
    font-family: 'Poppins', sans-serif;
 }

 .user-info.active {
    display: block;
 }

 .user-info-header {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
 }

 .user-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
    cursor: pointer;
    transition: transform 0.3s ease;
    border: 2px solid #4a8fe7;
 }

 .user-details {
    line-height: 1.2;
 }

 .user-name {
    font-size: 16px;
    font-weight: bold;
    margin: 0;
 }

 .user-email {
    font-size: 14px;
    color: #D6D0E0;
    margin: 0;
 }

 .user-options {
    display: flex;
    flex-direction: column;
    gap: 10px;
 }

 .option-item {
    display: flex;
    align-items: center;
    gap: 10px;
    text-decoration: none;
    color: #D6D0E0;
    font-size: 14px;
    padding: 8px 12px;
    border-radius: 5px;
    transition: background-color 0.3s ease;
 }

 .option-item:hover {
    background-color: #3A2F7A;
    color: white;
 }

 .option-item i {
    font-size: 16px;
 }

 /* Estilos para el contenedor del avatar */
 .avatar-container {
    position: relative;
    display: inline-block;
    margin-right: 10px;
 } 

 .user-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
    cursor: pointer;
    transition: transform 0.3s ease;
    border: 2px solid #4a8fe7;
 }

 .edit-icon {
    position: absolute;
    bottom: 0;
    right: 0;
    background-color: rgba(0, 0, 0, 0.6);
    color: white;
    width: 20px;
    height: 20px;
    display: none;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    font-size: 10px;
    cursor: pointer;
    transition: all 0.3s ease;
 }

 .avatar-container:hover .edit-icon {
    display: flex;
 }

 .avatar-container:hover .user-avatar {
     transform: scale(1.1);
 }

 /* Selector de avatares (modal) */
.avatar-selector-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.7);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 2000;
    animation: fadeIn 0.3s ease;
}

.avatar-selector {
    background-color: #2C2359;
    padding: 25px;
    border-radius: 15px;
    width: 90%;
    max-width: 400px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5);
    text-align: center;
}

.avatar-selector h3 {
    color: #EFEBFA;
    font-family: 'Poppins', sans-serif;
    margin-bottom: 20px;
    font-size: 1.2rem;
}

.avatar-options {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
    margin-bottom: 25px;
}

.avatar-option {
    cursor: pointer;
    transition: all 0.3s ease;
    text-align: center;
}

.avatar-option img {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid transparent;
    transition: all 0.3s ease;
}

.avatar-option:hover img {
    border-color: #4a8fe7;
    transform: scale(1.05);
}

.close-selector {
    display: block;
    margin: 0 auto;
    padding: 10px 25px;
    background-color: #4a8fe7;
    color: white;
    border: none;
    border-radius: 30px;
    cursor: pointer;
    font-family: 'Poppins', sans-serif;
    font-weight: 500;
    transition: background-color 0.3s ease;
}

.close-selector:hover {
    background-color: #6A5ACD;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}
</Style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const userIcon = document.querySelector('.user-icon');
    const loginFlotante = document.querySelector('.login-flotante');
    
    // 1. Alternar menú al hacer clic en el ícono de usuario
    userIcon.addEventListener('click', function(e) {
        e.stopPropagation();
        loginFlotante.classList.toggle('active');
    });
    
    // 5. Cerrar menú al hacer clic fuera
    document.addEventListener('click', function(e) {
        if(!e.target.closest('.login-flotante') && !e.target.closest('.user-icon')) {
            loginFlotante.classList.remove('active');
        }
    });
});
</script>    
    </header>

    <div class="carrusel2">
    <div class="content">
    <?php foreach ($peliculas_carrusel as $index => $pelicula): ?>            <section style="--position: <?= $index ?>;" class="card-item">
                <div class="image-container">
                    <img src="<?= htmlspecialchars($pelicula['imagen_ruta']) ?>" alt="<?= htmlspecialchars($pelicula['titulo']) ?>" class="movie-image">
                </div>
                <div class="title"><?= htmlspecialchars($pelicula['titulo']) ?></div>
            </section>
        <?php endforeach; ?>
    </div>
</div>

<style>
/* ESTILOS CON MAYOR SEPARACIÓN Y TAMAÑO */
.carrusel2 {
    font-family: 'Poppins', sans-serif;
    overflow-x: hidden;
    margin-top: 25px;
    padding: 0 15px;
}

.carrusel2 .content {
    --scroll: 1;
    height: 20rem; /* Aumentado de 16rem */
    width: 18rem;  /* Aumentado de 14rem */
    position: relative;
    user-select: none;
    margin: 0 auto;
}

/* TARJETAS MÁS GRANDES Y SEPARADAS */
.carrusel2 .card-item {
    margin-left: 150%;
    --relPosition: 1;
    position: absolute;
    top: 0;
    left: calc(var(--relPosition) * 90%); /* Aumentado de 85% a 90% */
    height: 100%;
    width: 85%; /* Reducido de 90% a 85% para más espacio */
    padding: 0.6rem; /* Aumentado de 0.4rem */
    border-radius: 0.9rem; /* Bordes ligeramente más redondeados */
    display: flex;
    flex-direction: column;
    overflow: hidden;
    background: rgba(30, 30, 40, 0.35); /* Fondo ligeramente más visible */
    border: 1px solid rgba(255, 255, 255, 0.15);
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2); /* Sombra más pronunciada */
    z-index: calc(1000 + 100 * min(var(--relPosition), -1 * var(--relPosition)));
    transition: transform 0.2s ease-out;
    margin-right: 25px; /* Aumentado de 15px */
}

.carrusel2 .image-container {
    flex-grow: 1;
    border-radius: 0.6rem; /* Aumentado de 0.5rem */
    overflow: hidden;
    width: 100%;
    height: 82%; /* Ajuste fino */
}

.carrusel2 .movie-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.carrusel2 .title {
    color: white;
    font-weight: 500;
    padding: 0.4rem; /* Aumentado de 0.3rem */
    font-size: 1rem; /* Aumentado de 0.9rem */
    text-align: center;
    background: rgba(50, 50, 70, 0.7); /* Más opaco */
    border-radius: 0.4rem; /* Aumentado de 0.3rem */
    margin-top: 0.4rem; /* Aumentado de 0.3rem */
}
</style>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // VERSIÓN AJUSTADA PARA MAYOR SEPARACIÓN
    (function() {
        let mousePos = { x: 0, y: 0 };
        let lastMousePos = { x: 0, y: 0 };
        let isMouseDown = false;
        const friction = 0.72; /* Ligero ajuste para el nuevo espaciado */
        const snapStrength = 0.14; /* Snap ligeramente más suave */

        const handleMove = (x, y) => {
            mousePos.x = x;
            mousePos.y = y;
        };
        
        document.addEventListener("mousemove", (e) => handleMove(e.clientX, e.clientY));
        document.addEventListener("touchmove", (e) => handleMove(e.touches[0].clientX, e.touches[0].clientY), {passive: true});
        
        const handleStart = (x, y) => {
            isMouseDown = true;
            lastMousePos = { x, y };
        };
        
        document.addEventListener("mousedown", (e) => handleStart(e.clientX, e.clientY));
        document.addEventListener("touchstart", (e) => handleStart(e.touches[0].clientX, e.touches[0].clientY), {passive: true});
        
        document.addEventListener("mouseup", () => isMouseDown = false);
        document.addEventListener("touchend", () => isMouseDown = false);

        let scrollPosition = 0;
        let targetPosition = 0;
        let targetVelocity = 0;

        function animate() {
            const contentEl = document.querySelector(".carrusel2 .content");
            if (!contentEl) return;
            
            if (isMouseDown) {
                const rect = contentEl.getBoundingClientRect();
                targetVelocity = (-1.25 * (mousePos.x - lastMousePos.x)) / rect.width; /* Ajuste fino */
                lastMousePos = { ...mousePos };
            }
            
            targetPosition += targetVelocity;
            targetVelocity *= friction;
            
            const snappingPosition = Math.round(targetPosition);
            targetVelocity += (snappingPosition - targetPosition) * snapStrength;
            
            scrollPosition = lerp(scrollPosition, targetPosition, 0.22); /* Interpolación ligeramente más lenta */
            contentEl.style.setProperty("--scroll", scrollPosition);
            
            contentEl.querySelectorAll(".card-item").forEach((el, index) => {
                const pos = index - scrollPosition;
                el.style.setProperty("--relPosition", pos);
                el.style.transform = `translateX(calc(var(--relPosition) * 30px))`; /* Duplicado el espacio (antes 15px) */
                el.style.opacity = 1 - Math.min(Math.abs(pos) * 0.15, 0.5); /* Efecto fade sutil */
            });
            
            requestAnimationFrame(animate);
        }
        
        function lerp(a, b, t) {
            return a * (1 - t) + b * t;
        }
        
        animate();
    })();
});
</script>


</div>
















<div class="cartelera">
    <h1 class="titulo-cartelera">En cartelera</h1>
    
    <?php
    require_once "../api/db.php";
    
    $stmt = $pdo->query("SELECT * FROM peliculas");
    $peliculas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>

<div class="peliculas-container">
    <?php foreach ($peliculas as $pelicula): ?>
        <div class="card">
            <div class="poster">
                <img src="/cineapp/assets/img/peliculas/<?= htmlspecialchars($pelicula['imagen_nombre']) ?>" 
                     alt="<?= htmlspecialchars($pelicula['titulo']) ?>">
            </div>
            <div class="details">
                <h1><?= htmlspecialchars($pelicula['titulo']) ?></h1>
                <h2><?= htmlspecialchars($pelicula['duracion']) ?> min</h2>
                <div class="tags">
                    <span class="tag"><?= htmlspecialchars($pelicula['genero']) ?></span>
                    <span class="tag"><?= htmlspecialchars($pelicula['clasificacion']) ?></span>
                </div>
                <p class="desc"><?= htmlspecialchars($pelicula['descripcion']) ?></p>
                <button class="btn-detalles" data-id="<?= $pelicula['id_pelicula'] ?>">Ver Detalles</button>
            </div>
        </div>
    <?php endforeach; ?>
</div>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap');

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Poppins', sans-serif;
    }

    .cartelera {
        position: relative;
        width: 100%;
        height: 100%;
        padding: 40px 60px; /* Más espacio en los bordes (arriba/abajo 40px, izquierda/derecha 60px) */
        background: #100919;
    }

    .titulo-cartelera {
        text-align: center;
        margin-bottom: 30px;
        margin-top: 5px;
        font-size: 2.0rem;
        color: #D6D0E0;
        font-weight: 700;
        position: relative;
        padding-bottom: 15px;
    }

    .titulo-cartelera::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 120px;
        height: 4px;
        background: linear-gradient(90deg, #4a8fe7, #6A5ACD);
    }

    .peliculas-container {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 30px 20px; /* 30px vertical, 20px horizontal */
        justify-content: center;
        max-width: 1400px;
        margin: 0 auto;
    }

    .card {
        position: relative;
        width: 100%;
        height: 380px;
        background: #000;
        overflow: hidden;
        box-shadow: 0 5px 10px rgba(0, 0, 0, .2);
        transition: transform 0.3s ease;
        border-radius: 5px;
        margin-bottom: 9px;
    }

    .card:hover {
        transform: translateY(-5px);
    }

    .poster {
        position: relative;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        overflow: hidden;
    }

    .poster::before {
        content: '';
        position: absolute;
        bottom: -45%;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 1;
        transition: .3s;
    }

    .card:hover .poster::before {
        bottom: 0;
    }

    .poster img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: .3s;
    }

    .card:hover .poster img {
        transform: scale(1.1);
    }

    .details {
        position: absolute;
        bottom: -100%;
        left: 0;
        width: 100%;
        height: auto;
        padding: 1.2em 1.2em 1.5em;
        background: rgba(0, 0, 0, 0.7);
        backdrop-filter: blur(16px) saturate(120%);
        transition: .3s;
        color: #fff;
        z-index: 2;
        border-radius: 0;
    }

    .card:hover .details {
        bottom: 0;
    }

    .details h1 {
        font-size: 1.2em;
        margin-bottom: 5px;
        font-weight: 700;
    }

    .details h2 {
        font-size: 0.9em;
        margin-bottom: 8px;
        opacity: .6;
        font-weight: 400;
    }

    .tags {
        display: flex;
        gap: .375em;
        margin-bottom: .5em;
        font-size: .75em;
    }

    .tag {
        padding: .25rem .5rem;
        color: #fff;
        border: 1.5px solid rgba(255 255 255 / 0.4);
        border-radius: 15px;
    }

    .desc {
        color: #fff;
        opacity: .8;
        line-height: 1.4;
        margin-bottom: 0.8em;
        font-size: 0.8em;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .btn-detalles {
        background-color: #4a8fe7;
        color: white;
        border: none;
        padding: 8px 16px;
        cursor: pointer;
        font-size: 12px;
        transition: background-color 0.3s ease;
        width: max-content;
        border-radius: 15px;
    }

    .btn-detalles:hover {
        background-color: #6A5ACD;
    }

    /* Responsive */
    @media (max-width: 1200px) {
        .peliculas-container {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width: 900px) {
        .peliculas-container {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .titulo-cartelera {
            font-size: 2rem;
        }
    }

    @media (max-width: 600px) {
        .peliculas-container {
            grid-template-columns: 1fr;
        }
        
        .titulo-cartelera {
            font-size: 1.8rem;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btnDetalles = document.querySelectorAll('.btn-detalles');

        btnDetalles.forEach(button => {
            button.addEventListener('click', function() {
                window.location.href = 'detalle-pelicula.php?id=' + encodeURIComponent(this.dataset.id);
            });
        });
    });
</script>






<script>
document.addEventListener('DOMContentLoaded', function() {
    // Seleccionar elementos relevantes
    const prontoLink = document.querySelector('.list li a[href="#"]');
    const carteleraLink = document.getElementById('cartelera-link');
    const carteleraSection = document.querySelector('.cartelera');
    const carrusel2 = document.querySelector('.carrusel2');

    // Estado inicial: ambos elementos visibles
    if (carteleraSection) {
        carteleraSection.style.display = 'block';
    }
    if (carrusel2) {
        carrusel2.style.display = 'block';
    }

    // Agregar evento click al enlace "Pronto"
    prontoLink.addEventListener('click', function(e) {
        e.preventDefault(); // Prevenir comportamiento por defecto

        // Ocultar la cartelera
        if (carteleraSection) {
            carteleraSection.style.display = 'none';
        }

        // Mostrar solo el carrusel2
        if (carrusel2) {
            carrusel2.style.display = 'block';
            carrusel2.style.marginTop = '0'; // Ajustar margen superior
            document.body.style.paddingTop = '4.5rem'; // Asegurar que no haya espacio extra
        }
    });

    // Agregar evento click al enlace "Cartelera"
    carteleraLink.addEventListener('click', function(e) {
        e.preventDefault();

        // Mostrar la cartelera
        if (carteleraSection) {
            carteleraSection.style.display = 'block';
        }

        // Ocultar el carrusel2
        if (carrusel2) {
            carrusel2.style.display = 'none';
        }
    });
});
</script>








<script>
document.addEventListener('DOMContentLoaded', function() {
    // Seleccionar elementos relevantes
    const prontoLink = document.querySelector('.list li:nth-child(2) a'); // Selecciona el segundo li (Pronto)
    const carteleraLink = document.getElementById('cartelera-link');
    const carteleraSection = document.querySelector('.cartelera');
    const carrusel2 = document.querySelector('.carrusel2');
    const header = document.querySelector('header');

    // Función para mostrar solo el carrusel con efecto
    function showCarruselOnly() {
        // Ocultar la cartelera con efecto
        if (carteleraSection) {
            carteleraSection.style.opacity = '0';
            carteleraSection.style.height = '0';
            carteleraSection.style.overflow = 'hidden';
            carteleraSection.style.transition = 'all 0.5s ease';
            
            // Después de la transición, ocultar completamente
            setTimeout(() => {
                carteleraSection.style.display = 'none';
            }, 500);
        }

        // Mostrar y animar el carrusel
        if (carrusel2) {
            carrusel2.style.display = 'block';
            carrusel2.style.opacity = '0';
            carrusel2.style.transform = 'translateY(20px)';
            carrusel2.style.transition = 'all 0.5s ease 0.3s'; // Retardo para que empiece después de ocultar la cartelera
            
            // Forzar reflow para activar la transición
            void carrusel2.offsetWidth;
            
            carrusel2.style.opacity = '1';
            carrusel2.style.transform = 'translateY(0)';
            carrusel2.style.marginTop = '20px'; // Espacio después del header
        }
        
        // Ajustar el padding del body
        document.body.style.paddingTop = header.offsetHeight + 'px';
    }

    // Función para mostrar solo la cartelera con efecto
    function showCarteleraOnly() {
        // Ocultar el carrusel con efecto
        if (carrusel2) {
            carrusel2.style.opacity = '0';
            carrusel2.style.transform = 'translateY(-20px)';
            carrusel2.style.transition = 'all 0.3s ease';
            
            // Después de la transición, ocultar completamente
            setTimeout(() => {
                carrusel2.style.display = 'none';
            }, 300);
        }

        // Mostrar y animar la cartelera
        if (carteleraSection) {
            carteleraSection.style.display = 'block';
            carteleraSection.style.opacity = '0';
            carteleraSection.style.height = 'auto';
            carteleraSection.style.overflow = 'visible';
            carteleraSection.style.transform = 'translateY(20px)';
            carteleraSection.style.transition = 'all 0.5s ease 0.3s';
            
            // Forzar reflow para activar la transición
            void carteleraSection.offsetWidth;
            
            carteleraSection.style.opacity = '1';
            carteleraSection.style.transform = 'translateY(0)';
        }
        
        // Ajustar el padding del body
        document.body.style.paddingTop = header.offsetHeight + 'px';
        
        // Scroll suave hacia la cartelera
        carteleraSection.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    }

    // Agregar evento click al enlace "Pronto"
    if (prontoLink) {
        prontoLink.addEventListener('click', function(e) {
            e.preventDefault();
            showCarruselOnly();
        });
    }

    // Agregar evento click al enlace "Cartelera"
    if (carteleraLink) {
        carteleraLink.addEventListener('click', function(e) {
            e.preventDefault();
            showCarteleraOnly();
        });
    }

    // Estado inicial: mostrar ambos elementos
    if (carteleraSection) {
        carteleraSection.style.display = 'block';
        carteleraSection.style.opacity = '1';
    }
    if (carrusel2) {
        carrusel2.style.display = 'block';
        carrusel2.style.opacity = '1';
    }
});
</script>









</body>
</html>