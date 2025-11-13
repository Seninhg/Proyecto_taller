const express = require('express');
const cors = require('cors');
require('dotenv').config();

const app = express();
const PORT = process.env.PORT || 3000;

// Importar rutas
const scrapingRoutes = require('./routes/scraping');

// Middleware
app.use(cors());
app.use(express.json());

// Rutas de prueba
app.get('/api/hello', (req, res) => {
    res.json({
        message: 'Hola desde Node.js API',
        timestamp: new Date().toISOString()
    });
});

// Ruta para verificar que la API está funcionando
app.get('/api/status', (req, res) => {
    res.json({
        status: 'OK',
        server: 'Bienestar API',
        version: '1.0.0',
        timestamp: new Date().toISOString()
    });
});

// Usar rutas de scraping
app.use('/api/scraping', scrapingRoutes);

// Manejo de rutas no encontradas
app.use((req, res) => {
    res.status(404).json({
        error: 'Ruta no encontrada',
        path: req.path
    });
});

// Iniciar servidor
app.listen(PORT, () => {
    console.log(`\n========================================`);
    console.log(`🚀 Servidor ejecutándose en puerto ${PORT}`);
    console.log(`📍 URL: http://localhost:${PORT}`);
    console.log(`\nRutas disponibles:`);
    console.log(`  GET  /api/hello`);
    console.log(`  GET  /api/status`);
    console.log(`  POST /api/scraping/notas`);
    console.log(`========================================\n`);
});
