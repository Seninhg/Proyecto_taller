<?php
/**
 * Página de Información de Estudiante
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
    <title>Información de Estudiante - Gestión de Bienestar Estudiantil</title>
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

        .info-section {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin-top: 1.5rem;
        }

        .info-item {
            padding: 1.5rem;
            background: #f9f9f9;
            border-left: 4px solid #3498db;
            border-radius: 4px;
        }

        .info-item label {
            display: block;
            color: #7f8c8d;
            font-size: 0.85rem;
            text-transform: uppercase;
            margin-bottom: 0.5rem;
            letter-spacing: 1px;
        }

        .info-item p {
            margin: 0;
            font-size: 1.1rem;
            font-weight: 500;
            color: #2c3e50;
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
                    <li><a href="student-info.php" class="nav-link active">Información de Estudiante</a></li>
                    <li><a href="scraping.php" class="nav-link">Obtener Información</a></li>
                    <li class="menu-separator"></li>
                    <li><a href="student-info.php?logout=1" class="logout-btn">Cerrar Sesión</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <main>
        <div class="container">
            <div class="page-header">
                <h2>Información de tu Perfil</h2>
                <p>Visualiza los detalles completos de tu cuenta</p>
            </div>

            <div class="info-section">
                <h3>Datos Personales</h3>
                <div class="info-grid">
                    <div class="info-item">
                        <label>Código de Estudiante</label>
                        <p><?php echo htmlspecialchars($_SESSION['student_code']); ?></p>
                    </div>
                    <div class="info-item">
                        <label>Nombre Completo</label>
                        <p><?php echo htmlspecialchars($_SESSION['full_name']); ?></p>
                    </div>
                    <div class="info-item">
                        <label>Correo Electrónico</label>
                        <p><?php echo htmlspecialchars($_SESSION['email']); ?></p>
                    </div>
                    <div class="info-item">
                        <label>ID de Usuario</label>
                        <p>#<?php echo htmlspecialchars($_SESSION['user_id']); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2025 Gestión de Bienestar Estudiantil. Todos los derechos reservados.</p>
        </div>
    </footer>
</body>
</html>
