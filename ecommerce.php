<?php
// ecommerce.php
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda de Rastreadores - San Antonio</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', 'Segoe UI', Arial, sans-serif; }
        
        :root {
            --primary: #00ffea;
            --bg-color: #0a0f16;
            --glass-bg: rgba(255, 255, 255, 0.05);
            --glass-border: rgba(255, 255, 255, 0.1);
        }

        body {
            background-color: var(--bg-color);
            color: #fff;
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Ambient Background Glow */
        body::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle at 50% 50%, rgba(0, 255, 234, 0.15), transparent 60%);
            z-index: -1;
            animation: pulse-glow 10s infinite alternate;
        }

        @keyframes pulse-glow {
            0% { transform: scale(1); opacity: 0.8; }
            100% { transform: scale(1.1); opacity: 1; }
        }

        nav {
            width: 100%;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--glass-border);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .btn-back {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: 0.3s;
        }

        .btn-back:hover {
            text-shadow: 0 0 10px var(--primary);
            transform: translateX(-5px);
        }

        .btn-cart {
            color: var(--primary);
            text-decoration: none;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 1.2rem;
            transition: 0.3s;
        }

        .btn-cart span {
            background: var(--primary);
            color: #000;
            border-radius: 50%;
            padding: 2px 8px;
            font-size: 0.9rem;
        }

        .btn-cart:hover {
            transform: scale(1.1);
            text-shadow: 0 0 10px var(--primary);
        }

        .header-title {
            text-align: center;
            margin: 60px 0 20px;
        }

        .header-title h1 {
            font-size: 3.5rem;
            background: linear-gradient(135deg, #fff, #00ffea);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            text-transform: uppercase;
            letter-spacing: 2px;
            text-shadow: 0 0 30px rgba(0, 255, 234, 0.3);
        }

        .header-title p {
            color: rgba(255,255,255,0.7);
            font-size: 1.2rem;
            margin-top: 10px;
        }

        .store-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
            padding: 40px;
            max-width: 1400px;
            width: 100%;
        }

        .product-card {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            padding: 30px;
            display: flex;
            flex-direction: column;
            align-items: center;
            backdrop-filter: blur(12px);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }

        .product-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.05), transparent);
            transition: all 0.6s;
        }

        .product-card:hover {
            transform: translateY(-15px);
            border-color: rgba(0, 255, 234, 0.4);
            box-shadow: 0 20px 40px rgba(0, 255, 234, 0.15);
        }

        .product-card:hover::before {
            left: 100%;
        }

        .product-image {
            width: 160px;
            height: 160px;
            object-fit: contain;
            margin-bottom: 25px;
            filter: drop-shadow(0 15px 15px rgba(0,0,0,0.5));
            transition: transform 0.3s;
        }

        .product-card:hover .product-image {
            transform: scale(1.1) rotate(5deg);
        }

        .image-container {
            position: relative;
            cursor: pointer;
        }

        .btn-expand {
            position: absolute;
            bottom: 15px;
            right: -10px;
            background: rgba(0, 255, 234, 0.2);
            border: 1px solid var(--primary);
            color: var(--primary);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            opacity: 0;
            transition: 0.3s;
            backdrop-filter: blur(5px);
            font-size: 1.2rem;
            z-index: 10;
        }

        .product-card:hover .btn-expand {
            opacity: 1;
            transform: scale(1.1);
        }

        .btn-expand:hover {
            background: var(--primary);
            color: #000;
        }

        /* Lightbox CSS */
        .lightbox {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.85);
            backdrop-filter: blur(10px);
            z-index: 9999;
            align-items: center;
            justify-content: center;
        }

        .lightbox.active {
            display: flex;
            animation: fadeIn 0.3s;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .lightbox-img {
            max-width: 90%;
            max-height: 90vh;
            object-fit: contain;
            filter: drop-shadow(0 0 30px rgba(0, 255, 234, 0.5));
            transform: scale(0.9);
            animation: zoomIn 0.3s forwards;
        }

        @keyframes zoomIn {
            to { transform: scale(1); }
        }

        .lightbox-close {
            position: absolute;
            top: 30px;
            right: 40px;
            font-size: 3rem;
            color: #fff;
            cursor: pointer;
            transition: 0.3s;
        }

        .lightbox-close:hover {
            color: var(--primary);
            transform: scale(1.1);
        }

        .product-info {
            text-align: center;
            width: 100%;
        }

        .product-title {
            font-size: 1.4rem;
            margin-bottom: 10px;
            color: #fff;
            font-weight: 600;
        }

        .product-desc {
            font-size: 0.9rem;
            color: #aaa;
            margin-bottom: 20px;
            line-height: 1.5;
        }

        .product-price {
            font-size: 1.8rem;
            font-weight: bold;
            color: var(--primary);
            margin-bottom: 20px;
        }

        .btn-buy {
            background: transparent;
            color: var(--primary);
            border: 2px solid var(--primary);
            padding: 12px 30px;
            border-radius: 50px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            width: 100%;
            transition: all 0.3s;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn-buy:hover {
            background: var(--primary);
            color: #000;
            box-shadow: 0 0 20px rgba(0, 255, 234, 0.5);
        }

        /* Notification Toast */
        .toast {
            position: fixed;
            bottom: -100px;
            left: 50%;
            transform: translateX(-50%);
            background: var(--primary);
            color: #000;
            padding: 15px 30px;
            border-radius: 50px;
            font-weight: bold;
            transition: 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            z-index: 1000;
            box-shadow: 0 5px 20px rgba(0,255,234,0.4);
        }

        .toast.show {
            bottom: 30px;
        }

        @media (max-width: 768px) {
            .header-title h1 { font-size: 2.5rem; }
            .store-grid { padding: 20px; }
        }
    </style>
</head>
<body>

    <nav>
        <a href="index.php" class="btn-back">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
            Volver
        </a>
        <div style="font-weight: bold; letter-spacing: 2px; color: var(--primary);">SAN ANTONIO STORE</div>
        <a href="carrito.php" class="btn-cart">
            🛒 <span id="cart-count">0</span>
        </a>
    </nav>

    <div class="header-title">
        <h1>Siempre Conectado</h1>
        <p>No vuelvas a perder lo que más te importa. Adquiere nuestros localizadores inteligentes.</p>
    </div>

    <div class="store-grid">
<?php
$products = [
    // Originales
    ['image' => './keys_tag1.png', 'title' => 'Tag para Llaves', 'desc' => 'Compacto, resistente y con alarma sonora. Encuentra tus llaves.', 'price' => '19.99'],
    ['image' => './luggage_tag1.png', 'title' => 'Tag Multipropósito', 'desc' => 'Ideal para mochilas, equipos electrónicos o artículos de viaje.', 'price' => '24.99'],
    ['image' => './bag_tag1.png', 'title' => 'Tag para Carteras', 'desc' => 'Diseño extra plano que se desliza perfectamente en la billetera.', 'price' => '22.99'],
    ['image' => './tag_pic1.png', 'title' => 'Micro Tag Pro', 'desc' => 'Nuestra versión más pequeña, GPS de precisión y larga duración.', 'price' => '29.99'],

    // People
    ['image' => './people_tags1.png', 'title' => 'Tag Personas Elite', 'desc' => 'Monitoreo discreto y efectivo para sentirse seguro siempre.', 'price' => '34.99'],
    ['image' => './people_tags2.png', 'title' => 'Tag Personas Plus', 'desc' => 'Con botón de pánico y batería recargable de larga duración.', 'price' => '39.99'],
    ['image' => './people_tags3.png', 'title' => 'Tag Personas Lite', 'desc' => 'Versión ligera y cómoda para llevar a diario sin molestias.', 'price' => '29.99'],
    ['image' => './people_tags4.png', 'title' => 'Tag Personas Pro', 'desc' => 'Ideal para personas con necesidades especiales, muy exacto.', 'price' => '44.99'],

    // Animals
    ['image' => './animal_tag1.png', 'title' => 'Pet Tag Basic', 'desc' => 'Ligero y resistente a salpicaduras, para perros y gatos.', 'price' => '18.99'],
    ['image' => './animal_tag2.png', 'title' => 'Pet Tag Pro', 'desc' => 'Rastreo en tiempo real para perros grandes y muy activos.', 'price' => '27.99'],
    ['image' => './animal_tag3.png', 'title' => 'Cat Track', 'desc' => 'Diseño especial para gatos. Ultra ligero, pequeño y discreto.', 'price' => '21.99'],
    ['image' => './animal_tag4.png', 'title' => 'Pet Explorer', 'desc' => 'Sumergible y de alta resistencia para las mascotas aventureras.', 'price' => '32.99'],

    // Kids
    ['image' => './kids_tag1.png', 'title' => 'Kids Tag Fun', 'desc' => 'Diseño resistente a golpes y rayones, ideal para el colegio.', 'price' => '25.99'],
    ['image' => './kids_tag2.png', 'title' => 'Kids Safe', 'desc' => 'Zona segura incluida: recibe notificaciones si salen del radio.', 'price' => '35.99'],
    ['image' => './kids_tag3.png', 'title' => 'Kids Bracelet', 'desc' => 'Estilo pulsera, difícil de quitar, cómodo para el niño.', 'price' => '28.99'],
    ['image' => './kids_tag4.png', 'title' => 'Kids Explorer', 'desc' => 'Rastreador súper resistente con botón de contacto rápido.', 'price' => '39.99'],

    // Vehicles
    ['image' => './vehicles_tag1.png', 'title' => 'Moto Track', 'desc' => 'Instalación oculta perfecta para motocicletas y bicicletas.', 'price' => '45.99'],
    ['image' => './vehicles_tag2.png', 'title' => 'Auto Tag Pro', 'desc' => 'Conexión OBD2 inteligente para rastreo constante de tu coche.', 'price' => '59.99'],
    ['image' => './vehicles_tag3.png', 'title' => 'Vehicle Stealth', 'desc' => 'Batería interna de 6 meses para vigilancia pasiva extrema.', 'price' => '49.99'],
    ['image' => './vehicles_tag4.png', 'title' => 'Fleet Tracker', 'desc' => 'Manejo de flotas. Reporte histórico y ubicaciones en vivo.', 'price' => '69.99'],

    // Wallet
    ['image' => './wallet_tag1.png', 'title' => 'Wallet Slim', 'desc' => 'El más delgado del mercado, como una tarjeta de crédito.', 'price' => '24.99'],
    ['image' => './wallet_tag2.png', 'title' => 'Wallet VIP', 'desc' => 'Terminación premium mate para billeteras altamente elegantes.', 'price' => '29.99'],
    ['image' => './wallet_tag3.png', 'title' => 'Card Note', 'desc' => 'Para libretas, bolsillos pequeños, tarjeteros o pasaportes.', 'price' => '19.99'],
    ['image' => './wallet_tag4.png', 'title' => 'Wallet Armor', 'desc' => 'Además de rastreo, trae bloqueo RFID anti-clonación.', 'price' => '34.99'],

    // Wrist
    ['image' => './wrist_tag1.png', 'title' => 'Wrist Band Basic', 'desc' => 'Banda muy suave de silicona ecológica para rastreo cotidiano.', 'price' => '22.99'],
    ['image' => './wrist_tag2.png', 'title' => 'Wrist Sport', 'desc' => 'Resistente al sudor y agua salada. Perfecto para deportistas.', 'price' => '28.99'],
    ['image' => './wrist_tag3.png', 'title' => 'Wrist Premium', 'desc' => 'Parece un smartwatch. Moderno, minimalista y espectacular.', 'price' => '40.99'],
    ['image' => './wrist_tag4.png', 'title' => 'Wrist Secure', 'desc' => 'Seguro extra fuerte para no perderlo nunca de la muñeca.', 'price' => '35.99']
];

foreach ($products as $i => $product) {
    // Añadir transformaciones sutiles para darle más dinamismo al grid
    $rotation = ($i % 3 === 0) ? 'transform: rotate(5deg);' : (($i % 4 === 0) ? 'transform: rotate(-5deg);' : '');
?>
        <div class="product-card">
            <div class="image-container" onclick="openLightbox('<?php echo $product['image']; ?>')">
                <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['title']; ?>" class="product-image" style="<?php echo $rotation; ?>">
                <button class="btn-expand" title="Ampliar imagen">🔍</button>
            </div>
            <div class="product-info">
                <h3 class="product-title"><?php echo $product['title']; ?></h3>
                <p class="product-desc"><?php echo $product['desc']; ?></p>
                <div class="product-price">$ <?php echo $product['price']; ?></div>
                <button class="btn-buy" onclick="addToCart('<?php echo $product['title']; ?>', <?php echo $product['price']; ?>, '<?php echo $product['image']; ?>')">Comprar</button>
            </div>
        </div>
<?php
}
?>
    </div>

    <div id="toast" class="toast">Producto añadido al carrito</div>

    <div id="lightbox" class="lightbox" onclick="closeLightbox(event)">
        <span class="lightbox-close" onclick="closeLightbox(event)">&times;</span>
        <img id="lightbox-img" class="lightbox-img" src="" alt="Ampliada">
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', updateCartCount);

        function updateCartCount() {
            let cart = JSON.parse(localStorage.getItem('sanAntonioCart')) || [];
            let totalItems = cart.reduce((acc, item) => acc + item.quantity, 0);
            document.getElementById('cart-count').innerText = totalItems;
        }

        function openLightbox(src) {
            const lightbox = document.getElementById('lightbox');
            const img = document.getElementById('lightbox-img');
            img.src = src;
            lightbox.classList.add('active');
        }

        function closeLightbox(e) {
            if (e.target.id === 'lightbox' || e.target.classList.contains('lightbox-close')) {
                document.getElementById('lightbox').classList.remove('active');
            }
        }

        function addToCart(title, price, image) {
            let cart = JSON.parse(localStorage.getItem('sanAntonioCart')) || [];
            let existingItem = cart.find(item => item.title === title);
            
            if (existingItem) {
                existingItem.quantity += 1;
            } else {
                cart.push({ title, price, image, quantity: 1 });
            }
            
            localStorage.setItem('sanAntonioCart', JSON.stringify(cart));
            updateCartCount();

            const toast = document.getElementById('toast');
            toast.textContent = title + " añadido al carrito 🛒";
            toast.classList.add('show');
            setTimeout(() => {
                toast.classList.remove('show');
            }, 3000);
        }
    </script>
</body>
</html>
