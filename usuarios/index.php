<?php
require_once '../api/db.php';

// Obtener las funciones disponibles
$stmt = $pdo->query("SELECT f.id_funcion, p.titulo, s.nombre AS sala, f.fecha_hora 
                     FROM funciones f
                     JOIN peliculas p ON f.id_pelicula = p.id_pelicula
                     JOIN salas s ON f.id_sala = s.id_sala
                     ORDER BY f.fecha_hora ASC");
$funciones = $stmt->fetchAll(PDO::FETCH_ASSOC);


$stmt = $pdo->query("SELECT * FROM carrusel ORDER BY id");
$peliculas = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
            <a href="index.php"><span>CINEVERSE</span></a>
        </a>
    </div>

    <nav class="nav">
        <ul class="list">
            <li><a href="#cartelera" id="cartelera-link">Cartelera</a></li>
            <li><a href="#">Pronto</a></li>
            <li><a href="combos.php" onclick="window.location.href='combos.php'; return false;">Comidas</a></li>
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

<!--estilos barra de busqueda (se mantienen igual)-->
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

    .olvidar-link {
        font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
        font-size: 80%;
        text-align: left;
        color:rgba(194, 172, 241, 0.96);
        margin-top: 20px;
        margin-left: 10px;
    }
</style>

<!--JavaScript de la barra de búsqueda modificado-->
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
                
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    // Mostrar el modal en lugar de redirigir
                    const modal = document.getElementById('loginRequiredModal');
                    if (modal) {
                        modal.style.display = 'block';
                    }
                    // Ocultar los resultados de búsqueda
                    searchResults.style.display = 'none';
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
    <div class="login-contenido active" id="login-form">
        <h3>Iniciar Sesión</h3>
        <form id="loginForm">
    <div class="input-group">
        <label>Correo:</label>
        <input type="text" name="email" placeholder="Ingresar correo" required>
    </div>
    
    <div class="input-group">
        <label>Contraseña:</label>
        <input type="password" name="password" placeholder="Ingresar contraseña" required>
    </div>
    
    <button type="submit" class="btn-login">Ingresar</button>
    
    <div class="registro-link">
        ¿No tienes cuenta? <a href="#" id="mostrar-registro">Regístrate</a>
    </div>

    <div class="olvidar-link">
        ¿Olvidaste tu contraseña? <a href="../api/usuarios/recuperar.php">Recupérala aquí</a>
    </div>
</form>
    </div>
<!--Registrar-->
    <div class="login-contenido" id="register-form">
        <h3>Registrarse</h3>
        <form id="registerForm">
    <div class="input-group">
        <label>Nombre:</label>
        <input type="text" name="nombre" placeholder="Ingresar nombre completo" required>
    </div>
    
    <div class="input-group">
        <label>Correo:</label>
        <input type="email" name="email" placeholder="Ingresar correo" required>
    </div>
    
    <div class="input-group">
        <label>Contraseña:</label>
        <input type="password" name="password" placeholder="Crear contraseña" required>
    </div>
    
    <button type="submit" class="btn-login">Registrarse</button>
    
    <div class="registro-link">
        ¿Ya tienes cuenta? <a href="#" id="mostrar-login">Inicia Sesión</a>
    </div>
</form>
    </div>
</div>

<!--CSS DEL LOGIN FLOTANTE-->
<style>
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

 /* Estilos para el login flotante */
 .login-flotante {
    position: absolute;
    top: 70px;
    right: 30px; /* Más alejado del borde derecho */
    width: 0;
    height: 0;
    opacity: 0;
    background: #2C2359;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    transition: all 0.3s ease;
    overflow: hidden;
    z-index: 1000;
 }


 .barra-de-navegacion:hover .login-flotante {
    opacity: 1;
    transform: translateY(0);
    visibility: visible;
 }

 .login-contenido h3 {
    color: white;
    margin-bottom: 20px;
    text-align: center;
    font-family: 'Poppins', sans-serif;
 }

 .login-contenido {
    width: 100%;
 }


 .input-group {
    margin-bottom: 20px; /* Más espacio entre campos */
 }

 .input-group label {
    display: block;
    color: #D6D0E0;
    font-family: 'Poppins', sans-serif;
    margin-top: 0px;
    

    
 }

 .input-group input {
    width: calc(100% - 20px); /* Ancho completo con padding */
    padding: 10px;
    margin-top: 5px;
    border-radius: 4px;
    border: none;
 }

 .btn-login {
    width: 90%;
    padding: 14px; /* Botón más alto y cómodo */
    margin-top: 10px;
    margin-bottom: 10px;
    color: #fff;
    background: linear-gradient(135deg, #6A5ACD, #483D8B); /* Degradado elegante */
    border-radius: 8px;
    border: none;
    font-size: 16px;
    font-weight: bold;
    transition: all 0.3s ease-in-out;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    cursor: pointer;
    font-family: 'Poppins', sans-serif;
    margin-left: 10px;
 }

 .btn-login:hover {
    background: linear-gradient(135deg, #7B68EE, #5A4FBF);
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
 }

 .btn-login:active {
    transform: translateY(1px);
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
 }

 .registro-link {
    text-align: center;
    padding-top: 10px;
    border-top: 1px solid #3a3460;
    margin-top: 15px;
    font-family: 'Poppins', sans-serif;
    color: white;
 }

 .registro-link a {
    color: #4a8fe7;
    text-decoration: none;
 }

 .registro-link a:hover {
    text-decoration: underline;
 }

 #register-form {
    display: none;
 }

 #register-form.active {
    display: block;
 }

 #login-form {
    display: none;
 }

 #login-form.active {
    display: block;
 }

 .login-contenido input[type="email"]:invalid {
    border: 1px solid #ff6b6b;
 }

 .login-contenido input[type="email"]:valid {
    border: 1px solid #51cf66;
 }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
    const userIcon = document.querySelector('.user-icon');
    const loginFlotante = document.querySelector('.login-flotante');
    const mostrarRegistro = document.getElementById('mostrar-registro');
    const mostrarLogin = document.getElementById('mostrar-login');
    const loginForm = document.getElementById('login-form');
    const registerForm = document.getElementById('register-form');







    // ============ [NUEVO CÓDIGO - INICIO] ============
    // Manejo del formulario de login
    document.getElementById('loginForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const form = this;
        const btn = form.querySelector('button[type="submit"]');
        const originalText = btn.textContent;
        
        try {
            btn.disabled = true;
            btn.textContent = 'Ingresando...';
            
            const formData = new FormData(form);
            const response = await fetch('/cineapp/api/usuarios/login.php', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (!response.ok || data.status !== 'success') {
                throw new Error(data.message || 'Error en el inicio de sesión');
            }
            
            // Redirigir después de login exitoso
            if (data.redirect) {
                window.location.href = data.redirect;
            }
            
        } catch (error) {
            console.error('Error:', error);
            alert(error.message || 'Error al iniciar sesión');
        } finally {
            btn.disabled = false;
            btn.textContent = originalText;
        }
    });
    // ============ [NUEVO CÓDIGO - FIN] ============
















    
    // Mostrar/ocultar login
    userIcon.addEventListener('click', function(e) {
        e.stopPropagation();
        if (loginFlotante.style.opacity === '1') {
            hideLogin();
        } else {
            showLogin();
        }
    });

    // Manejar clicks en los formularios
    loginForm.addEventListener('click', function(e) {
        e.stopPropagation();
    });

    registerForm.addEventListener('click', function(e) {
        e.stopPropagation();
    });

    // Mostrar registro
    mostrarRegistro.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        loginForm.classList.remove('active');
        registerForm.classList.add('active');
    });
    
    // Mostrar login
    mostrarLogin.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        registerForm.classList.remove('active');
        loginForm.classList.add('active');
    });

    // Ocultar al hacer clic fuera
    document.addEventListener('click', function() {
        hideLogin();
    });

    function showLogin() {
        loginFlotante.style.width = '300px';
        loginFlotante.style.height = 'auto';
        loginFlotante.style.opacity = '1';
        loginFlotante.style.padding = '20px';
        loginForm.classList.add('active');
        registerForm.classList.remove('active');
    }

    function hideLogin() {
        loginFlotante.style.width = '0';
        loginFlotante.style.height = '0';
        loginFlotante.style.opacity = '0';
        loginFlotante.style.padding = '0';
    }
 });






 document.getElementById('registerForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const form = this;
    const btn = form.querySelector('button[type="submit"]');
    const originalText = btn.textContent;
    
    try {
        btn.disabled = true;
        btn.textContent = 'Registrando...';
        
        // Convertir FormData a objeto
        const formData = {};
        new FormData(form).forEach((value, key) => formData[key] = value);
        
        const response = await fetch('/cineapp/api/usuarios/register.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(formData)
        });

        const text = await response.text();
        let data;
        
        try {
            data = JSON.parse(text);
        } catch {
            console.error('Respuesta no JSON:', text);
            throw new Error('Error en el formato de respuesta');
        }

        if (!response.ok || data.status !== 'success') {
            throw new Error(data.message || 'Error en el registro');
        }
        
        alert(data.message || '¡Registro exitoso!');
        form.reset();
        document.getElementById('register-form').classList.remove('active');
        document.getElementById('login-form').classList.add('active');
        
    } catch (error) {
        console.error('Error:', error);
        alert(error.message || 'Error al registrar');
    } finally {
        btn.disabled = false;
        btn.textContent = originalText;
    }
 });
