<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>San Antonio - Consíguelo Rapidito</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>

    <div class="hero-app-container">
        <!-- El fondo limpio que acabamos de generar -->
        <div class="hero-background-static"></div>

        <div class="objects-layer">
            <img src="./keys_tag1.png" alt="Llaves" class="floating-item keys" style="--delay: 0s;">
            <img src="./luggage_tag1.png" alt="Celular" class="floating-item phone" style="--delay: 1.5s;">
            <img src="./bag_tag1.png" alt="Cartera" class="floating-item wallet" style="--delay: 0.7s;">
            <img src="./tag_pic1.png" alt="Lupa" class="floating-item glass" style="--delay: 2.2s;">
        </div>

        <a href="login.php" class="btn-admin">🔐 Admin</a>
        <a href="ecommerce.php" class="btn-rastreo">📍 Rastreo</a>

        <!-- Contenido de Texto -->
        <div class="hero-text">
            <div class="h2-container">
                <h2 class="h2-cycle-1">TECNOLOGIA Y FE</h2>
                <h2 class="h2-cycle-2">REGRESAN LO TUYO</h2>
            </div>
            <div style="display: flex; justify-content: center; margin-top: 20px;">
                <a href="#formulario" class="btn-primary">Lo Encontré</a>
            </div>

            <!-- Icono Corazón Centrado con interactividad -->
            <img src="https://img.icons8.com/3d-fluency/250/like--v1.png" alt="Corazón" class="floating-item heart" style="--delay: 1.2s;">
        </div>
    </div>
    <main class="container">
        <section class="galeria">
            <h2>Objetos Encontrados</h2>
            <!-- Buscador -->
            <div class="buscador">
                <form method="GET" action="">
                    <input type="text" id="buscar" placeholder="Buscar por nombre, ubicación o descripción..." autocomplete="off">
                    <span id="resultado-contador"></span>
                </form>
            </div>

            <div class="grid" id="grid-objetos">
                <?php
                require_once 'db.php';
                $res = $conn->query("SELECT * FROM objetos WHERE estado = 'publicado' ORDER BY id DESC");
                while($row = $res->fetch_assoc()):
                    // Formateamos el mensaje para WhatsApp
                    $mensajeWA = urlencode("Hola, reconozco mi objeto: " . $row['nombre'] . " (ID: " . $row['id'] . ") en la web. ¿Cómo puedo recuperarlo?");
                ?>
                    <div class="card" style="align-items:center;">
                        <img src="uploads/<?php echo $row['imagen']; ?>"
                             alt="Objeto"
                             style="width: 200px; height: 200px; display: block; margin: 0 auto; object-fit: cover; border-radius: 10px; background: #f9f9f9; padding: 10px;">
                        <h3><?php echo $row['nombre']; ?></h3>
                        <p>📍 <?php echo $row['ubicacion']; ?></p>
                        <p><?php echo $row['descripcion']; ?></p>

                        <div class="contacto-info">
                            <p class="tag-pago">PARA RECUPERARLO CONTÁCTAME:</p>
                            <!-- Enlace a WhatsApp -->
                            <a href="https://wa.me/584143332662?text=<?php echo $mensajeWA; ?>" class="btn-wa" target="_blank">
                                WHATSAPP: +58 (414) 333-2662
                            </a>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </section>

        <section id="formulario" class="form-section">
            <h2>Reportar un Hallazgo</h2>
            <form action="subir.php" method="POST" enctype="multipart/form-data">
                <input type="text" name="nombre" placeholder="¿Qué objeto es?" required>
                <input type="text" name="ubicacion" placeholder="¿Dónde lo encontraste?" required>
                <textarea name="descripcion" placeholder="Detalles del objeto..." required></textarea>
                <input type="text" name="contacto" placeholder="Tu número (para uso interno)" required>
                <label>Foto del objeto:</label>
                <input type="file" name="foto" accept="image/*" required>
                
                <div class="terminos">
                    <label>
                        <input type="radio" name="acepta_terminos" id="acepta_terminos" required>
                        Acepto los <a href="terminos_y_condiciones.html" target="_blank">Términos y Condiciones</a>
                    </label>
                </div>
                
                <button type="submit" class="btn" id="btn-publicar" disabled>Publicar en San Antonio</button>
            </form>
        </section>
    </main>

    <script>
        const aceptaTerminos = document.getElementById('acepta_terminos');
        const btnPublicar = document.getElementById('btn-publicar');

        aceptaTerminos.addEventListener('change', function() {
            btnPublicar.disabled = !this.checked;
        });

        // Búsqueda dinámica en tiempo real
        const buscarInput = document.getElementById('buscar');
        const gridObjetos = document.getElementById('grid-objetos');
        const resultadoContador = document.getElementById('resultado-contador');
        let timeoutId = null;

        buscarInput.addEventListener('input', function() {
            const busqueda = this.value.trim();

            // Limpiar timeout anterior
            if (timeoutId) {
                clearTimeout(timeoutId);
            }

            // Esperar 300ms después de dejar de escribir
            timeoutId = setTimeout(function() {
                if (busqueda === '') {
                    // Si está vacío, recargar todos los objetos
                    cargarObjetos('');
                } else {
                    // Buscar en tiempo real
                    cargarObjetos(busqueda);
                }
            }, 300);
        });

        function cargarObjetos(busqueda) {
            fetch('buscar.php?q=' + encodeURIComponent(busqueda))
                .then(response => response.json())
                .then(data => {
                    let html = '';
                    
                    if (data.objetos.length === 0) {
                        gridObjetos.innerHTML = '<p style="grid-column: 1/-1; text-align: center; padding: 40px; color: #666;">No se encontraron objetos que coincidan con "' + busqueda + '"</p>';
                        resultadoContador.textContent = '';
                        return;
                    }

                    data.objetos.forEach(obj => {
                        const mensajeWA = encodeURIComponent('Hola, reconozco mi objeto: ' + obj.nombre + ' (ID: ' + obj.id + ') en la web. ¿Cómo puedo recuperarlo?');
                        
                        html += `
                            <div class="card" style="align-items:center;">
                                <img src="uploads/${obj.imagen}"
                                     alt="Objeto"
                                     style="width: 200px; height: 200px; display: block; margin: 0 auto; object-fit: cover; border-radius: 10px; background: #f9f9f9; padding: 10px;">
                                <h3>${obj.nombre}</h3>
                                <p>📍 ${obj.ubicacion}</p>
                                <p>${obj.descripcion}</p>
                                <div class="contacto-info">
                                    <p class="tag-pago">PARA RECUPERARLO CONTÁCTAME:</p>
                                    <a href="https://wa.me/584143332662?text=${mensajeWA}" class="btn-wa" target="_blank">
                                        WHATSAPP: +58 (414) 333-2662
                                    </a>
                                </div>
                            </div>
                        `;
                    });

                    gridObjetos.innerHTML = html;
                    resultadoContador.textContent = data.total + ' objeto(s) encontrado(s)';
                })
                .catch(error => {
                    console.error('Error:', error);
                    gridObjetos.innerHTML = '<p style="grid-column: 1/-1; text-align: center; color: #d9534f;">Error al cargar los objetos</p>';
                });
        }
    </script>

    <!-- Script de interactividad con el ratón para objetos -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const items = document.querySelectorAll('.floating-item');
            
            document.addEventListener('mousemove', (e) => {
                const moveX = (e.clientX - window.innerWidth / 2) / 40;
                const moveY = (e.clientY - window.innerHeight / 2) / 40;

                // Aplicar movimiento adicional al ratón usando margenes para evitar que
                // override la propiedad transform del Keyframe "floatRandom" ni las coordenadas iniciales Left/Top
                items.forEach(item => {
                    item.style.marginLeft = `${moveX}px`;
                    item.style.marginTop = `${moveY}px`;
                });
            });
        });
    </script>
</body>
</html>
