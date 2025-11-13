const express = require('express');
const router = express.Router();
const ScrapingController = require('../controllers/ScrapingController');

// Rutas de scraping
router.post('/notas', ScrapingController.obtenerNotas);

module.exports = router;
