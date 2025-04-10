<?php
require_once '../api/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../usuarios/index.php');
    exit();
}

// Obtener datos actualizados del usuario
$stmt = $pdo->prepare("SELECT avatar, nombre, email FROM usuarios WHERE id_usuario = ?");
$stmt->execute([$_SESSION['user_id']]);
$userData = $stmt->fetch();

if ($userData) {
    $_SESSION['user_avatar'] = $userData['avatar'];
    $_SESSION['user_name'] = $userData['nombre'];
    $_SESSION['user_email'] = $userData['email'];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CINEVERSE</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        a {
            text-decoration: none;
            color: inherit;
        }

        header {
            position: fixed;
            width: 100%;
            background-color: #100919;
            z-index: 1000;
            top: 0;
            left: 0;
        }

        body {
            background-color: #100919;
            padding-top: 4.5rem;
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

        .logo {
            z-index: 20;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 1rem;
        }

        .logo img {
            width: 2rem;
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
            padding-left: 90px;
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

        .btn button > div {
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

        /* Responsive */
        @media (max-width: 768px) {
            .nav, .search-container {
                display: none;
            }
            
            .btn {
                display: block;
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

            /* Ajustes para móviles */
            @media (max-width: 680px) {
                header {
                    height: 100%;
                    background-color: transparent;
                }
                
                .barra-de-navegacion {
                    background-color: #100919;
                }
                
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
    </style>
</head>
<body>
<header>
    <div class="barra-de-navegacion">
        <!-- Logo -->
        <div class="logo">
            <img src="../assets/img/icono.jpg" alt="CINEVERSE">
            <a href="loginiciado.php"><span>CINEVERSE</span></a>
        </div>
        
        <!-- Menú de navegación -->
        <nav class="nav">
            <ul class="list">
                <li><a href="loginiciado.php#cartelera">Cartelera</a></li>
                <li><a href="loginiciado.php#pronto">Pronto</a></li>
                <li><a href="combos.php"><span>Comida</span></a></li>
            </ul>
        </nav>
        
        <!-- Buscador -->
        <div class="search-container">
            <input type="text" placeholder="Buscar películas..." class="search-input" id="searchInput">
            <div class="search-results" id="searchResults"></div>
        </div>
        
        <!-- Botón de menú móvil -->
        <button class="btn">
            <div></div>
            <div></div>
            <div></div>
        </button>
        
        <!-- Icono de usuario -->
        <div class="user-icon" id="userIcon">
            <i class="fas fa-user"></i>
        </div>
        
        <!-- Menú flotante del usuario -->
        <div class="login-flotante" id="loginFlotante">
            <div class="user-info">
                <div class="user-info-header">
                <div class="avatar-container">
                <img src="../assets/avatars/default.png" alt="Avatar" class="user-avatar" id="user-avatar">
                </div>
                    <div class="user-details">
                        <p class="user-name"><?= htmlspecialchars($_SESSION['user_name']) ?></p>
                        <p class="user-email"><?= htmlspecialchars($_SESSION['user_email']) ?></p>
                    </div>
                </div>
                <div class="user-options">
                    <a href="perfil.php" class="option-item">
                        <i class="fas fa-user"></i>
                        <span>Perfil</span>
                    </a>
                    <a href="../api/usuarios/logout.php" class="option-item" id="logoutBtn">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Cerrar sesión</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const userIcon = document.getElementById('userIcon');
        const loginFlotante = document.getElementById('loginFlotante');
        
        // Alternar menú de usuario
        userIcon.addEventListener('click', function(e) {
            e.stopPropagation();
            loginFlotante.classList.toggle('active');
        });
        
        // Cerrar menú al hacer clic fuera
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.login-flotante') && !e.target.closest('#userIcon')) {
                loginFlotante.classList.remove('active');
            }
        });

        // JavaScript de la barra de búsqueda
        const searchInput = document.getElementById('searchInput');
        const searchResults = document.getElementById('searchResults');
        
        searchInput.addEventListener('input', function() {
            const query = this.value.trim();
            
            if (query.length > 1) {
                buscarPeliculas(query);
            } else {
                searchResults.style.display = 'none';
            }
        });
        
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
        
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.search-container')) {
                searchResults.style.display = 'none';
            }
        });

        // Menú móvil
        const btnMenu = document.querySelector('.btn');
        const navList = document.querySelector('.list');
        
        btnMenu.addEventListener('click', function() {
            btnMenu.classList.toggle('btn-toggle');
            navList.classList.toggle('list-toggle');
        });
    });
</script>