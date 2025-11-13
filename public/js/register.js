/**
 * Script para la página de Registro
 */

// Toggle para mostrar/ocultar contraseña
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const button = event.target;
    
    if (field.type === 'password') {
        field.type = 'text';
        button.textContent = '🙈';
    } else {
        field.type = 'password';
        button.textContent = '👁️';
    }
}

// Validar fortaleza de contraseña
function validatePassword() {
    const password = document.getElementById('password').value;
    const strengthBar = document.getElementById('strengthBar');
    let strength = 0;
    
    // Validar longitud
    if (password.length >= 8) strength += 25;
    if (password.length >= 12) strength += 10;
    
    // Validar mayúsculas
    if (/[A-Z]/.test(password)) strength += 20;
    
    // Validar números
    if (/[0-9]/.test(password)) strength += 20;
    
    // Validar caracteres especiales
    if (/[!@#$%^&*]/.test(password)) strength += 25;
    
    // Limitar a 100
    strength = Math.min(strength, 100);
    
    strengthBar.style.width = strength + '%';
    
    // Cambiar color según fortaleza
    if (strength < 40) {
        strengthBar.style.background = '#e74c3c';
    } else if (strength < 70) {
        strengthBar.style.background = '#f39c12';
    } else {
        strengthBar.style.background = '#2ecc71';
    }
}

// Validación del formulario
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('.register-form');
    
    if (form) {
        form.addEventListener('submit', function(e) {
            const studentCode = document.getElementById('student_code').value.trim();
            const fullName = document.getElementById('full_name').value.trim();
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const terms = document.getElementById('terms').checked;
            
            // Validaciones
            if (!studentCode) {
                e.preventDefault();
                alert('Por favor ingresa tu código de estudiante');
                return;
            }
            
            if (!fullName) {
                e.preventDefault();
                alert('Por favor ingresa tu nombre completo');
                return;
            }
            
            if (!email) {
                e.preventDefault();
                alert('Por favor ingresa tu correo electrónico');
                return;
            }
            
            // Validar email
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                e.preventDefault();
                alert('Por favor ingresa un correo válido');
                return;
            }
            
            if (password.length < 8) {
                e.preventDefault();
                alert('La contraseña debe tener al menos 8 caracteres');
                return;
            }
            
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Las contraseñas no coinciden');
                return;
            }
            
            if (!terms) {
                e.preventDefault();
                alert('Debes aceptar los términos y condiciones');
                return;
            }
        });
    }
    
    // Animaciones de enfoque
    const inputs = document.querySelectorAll('.form-control');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.style.borderColor = '#667eea';
        });
        
        input.addEventListener('blur', function() {
            this.style.borderColor = '#e0e0e0';
        });
    });
    
    // Inicializar validación de contraseña
    const passwordInput = document.getElementById('password');
    if (passwordInput) {
        passwordInput.addEventListener('input', validatePassword);
    }
});
