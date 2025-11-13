<?php
/**
 * Dashboard - Página protegida
 */

session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header('Location: public/login.php');
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
    <title>Dashboard - Gestión de Bienestar Estudiantil</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        .dashboard-header {
            background: linear-gradient(135deg, #3498db 0%, #2ecc71 100%);
            color: white;
            padding: 2rem;
            border-radius: 8px;
            margin-bottom: 2rem;
        }

        .dashboard-header h2 {
            margin: 0;
            font-size: 1.8rem;
        }

        .user-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .info-card {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .info-card h3 {
            margin-top: 0;
            color: #3498db;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .info-card p {
            margin: 0;
            font-size: 1.1rem;
            font-weight: 500;
            color: #333;
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
                    <li><a href="dashboard.php" class="nav-link active">Dashboard</a></li>
                    <li><a href="student-info.php" class="nav-link">Información de Estudiante</a></li>
                    <li><a href="scraping.php" class="nav-link">Obtener Información</a></li>
                    <li class="menu-separator"></li>
                    <li><a href="dashboard.php?logout=1" class="logout-btn">Cerrar Sesión</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <main>
        <div class="container">
            <div class="dashboard-header">
                <h2>¡Bienvenido, <?php echo htmlspecialchars($_SESSION['full_name']); ?>!</h2>
                <p>Has iniciado sesión correctamente en el sistema de Gestión de Bienestar Estudiantil</p>
            </div>

            <h3>Información de tu Cuenta</h3>
            <div class="user-info">
                <div class="info-card">
                    <h3>Código de Estudiante</h3>
                    <p><?php echo htmlspecialchars($_SESSION['student_code']); ?></p>
                </div>
                <div class="info-card">
                    <h3>Nombre Completo</h3>
                    <p><?php echo htmlspecialchars($_SESSION['full_name']); ?></p>
                </div>
                <div class="info-card">
                    <h3>Correo Electrónico</h3>
                    <p><?php echo htmlspecialchars($_SESSION['email']); ?></p>
                </div>
                <div class="info-card">
                    <h3>ID de Usuario</h3>
                    <p>#<?php echo htmlspecialchars($_SESSION['user_id']); ?></p>
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
