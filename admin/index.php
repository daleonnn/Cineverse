<?php
require_once "../api/db.php";

// Obtener datos para cada sección
$peliculas = $pdo->query("SELECT * FROM peliculas")->fetchAll(PDO::FETCH_ASSOC);
$salas = $pdo->query("SELECT * FROM salas")->fetchAll(PDO::FETCH_ASSOC);
$combos = $pdo->query("SELECT * FROM combos")->fetchAll(PDO::FETCH_ASSOC);
$usuarios = $pdo->query("SELECT * FROM usuarios")->fetchAll(PDO::FETCH_ASSOC);
$carrusel = $pdo->query("SELECT * FROM carrusel ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
$funciones = $pdo->query("SELECT funciones.*, peliculas.titulo AS pelicula, salas.nombre AS sala 
                         FROM funciones
                         JOIN peliculas ON funciones.id_pelicula = peliculas.id_pelicula
                         JOIN salas ON funciones.id_sala = salas.id_sala")->fetchAll(PDO::FETCH_ASSOC);

// Datos para asientos
$id_sala_asientos = isset($_GET["id_sala_asientos"]) ? $_GET["id_sala_asientos"] : ($salas[0]["id_sala"] ?? 0);
$stmt = $pdo->prepare("SELECT capacidad FROM salas WHERE id_sala = ?");
$stmt->execute([$id_sala_asientos]);
$capacidad = $stmt->fetchColumn() ?: 0;

$stmt = $pdo->prepare("SELECT * FROM asientos WHERE id_sala = ?");
$stmt->execute([$id_sala_asientos]);
$asientos_registrados = $stmt->fetchAll(PDO::FETCH_ASSOC);
$asientos_disponibles = array_column($asientos_registrados, "numero");

for ($i = 1; $i <= $capacidad; $i++) {
    if (!in_array($i, $asientos_disponibles)) {
        $stmt = $pdo->prepare("INSERT INTO asientos (id_sala, numero, estado) VALUES (?, ?, 'disponible')");
        $stmt->execute([$id_sala_asientos, $i]);
    }
}

$stmt = $pdo->prepare("SELECT * FROM asientos WHERE id_sala = ? ORDER BY numero ASC");
$stmt->execute([$id_sala_asientos]);
$asientos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Cineverse</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --primary: #6a11cb;
            --secondary: #2575fc;
            --dark: #1a1a2e;
            --light: #f8f9fa;
            --success: #28a745;
            --danger: #dc3545;
            --warning: #ffc107;
            --info: #17a2b8;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            color: #333;
        }
        
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }
        
        header {
            background: linear-gradient(to right, var(--primary), var(--secondary));
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        
        header h1 {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        header h1 i {
            font-size: 1.5em;
        }
        
        .tabs {
            display: flex;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
        }
        
        .tab {
            padding: 15px 25px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
            border-bottom: 3px solid transparent;
        }
        
        .tab:hover {
            background: rgba(106, 17, 203, 0.1);
        }
        
        .tab.active {
            border-bottom: 3px solid var(--primary);
            color: var(--primary);
            background: rgba(106, 17, 203, 0.05);
        }
        
        .tab-content {
            display: none;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            animation: fadeIn 0.5s ease;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .tab-content.active {
            display: block;
        }
        
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .section-header h2 {
            color: var(--primary);
        }
        
        .btn {
            padding: 8px 15px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        
        .btn-primary {
            background: var(--primary);
            color: white;
        }
        
        .btn-primary:hover {
            background: #5a0cb0;
            transform: translateY(-2px);
        }
        
        .btn-danger {
            background: var(--danger);
            color: white;
        }
        
        .btn-danger:hover {
            background: #c82333;
        }
        
        .btn-success {
            background: var(--success);
            color: white;
        }
        
        .btn-success:hover {
            background: #218838;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
        }
        
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        
        th {
            background: linear-gradient(to right, var(--primary), var(--secondary));
            color: white;
            font-weight: 500;
        }
        
        tr:hover {
            background-color: rgba(106, 17, 203, 0.05);
        }
        
        .status-available {
            color: var(--success);
            font-weight: 500;
        }
        
        .status-occupied {
            color: var(--danger);
            font-weight: 500;
        }
        
        .action-link {
            color: var(--primary);
            text-decoration: none;
            margin-right: 10px;
            transition: all 0.3s ease;
        }
        
        .action-link:hover {
            color: var(--secondary);
            text-decoration: underline;
        }
        
        .action-link.delete {
            color: var(--danger);
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }
        
        select, input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        
        .asientos-grid {
            display: grid;
            grid-template-columns: repeat(10, 1fr);
            gap: 10px;
            margin-top: 20px;
        }
        
        .asiento {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .asiento.disponible {
            background: rgba(40, 167, 69, 0.2);
            border: 2px solid var(--success);
            color: var(--success);
        }
        
        .asiento.ocupado {
            background: rgba(220, 53, 69, 0.2);
            border: 2px solid var(--danger);
            color: var(--danger);
        }
        
        .pantalla {
            background: linear-gradient(to right, #bdc3c7, #2c3e50);
            color: white;
            text-align: center;
            padding: 10px;
            margin-bottom: 30px;
            border-radius: 5px;
            font-weight: bold;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        
        .action-btns {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }
        
        .card {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
        }
        
        .card-img-top {
            height: 200px;
            object-fit: cover;
        }
        
        .row {
            display: flex;
            flex-wrap: wrap;
            margin: -10px;
        }
        
        .col-md-4 {
            flex: 0 0 33.333333%;
            max-width: 33.333333%;
            padding: 10px;
        }
        
        @media (max-width: 768px) {
            .tabs {
                flex-direction: column;
            }
            
            .asientos-grid {
                grid-template-columns: repeat(5, 1fr);
            }
            
            table {
                display: block;
                overflow-x: auto;
            }
            
            .col-md-4 {
                flex: 0 0 100%;
                max-width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1><i class="fas fa-film"></i> Panel de Administración - Cineverse</h1>
        </header>
        
        <div class="tabs">
            <div class="tab active" data-tab="peliculas">Películas</div>
            <div class="tab" data-tab="salas">Salas</div>
            <div class="tab" data-tab="asientos">Asientos</div>
            <div class="tab" data-tab="funciones">Funciones</div>
            <div class="tab" data-tab="combos">Combos</div>
            <div class="tab" data-tab="usuarios">Usuarios</div>
            <div class="tab" data-tab="carrusel">Carrusel</div>
        </div>
        
        <!-- Contenido de Pestañas -->
        
        <!-- Pestaña Películas -->
        <div id="peliculas" class="tab-content active">
            <div class="section-header">
                <h2><i class="fas fa-film"></i> Gestión de Películas</h2>
                <a href="peliculas/add_pelicula.php" class="btn btn-primary"><i class="fas fa-plus"></i> Añadir Película</a>
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Título Original</th>
                        <th>Duración</th>
                        <th>Género</th>
                        <th>Clasificación</th>
                        <th>Estreno</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($peliculas as $pelicula): ?>
                        <tr>
                            <td><?= $pelicula["id_pelicula"] ?></td>
                            <td><strong><?= $pelicula["titulo"] ?></strong></td>
                            <td><?= $pelicula["titulo_original"] ?></td>
                            <td><?= $pelicula["duracion"] ?> min</td>
                            <td><?= $pelicula["genero"] ?></td>
                            <td><?= $pelicula["clasificacion"] ?></td>
                            <td><?= $pelicula["fecha_estreno"] ?></td>
                            <td>
                                <a href="peliculas/edit_pelicula.php?id=<?= $pelicula['id_pelicula'] ?>" class="action-link"><i class="fas fa-edit"></i> Editar</a>
                                <a href="peliculas/delete_pelicula.php?id=<?= $pelicula['id_pelicula'] ?>" class="action-link delete" onclick="return confirm('¿Eliminar esta película?')"><i class="fas fa-trash"></i> Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Pestaña Salas -->
        <div id="salas" class="tab-content">
            <div class="section-header">
                <h2><i class="fas fa-theater-masks"></i> Gestión de Salas</h2>
                <a href="salas/add_sala.php" class="btn btn-primary"><i class="fas fa-plus"></i> Añadir Sala</a>
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Capacidad</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($salas as $sala): ?>
                        <tr>
                            <td><?= $sala["id_sala"] ?></td>
                            <td><strong><?= $sala["nombre"] ?></strong></td>
                            <td><?= $sala["capacidad"] ?> asientos</td>
                            <td>
                                <a href="salas/edit_sala.php?id=<?= $sala['id_sala'] ?>" class="action-link"><i class="fas fa-edit"></i> Editar</a>
                                <a href="salas/delete_sala.php?id=<?= $sala['id_sala'] ?>" class="action-link delete" onclick="return confirm('¿Eliminar esta sala?')"><i class="fas fa-trash"></i> Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Pestaña Asientos -->
        <div id="asientos" class="tab-content">
            <div class="section-header">
                <h2><i class="fas fa-chair"></i> Gestión de Asientos</h2>
            </div>
            
            <form method="GET" class="form-group">
                <label for="id_sala_asientos">Selecciona una sala:</label>
                <select name="id_sala_asientos" id="id_sala_asientos" onchange="this.form.submit()">
                    <?php foreach ($salas as $sala): ?>
                        <option value="<?= $sala['id_sala'] ?>" <?= ($sala['id_sala'] == $id_sala_asientos) ? "selected" : "" ?>>
                            <?= $sala['nombre'] ?> (Capacidad: <?= $sala['capacidad'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>
            
            <div class="pantalla">PANTALLA</div>
            
            <div class="asientos-grid">
                <?php foreach ($asientos as $asiento): ?>
                    <div class="asiento <?= $asiento["estado"] ?>">
                        <?= $asiento["numero"] ?>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <table style="margin-top: 30px;">
                <thead>
                    <tr>
                        <th>Número</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($asientos as $asiento): ?>
                        <tr>
                            <td><?= $asiento["numero"] ?></td>
                            <td>
                                <span class="status-<?= $asiento["estado"] ?>">
                                    <?= $asiento["estado"] == "disponible" ? "Disponible" : "Ocupado" ?>
                                </span>
                            </td>
                            <td>
                                <a href="asientos/edit_asiento.php?id=<?= $asiento['id_asiento'] ?>" class="action-link"><i class="fas fa-edit"></i> Editar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Pestaña Funciones -->
        <div id="funciones" class="tab-content">
            <div class="section-header">
                <h2><i class="fas fa-calendar-alt"></i> Gestión de Funciones</h2>
                <a href="funciones/add_funcion.php" class="btn btn-primary"><i class="fas fa-plus"></i> Añadir Función</a>
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Película</th>
                        <th>Fecha y Hora</th>
                        <th>Precio</th>
                        <th>Sala</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($funciones as $funcion): ?>
                        <tr>
                            <td><?= $funcion["id_funcion"] ?></td>
                            <td><strong><?= $funcion["pelicula"] ?></strong></td>
                            <td><?= $funcion["fecha_hora"] ?></td>
                            <td>$<?= number_format($funcion["precio"], 0, ',', '.') ?> COP</td>
                            <td><?= $funcion["sala"] ?></td>
                            <td>
                                <a href="funciones/edit_funcion.php?id=<?= $funcion['id_funcion'] ?>" class="action-link"><i class="fas fa-edit"></i> Editar</a>
                                <a href="funciones/delete_funcion.php?id=<?= $funcion['id_funcion'] ?>" class="action-link delete"><i class="fas fa-trash"></i> Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Pestaña Combos -->
        <div id="combos" class="tab-content">
            <div class="section-header">
                <h2><i class="fas fa-concierge-bell"></i> Gestión de Combos</h2>
                <a href="combos/add_combo.php" class="btn btn-primary"><i class="fas fa-plus"></i> Añadir Combo</a>
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Precio</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($combos as $combo): ?>
                        <tr>
                            <td><?= $combo["id_combo"] ?></td>
                            <td><strong><?= $combo["nombre"] ?></strong></td>
                            <td><?= $combo["descripcion"] ?></td>
                            <td>$<?= number_format($combo["precio"], 0, '', '.') ?></td>
                            <td>
                                <a href="combos/edit_combo.php?id=<?= $combo['id_combo'] ?>" class="action-link"><i class="fas fa-edit"></i> Editar</a>
                                <a href="combos/delete_combo.php?id=<?= $combo['id_combo'] ?>" class="action-link delete"><i class="fas fa-trash"></i> Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Pestaña Usuarios -->
        <div id="usuarios" class="tab-content">
            <div class="section-header">
                <h2><i class="fas fa-users"></i> Gestión de Usuarios</h2>
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Fecha de Registro</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $usuario): ?>
                        <tr>
                            <td><?= $usuario["id_usuario"] ?></td>
                            <td><strong><?= $usuario["nombre"] ?></strong></td>
                            <td><?= $usuario["email"] ?></td>
                            <td><?= $usuario["fecha_registro"] ?></td>
                            <td>
                                <a href="usuarios/edit_usuario.php?id=<?= $usuario['id_usuario'] ?>" class="action-link"><i class="fas fa-edit"></i> Editar</a>
                                <a href="usuarios/delete_usuario.php?id=<?= $usuario['id_usuario'] ?>" class="action-link delete"><i class="fas fa-trash"></i> Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Pestaña Carrusel -->
        <div id="carrusel" class="tab-content">
            <div class="section-header">
                <h2><i class="fas fa-images"></i> Gestión del Carrusel</h2>
                <a href="carrusel/add_carrusel.php" class="btn btn-primary"><i class="fas fa-plus"></i> Añadir al Carrusel</a>
            </div>
            
            <div class="row" style="margin-top: 20px;">
                <?php foreach ($carrusel as $item): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <?php if ($item['imagen_ruta']): ?>
                                <img src="<?= htmlspecialchars($item['imagen_ruta']) ?>" class="card-img-top" alt="<?= htmlspecialchars($item['titulo']) ?>">
                            <?php else: ?>
                                <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center text-white">
                                    Sin imagen
                                </div>
                            <?php endif; ?>
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($item['titulo']) ?></h5>
                                <div class="action-btns">
                                    <a href="carrusel/edit_carrusel.php?id=<?= $item['id'] ?>" class="action-link"><i class="fas fa-edit"></i> Editar</a>
                                    <a href="carrusel/delete_carrusel.php?id=<?= $item['id'] ?>" class="action-link delete" onclick="return confirm('¿Eliminar este elemento?')"><i class="fas fa-trash"></i> Eliminar</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    
    <script>
        // Funcionalidad de pestañas
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('.tab');
            const tabContents = document.querySelectorAll('.tab-content');
            
            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    // Remover clase active de todas las pestañas y contenidos
                    tabs.forEach(t => t.classList.remove('active'));
                    tabContents.forEach(c => c.classList.remove('active'));
                    
                    // Agregar clase active a la pestaña clickeada
                    tab.classList.add('active');
                    
                    // Mostrar el contenido correspondiente
                    const tabId = tab.getAttribute('data-tab');
                    document.getElementById(tabId).classList.add('active');
                    
                    // Actualizar la URL con el parámetro de la pestaña
                    const url = new URL(window.location);
                    url.searchParams.set('tab', tabId);
                    window.history.pushState({}, '', url);
                });
            });
            
            // Mantener la pestaña activa después de recargar
            const urlParams = new URLSearchParams(window.location.search);
            const activeTab = urlParams.get('tab');
            if (activeTab) {
                const tabToActivate = document.querySelector(`.tab[data-tab="${activeTab}"]`);
                if (tabToActivate) {
                    tabs.forEach(t => t.classList.remove('active'));
                    tabContents.forEach(c => c.classList.remove('active'));
                    
                    tabToActivate.classList.add('active');
                    document.getElementById(activeTab).classList.add('active');
                }
            }
        });
    </script>
</body>
</html>