<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>San Antonio - Consíguelo Rapidito</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>

    <header class="hero">
        <div class="hero-content">
            <h1>San Antonio Bendito</h1>
            <p>"Consíguelo rapidito"</p>
            <div style="display: flex; gap: 15px; justify-content: center; align-items: center; margin-top: 20px;">
                <a href="#formulario" class="btn">Encontré algo</a>
                <a href="login.php" class="btn-admin">🔐 Admin</a>
            </div>
        </div>
    </header>

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
                $conn = new mysqli("localhost", "root", "", "san_antonio");
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

</body>
</html>
