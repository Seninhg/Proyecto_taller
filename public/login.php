<?php
/**
 * Página de Login
 */

// Procesar el formulario si se envía
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener datos del formulario
    $student_code = trim($_POST['student_code'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validaciones
    if (empty($student_code) || empty($password)) {
        $error = 'El código de estudiante y contraseña son obligatorios.';
    } else {
        // Archivo de usuarios
        $users_file = '../data/users.json';
        
        if (file_exists($users_file)) {
            // Leer usuarios
            $json_content = file_get_contents($users_file);
            $users = json_decode($json_content, true) ?? [];
            
            // Buscar al usuario
            $user_found = false;
            foreach ($users as $user) {
                if ($user['student_code'] === $student_code) {
                    // Verificar contraseña
                    if (password_verify($password, $user['password'])) {
                        // Login exitoso
                        session_start();
                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['student_code'] = $user['student_code'];
                        $_SESSION['full_name'] = $user['full_name'];
                        $_SESSION['email'] = $user['email'];
                        
                        $success = '¡Login exitoso! Redirigiendo al dashboard...';
                        header('Refresh: 2; url=dashboard.php');
                    } else {
                        $error = 'Contraseña incorrecta.';
                    }
                    $user_found = true;
                    break;
                }
            }
            
            if (!$user_found) {
                $error = 'El código de estudiante no existe.';
            }
        } else {
            $error = 'Error al acceder a la base de datos.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Gestión de Bienestar Estudiantil</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/login.css">
</head>
<body class="login-page">
    <div class="login-container">
        <div class="login-box">
            <div class="login-header">
                <h1>Gestión de Bienestar Estudiantil</h1>
                <p>Acceso al Sistema</p>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-error">
                    <strong>Error:</strong> <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success">
                    <strong>¡Éxito!</strong> <?php echo htmlspecialchars($success); ?>
                </div>
            <?php else: ?>

            <form action="login.php" method="POST" class="login-form" id="loginForm">
                <!-- Campo de Código de Estudiante -->
                <div class="form-group">
                    <label for="student_code">Código de Estudiante</label>
                    <div class="input-wrapper">
                        <span class="input-icon">👤</span>
                        <input 
                            type="text" 
                            id="student_code" 
                            name="student_code" 
                            placeholder="Ingresa tu código de estudiante"
                            required
                            class="form-control"
                            value="<?php echo htmlspecialchars($student_code ?? ''); ?>"
                        >
                    </div>
                </div>

                <!-- Campo de Contraseña -->
                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <div class="input-wrapper">
                        <span class="input-icon">🔒</span>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            placeholder="Ingresa tu contraseña"
                            required
                            class="form-control"
                        >
                        <button type="button" class="toggle-password" onclick="togglePassword()">👁️</button>
                    </div>
                </div>

                <!-- Botón de Login -->
                <button type="submit" class="btn btn-primary btn-login">
                    Iniciar Sesión
                </button>
            </form>

            <?php endif; ?>

            <!-- Enlace de Registro -->
            <div class="login-footer">
                <p>¿No tienes cuenta? 
                    <a href="register.php" class="btn-register">
                        Regístrate aquí
                    </a>
                </p>
            </div>
        </div>

        <!-- Sección Informativa -->
        <div class="login-info">
            <div class="info-card">
                <h3>Bienvenido</h3>
                <p>Sistema de Gestión de Bienestar Estudiantil</p>
                <ul class="info-list">
                    <li>✓ Acceso seguro a tu perfil</li>
                    <li>✓ Información académica</li>
                    <li>✓ Servicios de bienestar</li>
                    <li>✓ Comunicación con tutores</li>
                </ul>
            </div>
        </div>
    </div>

    <script src="js/main.js"></script>
    <script src="js/login.js"></script>
</body>
</html>
