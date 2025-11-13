# Gestión de Bienestar Estudiantil

## Descripción
Sistema web profesional para gestionar el bienestar y desarrollo integral de los estudiantes.

## Requisitos previos
- XAMPP instalado en tu computadora
- PHP 7.4 o superior
- MySQL/MariaDB

## Instrucciones para ejecutar

1. **Inicia XAMPP:**
   - Abre el Panel de Control de XAMPP
   - Inicia Apache y MySQL

2. **Accede a la aplicación:**
   - Abre tu navegador
   - Navega a: `http://localhost/bienestar_web`

## Estructura del proyecto

```
bienestar_web/
├── public/                   # Carpeta pública
│   ├── index.php            # Punto de entrada
│   ├── css/                 # Estilos CSS
│   ├── js/                  # Scripts JavaScript
│   └── img/                 # Imágenes
├── src/                     # Código fuente
│   ├── config/              # Configuración
│   │   └── config.php       # Archivo de configuración
│   ├── controllers/         # Controladores (MVC)
│   ├── models/              # Modelos (MVC)
│   ├── views/               # Vistas (MVC)
│   ├── utils/               # Utilidades
│   └── router.php           # Enrutador
├── database/                # Scripts SQL
├── .htaccess                # Configuración de reescritura
└── README.md                # Este archivo
```

## Características
- ✅ Estructura MVC profesional
- ✅ Base de datos MySQL
- ✅ Sistema de enrutamiento
- ✅ Interfaz responsiva con CSS moderno
- ✅ JavaScript para interactividad
- ✅ Configuración centralizada

## Solución de problemas

**Página en blanco:**
- Verifica que Apache esté corriendo
- Revisa los logs de PHP en XAMPP
- Asegúrate de que la carpeta esté en `C:\xampp\htdocs\bienestar_web`

**Errores de conexión a BD:**
- Verifica que MySQL esté corriendo
- Comprueba las credenciales en `src/config/config.php`
- Crea la base de datos `bienestar_estudiantes`

## Próximos pasos
- Crear tablas en la base de datos
- Desarrollar controladores
- Implementar funcionalidades de CRUD
- Agregar autenticación de usuarios
