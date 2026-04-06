<?php
// pago.php
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finalizar Pago - San Antonio</title>
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

        body::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle at 50% 50%, rgba(0, 255, 234, 0.1), transparent 60%);
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

        .header-title {
            text-align: center;
            margin: 40px 0 20px;
        }

        .header-title h1 {
            font-size: 2.5rem;
            background: linear-gradient(135deg, #fff, #00ffea);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            text-transform: uppercase;
            letter-spacing: 2px;
            text-shadow: 0 0 30px rgba(0, 255, 234, 0.3);
        }

        .checkout-container {
            width: 100%;
            max-width: 1000px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            padding: 20px;
            margin-bottom: 50px;
        }

        .checkout-box {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            padding: 30px;
            backdrop-filter: blur(12px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            display: flex;
            flex-direction: column;
        }

        .checkout-box h2 {
            font-size: 1.5rem;
            margin-bottom: 25px;
            color: var(--primary);
            border-bottom: 1px solid var(--glass-border);
            padding-bottom: 15px;
        }

        /* Order Summary Styles */
        .summary-items {
            flex: 1;
            overflow-y: auto;
            max-height: 350px;
            margin-bottom: 20px;
            padding-right: 10px;
        }

        .summary-items::-webkit-scrollbar { width: 6px; }
        .summary-items::-webkit-scrollbar-thumb { background: rgba(0,255,234,0.3); border-radius: 10px; }

        .summary-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            background: rgba(255, 255, 255, 0.02);
            padding: 10px;
            border-radius: 8px;
        }

        .summary-item-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .summary-item-img {
            width: 50px;
            height: 50px;
            object-fit: contain;
        }

        .summary-item-title {
            font-size: 1rem;
            font-weight: bold;
        }

        .summary-item-qty {
            font-size: 0.85rem;
            color: #aaa;
        }

        .summary-item-price {
            font-weight: bold;
            color: var(--primary);
        }

        .summary-total {
            display: flex;
            justify-content: space-between;
            font-size: 1.5rem;
            font-weight: bold;
            border-top: 1px solid var(--glass-border);
            padding-top: 15px;
        }

        .summary-total span {
            color: var(--primary);
            text-shadow: 0 0 10px rgba(0,255,234,0.4);
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #ccc;
            font-size: 0.95rem;
        }

        .form-group input {
            width: 100%;
            padding: 15px;
            background: rgba(0, 0, 0, 0.3);
            border: 1px solid var(--glass-border);
            border-radius: 8px;
            color: #fff;
            font-size: 1rem;
            transition: 0.3s;
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 10px rgba(0, 255, 234, 0.2);
        }

        .btn-submit {
            background: var(--primary);
            color: #000;
            border: none;
            padding: 15px;
            border-radius: 50px;
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
            text-transform: uppercase;
            width: 100%;
            margin-top: 10px;
            box-shadow: 0 0 15px rgba(0, 255, 234, 0.4);
        }

        .btn-submit:hover {
            transform: scale(1.02);
            box-shadow: 0 0 25px rgba(0, 255, 234, 0.6);
        }

        /* Success Message */
        .success-overlay {
            display: none;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 40px;
            grid-column: 1 / -1; /* Ocupar todo el ancho en el grid */
        }

        .success-overlay h2 {
            font-size: 2.5rem;
            color: var(--primary);
            margin-bottom: 20px;
            text-shadow: 0 0 20px rgba(0,255,234,0.5);
            border: none;
        }

        .success-overlay p {
            font-size: 1.2rem;
            line-height: 1.6;
            color: #ddd;
            margin-bottom: 30px;
            max-width: 600px;
        }

        @media (max-width: 768px) {
            .checkout-container {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

    <nav>
        <a href="carrito.php" class="btn-back">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
            Volver al Carrito
        </a>
        <div style="font-weight: bold; letter-spacing: 2px; color: var(--primary);">CHECKOUT SECURE</div>
    </nav>

    <div class="header-title" id="main-title">
        <h1>Finalizar Compra</h1>
    </div>

    <div class="checkout-container" id="checkout-content">
        
        <!-- Order Summary -->
        <div class="checkout-box">
            <h2>Resumen de tu Orden</h2>
            <div class="summary-items" id="summary-items">
                <!-- Javascript will load items here -->
            </div>
            <div class="summary-total">
                Total a pagar: <span id="summary-total-price">$0.00</span>
            </div>
        </div>

        <!-- Payment Details Form -->
        <div class="checkout-box">
            <h2>Datos de Contacto</h2>
            <form id="checkout-form" onsubmit="procesarCompra(event)">
                <div class="form-group">
                    <label for="nombre">Nombre Completo *</label>
                    <input type="text" id="nombre" required placeholder="Ej: Juan Pérez">
                </div>
                <div class="form-group">
                    <label for="telefono">Teléfono / WhatsApp *</label>
                    <input type="tel" id="telefono" required placeholder="+58 414 1234567">
                </div>
                <div class="form-group">
                    <label for="email">Correo Electrónico *</label>
                    <input type="email" id="email" required placeholder="tu@correo.com">
                </div>
                <button type="submit" class="btn-submit">Confirmar Pedido</button>
            </form>
        </div>

    </div>

    <div class="checkout-container" id="success-content" style="display: none; max-width: 800px; margin-top: 20px;">
        <div class="checkout-box success-overlay" style="display: flex;">
            <h2>¡Pedido Confirmado!</h2>
            <p>Gracias por tu compra, en breve serás atendido(a) para tu forma de pago, logística y detalles.<br><br>¡Gracias por confiar en San Antonio!</p>
            <button class="btn-submit" style="width: auto; padding: 15px 40px;" onclick="window.location.href='index.php'">Volver al Inicio</button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', loadSummary);

        function loadSummary() {
            let cart = JSON.parse(localStorage.getItem('sanAntonioCart')) || [];
            if(cart.length === 0) {
                window.location.href = 'carrito.php';
                return;
            }

            const summaryItemsDiv = document.getElementById('summary-items');
            let totalPrice = 0;
            summaryItemsDiv.innerHTML = '';

            cart.forEach(item => {
                const itemTotal = item.price * item.quantity;
                totalPrice += itemTotal;

                summaryItemsDiv.innerHTML += `
                    <div class="summary-item">
                        <div class="summary-item-info">
                            <img src="${item.image}" alt="${item.title}" class="summary-item-img">
                            <div>
                                <div class="summary-item-title">${item.title}</div>
                                <div class="summary-item-qty">Cantidad: ${item.quantity}</div>
                            </div>
                        </div>
                        <div class="summary-item-price">$${itemTotal.toFixed(2)}</div>
                    </div>
                `;
            });

            document.getElementById('summary-total-price').innerText = '$' + totalPrice.toFixed(2);
        }

        function procesarCompra(e) {
            e.preventDefault(); // Evitar recarga de página

            let cart = JSON.parse(localStorage.getItem('sanAntonioCart')) || [];
            if(cart.length === 0) return;

            // Recopilar datos del formulario
            const nombre = document.getElementById('nombre').value;
            const telefono = document.getElementById('telefono').value;
            const email = document.getElementById('email').value;

            // Construir el mensaje para WhatsApp
            let mensaje = `*NUEVO PEDIDO - SAN ANTONIO STORE*\n\n`;
            mensaje += `*Cliente:* ${nombre}\n`;
            mensaje += `*Teléfono:* ${telefono}\n`;
            mensaje += `*Email:* ${email}\n\n`;
            mensaje += `*Detalles del Pedido:*\n`;

            let totalPrice = 0;
            cart.forEach(item => {
                const itemTotal = item.price * item.quantity;
                totalPrice += itemTotal;
                mensaje += `- ${item.quantity}x ${item.title} ($${itemTotal.toFixed(2)})\n`;
            });

            mensaje += `\n*TOTAL A PAGAR:* $${totalPrice.toFixed(2)}\n\n`;
            mensaje += `Gracias por tu compra!- te contactaremos para las formas de pago y detalles logísticos. Gracias!!!`;

            // Enviar confirmación automática vía CallMeBot API
            const api_key = "1189818";
            const numWA = "+584143332662";
            const urlWA = `https://api.callmebot.com/whatsapp.php?phone=${encodeURIComponent(numWA)}&text=${encodeURIComponent(mensaje)}&apikey=${api_key}`;
            
            // Petición oculta en segundo plano sin redirigir al usuario
            fetch(urlWA, { mode: 'no-cors' }).catch(err => console.error(err));

            // Ocultar formulario y mostrar msj de éxito
            document.getElementById('checkout-content').style.display = 'none';
            document.getElementById('success-content').style.display = 'grid';
            document.getElementById('main-title').style.display = 'none'; // Opcional, para limpiar

            // Limpiar el carrito simulando que ya se procesó
            localStorage.removeItem('sanAntonioCart');
            
            // Hacer scroll hacia arriba para q pueda ver bien el msj de éxito
            window.scrollTo(0, 0);
        }
    </script>
</body>
</html>
