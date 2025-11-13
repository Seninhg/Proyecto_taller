const puppeteer = require('puppeteer');
const fs = require('fs');
const path = require('path');

class ScrapingController {
    static async obtenerNotas(req, res) {
        const { usuario, contrasena, userId } = req.body;

        if (!usuario || !contrasena) {
            return res.status(400).json({
                error: 'Usuario y contraseña son requeridos'
            });
        }

        let browser = null;

        try {
            console.log('\n========== INICIANDO LOGIN ==========');
            console.log(`👤 Usuario: ${usuario}`);

            browser = await puppeteer.launch({
                headless: true,
                args: [
                    '--no-sandbox',
                    '--disable-setuid-sandbox',
                    '--disable-dev-shm-usage'
                ]
            });

            const page = await browser.newPage();

            await page.setUserAgent(
                'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36'
            );

            const loginUrl = 'https://intranet.ucss.edu.pe/ucss-intranet/login/ingresar.aspx';

            console.log('📍 Navegando a login...');
            await page.goto(loginUrl, {
                waitUntil: 'networkidle2',
                timeout: 30000
            });

            console.log('🔑 Ingresando credenciales...');

            // Campo Usuario: id="txtUsuarioMail"
            await page.type('#txtUsuarioMail', usuario, { delay: 100 });
            console.log('✓ Usuario ingresado');

            // Campo Contraseña: id="txtPwd"
            await page.type('#txtPwd', contrasena, { delay: 100 });
            console.log('✓ Contraseña ingresada');

            // Click en botón: id="btnIngresar"
            console.log('⏳ Enviando formulario...');
            
            // Iniciar escucha de navegación ANTES de hacer click
            const navigationPromise = page.waitForNavigation({
                waitUntil: 'networkidle0',
                timeout: 25000
            }).catch(e => {
                console.log('⚠️ Timeout esperando navegación:', e.message);
                return null;
            });
            
            // Hacer click
            await page.click('#btnIngresar');
            
            // Esperar a que se complete
            await navigationPromise;
            
            console.log('✅ Formulario enviado');
            
            // Esperar extra para asegurar carga completa
            await new Promise(resolve => setTimeout(resolve, 2000));
            
            // Verificar URL actual
            const urlAfterLogin = page.url();
            console.log(`📍 URL después de login: ${urlAfterLogin}`);

            // PASO 3: Navegar a página de notas
            console.log('📊 Navegando a página de notas...');
            
            // Si la URL actual ya no es login, vamos directamente a notas
            if (!urlAfterLogin.includes('ingresar.aspx')) {
                console.log('✓ Login verificado');
                
                const notasUrl = 'https://intranet.ucss.edu.pe/ucss-intranet/academico/notas.aspx';
                
                try {
                    const navPromise = page.waitForNavigation({
                        waitUntil: 'networkidle0',
                        timeout: 20000
                    }).catch(() => null);
                    
                    await page.goto(notasUrl, { referer: urlAfterLogin });
                    await navPromise;
                    
                } catch (e) {
                    console.log('⚠️ Error navegando a notas, intentando ruta alternativa...');
                    await page.goto('https://intranet.ucss.edu.pe/ucss-intranet/estudiante/calificaciones.aspx', 
                        { waitUntil: 'networkidle0', timeout: 20000 }).catch(() => null);
                }
            } else {
                console.log('❌ Login falló, aún en ingresar.aspx');
                throw new Error('Login incorrecto - credenciales inválidas o el formulario cambió');
            }

            console.log('⏳ Esperando carga de notas...');
            await new Promise(resolve => setTimeout(resolve, 3000));

            // DEBUG: Guardar HTML en archivo y verificar URL
            const currentUrl = page.url();
            const pageContent = await page.content();
            fs.writeFileSync('debug.html', pageContent, 'utf-8');
            const hasCards = pageContent.includes('css-curso-card');
            console.log(`📍 URL actual: ${currentUrl}`);
            console.log(`✓ HTML guardado (${hasCards ? 'CON tarjetas' : 'SIN tarjetas'})`);

            // PASO 4: Extraer datos de notas
            console.log('📝 Extrayendo datos...');
            const cursos = await page.evaluate(() => {
                const listaCursos = [];
                
                // Seleccionar todas las tarjetas de cursos
                const tarjetas = document.querySelectorAll('div.css-curso-card');
                console.log(`Tarjetas encontradas: ${tarjetas.length}`);
                
                tarjetas.forEach((tarjeta, idx) => {
                    try {
                        const curso = {
                            nombre: '',
                            codigo: '',
                            creditos: '',
                            tipo: '',
                            docente: '',
                            calificaciones: {}
                        };

                        // Extraer nombre
                        const nombreEl = tarjeta.querySelector('.css-curso-card-body > p > b');
                        if (nombreEl) {
                            curso.nombre = nombreEl.textContent.trim();
                        }

                        // Extraer código
                        const items = tarjeta.querySelectorAll('.css-curso-card-body-cred-tipo-item');
                        items.forEach(item => {
                            const texto = item.textContent;
                            if (texto.includes('CÓD')) {
                                const match = texto.match(/CÓD\.:\s*(\d+)/);
                                if (match) curso.codigo = match[1];
                            } else if (texto.includes('CRÉD')) {
                                const match = texto.match(/CRÉD\.:\s*(\d+)/);
                                if (match) curso.creditos = match[1];
                            } else if (texto.includes('TIPO')) {
                                const match = texto.match(/TIPO:\s*(\w+)/);
                                if (match) curso.tipo = match[1];
                            }
                        });

                        // Extraer docente
                        const docenteTexto = tarjeta.querySelector('.css-curso-card-body > p:last-of-type');
                        if (docenteTexto) {
                            const match = docenteTexto.textContent.match(/DOCENTE:\s*(.+)/);
                            if (match) curso.docente = match[1].trim();
                        }

                        // Extraer calificaciones de tabla principal (E1, E2, E3, EF, PF)
                        const tablaPrincipal = tarjeta.querySelector('.css-curso-card-tabla-notas:not(.css-curso-card-tabla-notas-ec)');
                        if (tablaPrincipal) {
                            const encabezados = Array.from(tablaPrincipal.querySelectorAll('tr:first-child th')).map(th => 
                                th.textContent.match(/(\w+)/)[1]
                            );
                            const valores = Array.from(tablaPrincipal.querySelectorAll('tr:last-child td')).map(td => {
                                const texto = td.textContent.trim();
                                const num = parseFloat(texto);
                                return isNaN(num) || texto === '' ? null : num;
                            });

                            encabezados.forEach((enc, i) => {
                                if (valores[i] !== null) {
                                    curso.calificaciones[enc] = valores[i];
                                }
                            });
                        }

                        // Extraer calificaciones EC
                        const tablaEC = tarjeta.querySelector('.css-curso-card-tabla-notas-ec');
                        if (tablaEC) {
                            const encabezados = Array.from(tablaEC.querySelectorAll('tr:first-child th')).map(th => th.textContent.trim());
                            const valores = Array.from(tablaEC.querySelectorAll('tr:last-child td')).map(td => {
                                const texto = td.textContent.trim();
                                const num = parseFloat(texto);
                                return isNaN(num) || texto === '' ? null : num;
                            });

                            encabezados.forEach((enc, i) => {
                                if (valores[i] !== null && !enc.includes('d-none')) {
                                    curso.calificaciones[enc] = valores[i];
                                }
                            });
                        }

                        listaCursos.push(curso);
                    } catch (e) {
                        console.error(`Error extrayendo curso ${idx}:`, e.message);
                    }
                });

                return listaCursos;
            });

            console.log(`✓ Se encontraron ${cursos.length} cursos\n`);

            // Guardar datos en JSON
            const dataDir = path.join(__dirname, '../../../data');
            if (!fs.existsSync(dataDir)) {
                fs.mkdirSync(dataDir, { recursive: true });
            }

            const notasData = {
                user_id: userId || usuario,
                fecha_descarga: new Date().toISOString(),
                cursos: cursos
            };

            const notasFile = path.join(dataDir, `notas_${userId || usuario}.json`);
            fs.writeFileSync(notasFile, JSON.stringify(notasData, null, 2), 'utf-8');
            console.log(`💾 Datos guardados en: ${notasFile}`);

            res.json({
                success: true,
                message: 'Scraping completado exitosamente',
                cantidad_cursos: cursos.length,
                data: {
                    usuario: usuario,
                    fecha_descarga: new Date().toISOString(),
                    cursos: cursos
                }
            });

        } catch (error) {
            console.error('\n❌ Error:', error.message);
            
            res.status(500).json({
                error: 'Error durante el scraping',
                message: error.message
            });

        } finally {
            if (browser) {
                await browser.close();
                console.log('🔒 Navegador cerrado\n');
            }
        }
    }
}

module.exports = ScrapingController;
