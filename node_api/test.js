#!/usr/bin/env node

const http = require('http');

const usuario = process.argv[2];
const contrasena = process.argv[3];

if (!usuario || !contrasena) {
    console.log('\nUso: node test.js <usuario> <contraseña>\n');
    console.log('Ejemplo: node test.js 202110257 micontraseña\n');
    process.exit(1);
}

console.log('\n🌐 Probando scraping...\n');

const data = JSON.stringify({
    usuario,
    contrasena,
    userId: 1
});

const options = {
    hostname: 'localhost',
    port: 3000,
    path: '/api/scraping/notas',
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'Content-Length': data.length
    }
};

const req = http.request(options, (res) => {
    let body = '';
    res.on('data', chunk => body += chunk);
    res.on('end', () => {
        const result = JSON.parse(body);
        
        if (result.success) {
            console.log('✅ Scraping exitoso\n');
            console.log(`Cursos encontrados: ${result.cantidad_cursos}\n`);
            
            result.data.cursos.forEach((curso, i) => {
                console.log(`${i+1}. ${curso.nombre}`);
                console.log(`   Código: ${curso.codigo} | Créditos: ${curso.creditos} | Tipo: ${curso.tipo}`);
                console.log(`   Docente: ${curso.docente}`);
                
                if (Object.keys(curso.calificaciones).length > 0) {
                    console.log(`   Notas: ${JSON.stringify(curso.calificaciones)}`);
                }
                console.log('');
            });
        } else {
            console.log('❌ Error:', result.error);
            console.log('Mensaje:', result.message);
        }
    });
});

req.on('error', (err) => {
    console.log('❌ Error de conexión:', err.message);
    console.log('¿Está ejecutándose el servidor? npm run dev\n');
});

req.write(data);
req.end();
