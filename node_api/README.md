# API Node.js - Bienestar Estudiantil

API para scraping de notas del intranet UCSS usando Puppeteer.

## Instalación

```bash
npm install
```

## Iniciar

```bash
npm run dev    # desarrollo
npm start      # producción
```

Servidor en `http://localhost:3000`

## Rutas

- `GET /api/hello` - Test
- `GET /api/status` - Estado
- `POST /api/scraping/notas` - Scraping de notas

## POST /api/scraping/notas

**Request:**
```json
{
  "usuario": "202110257",
  "contrasena": "tu_contraseña",
  "userId": 1
}
```

**Response:**
```json
{
  "success": true,
  "message": "Scraping completado exitosamente",
  "cantidad_cursos": 5,
  "data": {
    "usuario": "202110257",
    "fecha_descarga": "2025-11-12T15:30:45.123Z",
    "cursos": [
      {
        "nombre": "NOMBRE CURSO",
        "codigo": "123456",
        "docente": "DOCENTE",
        "calificaciones": {
          "EC1": 14,
          "EC2": 15,
          "EF": 17
        }
      }
    ]
  }
}
```

## Estructura

```
node_api/
├── server.js
├── config.js
├── package.json
├── .env
├── controllers/
│   └── ScrapingController.js
└── routes/
    └── scraping.js
```

## Dependencias

- express ^5.1.0
- puppeteer ^24.29.1
- cors ^2.8.5
- dotenv ^16.3.1

