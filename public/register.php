<?php
/**
 * Página de Registro
 */

// Procesar el formulario si se envía
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener datos del formulario
    $student_code = trim($_POST['student_code'] ?? '');
    $full_name = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validaciones
    if (empty($student_code) || empty($full_name) || empty($email) || empty($password)) {
        $error = 'Todos los campos son obligatorios.';
    } elseif (strlen($password) < 8) {
        $error = 'La contraseña debe tener al menos 8 caracteres.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'El correo electrónico no es válido.';
    } else {
        // Archivo de usuarios
        $users_file = '../data/users.json';
        
        // Leer usuarios existentes
        $users = [];
        if (file_exists($users_file)) {
            $json_content = file_get_contents($users_file);
            $users = json_decode($json_content, true) ?? [];
        }
        
        // Verificar si el usuario ya existe
        $user_exists = false;
        foreach ($users as $user) {
            if ($user['student_code'] === $student_code || $user['email'] === $email) {
                $user_exists = true;
                break;
            }
        }
        
        if ($user_exists) {
            $error = 'El código de estudiante o correo ya están registrados.';
        } else {
            // Crear nuevo usuario
            $new_user = [
                'id' => count($users) + 1,
                'student_code' => $student_code,
                'full_name' => $full_name,
                'email' => $email,
                'password' => password_hash($password, PASSWORD_BCRYPT),
                'created_at' => date('Y-m-d H:i:s'),
                'status' => 'active'
            ];
            
            // Agregar al array
            $users[] = $new_user;
            
            // Guardar en JSON
            if (file_put_contents($users_file, json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
                $success = '¡Registro exitoso! Redirigiendo al login...';
                // Redirigir después de 2 segundos
                header('Refresh: 2; url=login.php');
            } else {
                $error = 'Error al guardar el registro. Intenta de nuevo.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Gestión de Bienestar Estudiantil</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/register.css">
</head>
<body class="register-page">
    <div class="register-container">
        <div class="register-box">
            <div class="register-header">
                <h1>Gestión de Bienestar Estudiantil</h1>
                <p>Crear Nueva Cuenta</p>
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

            <form action="register.php" method="POST" class="register-form" id="registerForm">
                <div class="form-row">
                    <!-- Código de Estudiante -->
                    <div class="form-group">
                        <label for="student_code">Código de Estudiante *</label>
                        <input 
                            type="text" 
                            id="student_code" 
                            name="student_code" 
                            placeholder="Ej: 202110...."
                            required
                            class="form-control"
                            value="<?php echo htmlspecialchars($student_code ?? ''); ?>"
                        >
                    </div>

                    <!-- Nombre Completo -->
                    <div class="form-group">
                        <label for="full_name">Nombre Completo *</label>
                        <input 
                            type="text" 
                            id="full_name" 
                            name="full_name" 
                            placeholder="Tu nombre completo"
                            required
                            class="form-control"
                            value="<?php echo htmlspecialchars($full_name ?? ''); ?>"
                        >
                    </div>
                </div>

                <div class="form-row">
                    <!-- Email -->
                    <div class="form-group">
                        <label for="email">Correo Electrónico *</label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            placeholder="tu@email.com"
                            required
                            class="form-control"
                            value="<?php echo htmlspecialchars($email ?? ''); ?>"
                        >
                    </div>
                </div>

                <div class="form-row">
                    <!-- Contraseña -->
                    <div class="form-group">
                        <label for="password">Contraseña *</label>
                        <div class="input-wrapper">
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                placeholder="Mínimo 8 caracteres"
                                required
                                class="form-control"
                                onchange="validatePassword()"
                            >
                            <button type="button" class="toggle-password" onclick="togglePassword('password')">👁️</button>
                        </div>
                        <div class="password-strength">
                            <div class="strength-bar" id="strengthBar"></div>
                        </div>
                    </div>
                </div>

                <!-- Botones -->
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary btn-register">
                        Crear Cuenta
                    </button>
                </div>
            </form>

            <?php endif; ?>

            <!-- Enlace de Login -->
            <div class="register-footer">
                <p>¿Ya tienes cuenta? 
                    <a href="login.php" class="btn-login">
                        Inicia sesión aquí
                    </a>
                </p>
            </div>
        </div>
    </div>

    <script src="js/main.js"></script>
    <script src="js/register.js"></script>
</body>
</html>
