<?php
// Start session at the VERY beginning

// Then check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../loginiciado.php');
    exit(); // Always exit after header redirect
}

require_once '../api/db.php';

// Verificar sesión y cargar datos del usuario
// Obtener el avatar actualizado de la base de datos si es necesario
$stmt = $pdo->prepare("SELECT avatar FROM usuarios WHERE id_usuario = ?");
$stmt->execute([$_SESSION['user_id']]);
$userData = $stmt->fetch();

// Actualizar la sesión con el avatar más reciente
if ($userData) {
    $_SESSION['user_avatar'] = $userData['avatar'];
}
?>
<header>



    <style>
        a {
            text-decoration: none;
            /* Quita el subrayado */
            color: inherit;
            /* Hereda el color del texto del contenedor */
        }

        @import url('https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap');

        header {
            position: fixed;
            width: 100%;
            background-color: #100919;
            z-index: 1000;
            /* Asegura que esté por encima del carrusel */
            top: 0;
            left: 0;
        }

        body {
            background-color: #100919;
            padding-top: 4.5rem;
            /* Ajuste para que el carrusel no quede tapado */
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

        .barra-de-navegacion a,
        span {
            font-family: Arial, Helvetica, sans-serif;
        }

        .logo {
            z-index: 20;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 1rem;
        }

        .logo img {
            width: 3rem;
            border-radius: 90%;
        }

        .logo span {
            color: #EFEBFA;
            font-weight: 700;
            font-size: 1.5rem;
        }

        .list {
            color: #D6D0E0;
            list-style: none;
            display: flex;
            gap: 2.5rem;
            margin-right: auto;
            /* Esta línea es la clave para moverlo a la izquierda */
            padding-left: 90px;
            /* Espacio después del logo */
        }

        .list li a {
            color: #D6D0E0;
            text-decoration: none;
            font-size: 1.1rem;
            font-weight: 500;
        }

        .list li a:hover {
            color: white;
        }

        .btn {
            display: none;
        }

        .btn button {
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

        .btn button>div {
            background-color: white;
            width: 100%;
            height: 1.5px;
            transform-origin: left;
            transition: all 0.5s ease;
        }

        .btn.btn-toggle button div:first-child {
            transform: rotate(45deg);
        }

        .btn.btn-toggle button div:nth-child(2) {
            opacity: 0;
        }

        .btn.btn-toggle button div:last-child {
            transform: rotate(-45deg);
        }

        @media (max-width: 680px) {
            header {
                height: 100%;
                background-color: transparent;
            }

            .barra-de-navegacion {
                background-color: #100919;
            }

            .btn {
                display: block;
            }

            .nav {
                overflow: hidden;
                position: absolute;
                top: 0;
                left: 0;
                height: 100%;
                background-color: #2C2359;
            }

            .list {
                flex-direction: column;
                padding-top: 100px;
                width: 0;
                transition: all 0.5s ease;
            }

            .list.list-toggle {
                width: 250px;
                margin-left: 35px;
            }
        }
    </style>

    <div class="barra-de-navegacion">
        <div class="logo">
            <a href="#home">
                <img src="imagenes/icono.jpg" alt="img">
                <span>CINEVERSE</span>
            </a>
        </div>

        <nav class="nav">
            <ul class="list">
                <li><a href="#cartelera" id="cartelera-link">Cartelera</a></li>
                <li><a href="#">Pronto</a></li>
                <li><a href="#">Comidas</a></li>
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
                padding-top: 80px !important;
                /* Reduce el espacio superior */
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
                opacity: 0;
                /* Inicialmente invisible */
                animation: fadeInUp 0.8s ease forwards;
            }

            /* Retraso escalonado para cada película */
            .card:nth-child(1) {
                animation-delay: 0.1s;
            }

            .card:nth-child(2) {
                animation-delay: 0.3s;
            }

            .card:nth-child(3) {
                animation-delay: 0.5s;
            }

            .card:nth-child(4) {
                animation-delay: 0.7s;
            }

            .card:nth-child(5) {
                animation-delay: 0.9s;
            }

            .card:nth-child(6) {
                animation-delay: 0.11s;
            }

            .card:nth-child(7) {
                animation-delay: 0.13s;
            }

            .card:nth-child(8) {
                animation-delay: 0.15s;
            }

            .card:nth-child(9) {
                animation-delay: 0.17s;
            }

            .card:nth-child(10) {
                animation-delay: 0.19s;
            }

            /* Añade más si tienes más de 4 columnas */
        </style>


        <div class="search-container">
            <input type="text" placeholder="Buscar películas..." class="search-input" id="searchInput">
            <div class="search-results" id="searchResults"></div>
        </div>

        <!--estilos barra de busqueda-->
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
                right: 0;
                /* Se despliega desde la derecha */
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
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
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
                    <img src="../assets/avatars/<?php echo htmlspecialchars($_SESSION['user_avatar'] ?? 'avatar1.png'); ?>"
                        alt="Avatar"
                        class="user-avatar"
                        id="user-avatar">
                    <div class="edit-icon"><i class="fas fa-pencil-alt"></i></div>
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
            right: 30px;
            /* Más alejado del borde derecho */
            width: 0;
            height: 0;
            opacity: 0;
            background: #2C2359;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
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
            margin-bottom: 20px;
            /* Más espacio entre campos */
        }

        .input-group label {
            display: block;
            color: #D6D0E0;
            font-family: 'Poppins', sans-serif;
            margin-top: 0px;



        }

        .input-group input {
            width: calc(100% - 20px);
            /* Ancho completo con padding */
            padding: 10px;
            margin-top: 5px;
            border-radius: 4px;
            border: none;
        }

        .btn-login {
            width: 90%;
            padding: 14px;
            /* Botón más alto y cómodo */
            margin-top: 10px;
            margin-bottom: 10px;
            color: #fff;
            background: linear-gradient(135deg, #6A5ACD, #483D8B);
            /* Degradado elegante */
            border-radius: 8px;
            border: none;
            font-size: 16px;
            font-weight: bold;
            transition: all 0.3s ease-in-out;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            cursor: pointer;
            font-family: Arial, Helvetica, sans-serif;
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
            font-family: 'Poppins';
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