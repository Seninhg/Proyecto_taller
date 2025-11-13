/**
 * Script para la página de Login
 */

// Toggle para mostrar/ocultar contraseña
function togglePassword() {
    const passwordField = document.getElementById('password');
    const button = event.target;
    
    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        button.textContent = '🙈';
    } else {
        passwordField.type = 'password';
        button.textContent = '👁️';
    }
}

// Validación del formulario
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('.login-form');
    
    if (form) {
        form.addEventListener('submit', function(e) {
            const studentCode = document.getElementById('student_code').value.trim();
            const password = document.getElementById('password').value;
            
            // Validaciones básicas
            if (!studentCode) {
                e.preventDefault();
                alert('Por favor ingresa tu código de estudiante');
                return;
            }
            
            if (password.length < 6) {
                e.preventDefault();
                alert('La contraseña debe tener al menos 6 caracteres');
                return;
            }
        });
    }
    
    // Animación de enfoque
    const inputs = document.querySelectorAll('.form-control');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.style.borderColor = 'var(--primary-color)';
        });
        
        input.addEventListener('blur', function() {
            this.parentElement.style.borderColor = '#e0e0e0';
        });
    });
});
