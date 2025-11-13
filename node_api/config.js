/**
 * Configuración de la aplicación
 */

module.exports = {
    // Configuración del puerto
    port: process.env.PORT || 3000,
    nodeEnv: process.env.NODE_ENV || 'development',

    // URLs del intranet
    intranet: {
        baseUrl: process.env.INTRANET_URL || 'https://intranet.ucss.edu.pe/ucss-intranet',
        loginUrl: process.env.LOGIN_URL || 'https://intranet.ucss.edu.pe/ucss-intranet/login/ingresar.aspx',
        notasUrl: process.env.NOTAS_URL || 'https://intranet.ucss.edu.pe/ucss-intranet/estudiante/calificaciones.aspx'
    },

    // Configuración de Puppeteer
    puppeteer: {
        headless: true,
        args: [
            '--no-sandbox',
            '--disable-setuid-sandbox',
            '--disable-dev-shm-usage',
            '--disable-gpu'
        ],
        timeout: 30000,
        waitUntil: 'networkidle2'
    },

    // User Agent realista
    userAgent: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',

    // Configuración de viewport
    viewport: {
        width: 1080,
        height: 1024
    },

    // Selectores comunes para buscar campos
    selectors: {
        usuario: [
            'input[id*="txtUsuario"]',
            'input[name*="usuario"]',
            'input[id*="Usuario"]',
            'input[type="text"]'
        ],
        contrasena: [
            'input[id*="txtContrasena"]',
            'input[name*="contrasena"]',
            'input[id*="Contrasena"]',
            'input[type="password"]'
        ],
        botonLogin: [
            'button[type="submit"]',
            'input[type="submit"]',
            'button[id*="btnIngresar"]',
            'input[id*="btnIngresar"]'
        ]
    },

    // Directorio de datos
    dataDir: '../../../data'
};