</script>    
    </header>

    <div class="carrusel2">
    <div class="content">
        <?php foreach ($peliculas as $index => $pelicula): ?>
            <section style="--position: <?= $index ?>;" class="card-item">
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

    <!-- Modal para requerir inicio de sesión (solo con la X) -->
    <div id="loginRequiredModal" class="modal">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <h2>Inicia sesión para continuar</h2>
            <p>Debes iniciar sesión para ver los detalles de las películas.</p>
        </div>
    </div>

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
    /* Todos tus estilos originales de la cartelera se mantienen exactamente igual */
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
        padding: 40px 60px;
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
        gap: 30px 20px;
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

    /* Estilos para el modal (solo lo necesario) */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.7);
    }

    .modal-content {
        background-color: #1a1a2e;
        margin: 15% auto;
        padding: 25px;
        border-radius: 10px;
        width: 90%;
        max-width: 400px;
        color: #fff;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        position: relative;
        text-align: center;
    }

    .close-modal {
        position: absolute;
        top: 10px;
        right: 15px;
        color: #aaa;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }

    .close-modal:hover {
        color: #fff;
    }

    .modal-content h2 {
        margin-bottom: 15px;
        color: #fff;
    }

    .modal-content p {
        margin-bottom: 5px;
        color: #ccc;
    }

    /* Responsive (original se mantiene igual) */
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
        
        .modal-content {
            margin: 30% auto;
            width: 85%;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btnDetalles = document.querySelectorAll('.btn-detalles');
        const modal = document.getElementById('loginRequiredModal');
        const closeModal = document.querySelector('.close-modal');

        // Función para abrir el modal
        function openModal() {
            modal.style.display = 'block';
        }

        // Función para cerrar el modal
        function closeModalFunc() {
            modal.style.display = 'none';
        }

        // Event listeners para los botones de detalles
        btnDetalles.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                openModal();
            });
        });

        // Event listener para cerrar el modal con la X
        closeModal.addEventListener('click', closeModalFunc);

        // Cerrar modal al hacer clic fuera del contenido
        window.addEventListener('click', function(event) {
            if (event.target === modal) {
                closeModalFunc();
            }
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