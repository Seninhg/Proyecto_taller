<?php
/**
 * Página de Obtener Información
 */

session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: ../index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Obtener Información - Gestión de Bienestar Estudiantil</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        .page-header {
            background: linear-gradient(135deg, #3498db 0%, #2ecc71 100%);
            color: white;
            padding: 2rem;
            border-radius: 8px;
            margin-bottom: 2rem;
        }

        .page-header h2 {
            margin: 0;
            font-size: 1.8rem;
        }

        .section {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: bold;
            color: #2c3e50;
        }

        .form-group input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }

        .form-group input:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        }

        .btn {
            padding: 0.75rem 1.5rem;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: bold;
            transition: background 0.3s;
        }

        .btn:hover {
            background: #2980b9;
        }

        .btn:disabled {
            background: #bdc3c7;
            cursor: not-allowed;
        }

        .alert {
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: 1rem;
            display: none;
        }

        .alert.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            display: block;
        }

        .alert.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            display: block;
        }

        .loading {
            display: none;
            text-align: center;
            color: #3498db;
            font-weight: bold;
        }

        .loading.active {
            display: block;
        }

        .cursos-container {
            display: none;
        }

        .cursos-container.active {
            display: block;
        }

        .curso-card {
            background: #f9f9f9;
            border-left: 4px solid #3498db;
            padding: 1.5rem;
            margin-bottom: 1rem;
            border-radius: 4px;
            transition: transform 0.2s;
        }

        .curso-card:hover {
            transform: translateX(5px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .curso-titulo {
            font-weight: bold;
            color: #2c3e50;
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
        }

        .curso-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .curso-info-item {
            color: #555;
        }

        .curso-info-item strong {
            color: #2c3e50;
        }

        .curso-notas {
            background: white;
            padding: 0.75rem;
            border-radius: 4px;
            margin-top: 0.75rem;
        }

        .nota-item {
            display: inline-block;
            background: #3498db;
            color: white;
            padding: 0.25rem 0.5rem;
            margin-right: 0.5rem;
            margin-bottom: 0.25rem;
            border-radius: 3px;
            font-size: 0.85rem;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .stat-card {
            background: linear-gradient(135deg, #3498db 0%, #2ecc71 100%);
            color: white;
            padding: 1.5rem;
            border-radius: 8px;
            text-align: center;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 0.25rem;
        }

        .stat-label {
            font-size: 0.9rem;
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="container">
                <div class="navbar-brand">
                    <h1>Gestión de Bienestar Estudiantil</h1>
                </div>
                <ul class="menu">
                    <li><a href="dashboard.php" class="nav-link">Dashboard</a></li>
                    <li><a href="student-info.php" class="nav-link">Información de Estudiante</a></li>
                    <li><a href="scraping.php" class="nav-link active">Obtener Información</a></li>
                    <li class="menu-separator"></li>
                    <li><a href="scraping.php?logout=1" class="logout-btn">Cerrar Sesión</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <main>
        <div class="container">
            <div class="page-header">
                <h2>Obtener Calificaciones</h2>
                <p>Descarga automáticamente tus calificaciones del UCSS intranet</p>
            </div>

            <div class="section">
                <h3>Ingresar Credenciales</h3>
                
                <div id="alertBox" class="alert"></div>
                
                <form id="scrapingForm">
                    <div class="form-group">
                        <label for="usuario">Código de Usuario UCSS:</label>
                        <input type="text" id="usuario" name="usuario" placeholder="Ej: 202110257" required>
                    </div>

                    <div class="form-group">
                        <label for="contrasena">Contraseña:</label>
                        <input type="password" id="contrasena" name="contrasena" required>
                    </div>

                    <button type="submit" class="btn" id="btnSubmit">Obtener Calificaciones</button>
                </form>

                <div id="loading" class="loading">
                    ⏳ Obteniendo calificaciones... (esto puede tomar 30-60 segundos)
                </div>
            </div>

            <div id="cursosSection" class="cursos-container">
                <div class="section">
                    <h3>Tus Calificaciones</h3>
                    
                    <div id="statsContainer" class="stats"></div>
                    
                    <div id="cursosList"></div>
                </div>
            </div>
        </div>
    </main>

    <script>
        document.getElementById('scrapingForm').addEventListener('submit', async (e) => {
            e.preventDefault();

            const usuario = document.getElementById('usuario').value;
            const contrasena = document.getElementById('contrasena').value;
            const alertBox = document.getElementById('alertBox');
            const loading = document.getElementById('loading');
            const cursosSection = document.getElementById('cursosSection');
            const btnSubmit = document.getElementById('btnSubmit');

            // Limpiar alertas previas
            alertBox.className = 'alert';
            alertBox.textContent = '';
            cursosSection.classList.remove('active');

            // Mostrar loading
            loading.classList.add('active');
            btnSubmit.disabled = true;

            try {
                const response = await fetch('http://localhost:3000/api/scraping/notas', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        usuario,
                        contrasena,
                        userId: '<?php echo $_SESSION['user_id']; ?>'
                    })
                });

                const data = await response.json();

                if (data.success) {
                    // Mostrar estadísticas
                    const stats = document.getElementById('statsContainer');
                    stats.innerHTML = `
                        <div class="stat-card">
                            <div class="stat-number">${data.cantidad_cursos}</div>
                            <div class="stat-label">Cursos</div>
                        </div>
                    `;

                    // Mostrar cursos
                    const cursosList = document.getElementById('cursosList');
                    cursosList.innerHTML = data.data.cursos.map((curso, idx) => `
                        <div class="curso-card">
                            <div class="curso-titulo">${idx + 1}. ${curso.nombre}</div>
                            <div class="curso-info">
                                <div class="curso-info-item"><strong>Código:</strong> ${curso.codigo}</div>
                                <div class="curso-info-item"><strong>Créditos:</strong> ${curso.creditos}</div>
                                <div class="curso-info-item"><strong>Tipo:</strong> ${curso.tipo}</div>
                                <div class="curso-info-item"><strong>Docente:</strong> ${curso.docente}</div>
                            </div>
                            <div class="curso-notas">
                                ${Object.entries(curso.calificaciones).map(([tipo, nota]) => 
                                    `<span class="nota-item">${tipo}: ${nota}</span>`
                                ).join('')}
                            </div>
                        </div>
                    `).join('');

                    // Mostrar sección de cursos
                    cursosSection.classList.add('active');

                    // Mostrar alerta de éxito
                    alertBox.className = 'alert success';
                    alertBox.textContent = '✅ Calificaciones obtenidas exitosamente';
                } else {
                    alertBox.className = 'alert error';
                    alertBox.textContent = `❌ ${data.message || 'Error desconocido'}`;
                }
            } catch (error) {
                alertBox.className = 'alert error';
                alertBox.textContent = `❌ Error de conexión: ${error.message}`;
                console.error('Error:', error);
            } finally {
                loading.classList.remove('active');
                btnSubmit.disabled = false;
            }
        });
    </script>

    <footer>
        <div class="container">
            <p>&copy; 2025 Gestión de Bienestar Estudiantil. Todos los derechos reservados.</p>
        </div>
    </footer>
</body>
</html>
