<?php
// carrito.php
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras - San Antonio</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', 'Segoe UI', Arial, sans-serif; }
        
        :root {
            --primary: #00ffea;
            --bg-color: #0a0f16;
            --glass-bg: rgba(255, 255, 255, 0.05);
            --glass-border: rgba(255, 255, 255, 0.1);
            --danger: #ff4757;
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

        .cart-container {
            width: 100%;
            max-width: 900px;
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            padding: 30px;
            backdrop-filter: blur(12px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            margin-bottom: 50px;
        }

        .cart-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 15px;
            border: 1px solid var(--glass-border);
            transition: 0.3s;
        }

        .cart-item:hover {
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(0, 255, 234, 0.3);
        }

        .item-info {
            display: flex;
            align-items: center;
            gap: 20px;
            flex: 1;
        }

        .item-img {
            width: 80px;
            height: 80px;
            object-fit: contain;
            filter: drop-shadow(0 5px 10px rgba(0,0,0,0.5));
        }

        .item-title {
            font-size: 1.2rem;
            font-weight: bold;
        }

        .item-price {
            color: var(--primary);
            font-size: 1.1rem;
            margin-top: 5px;
        }

        .item-actions {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .qty-btn {
            background: var(--glass-bg);
            border: 1px solid var(--primary);
            color: var(--primary);
            border-radius: 5px;
            width: 30px;
            height: 30px;
            font-size: 1.2rem;
            cursor: pointer;
            transition: 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        .qty-btn:hover {
            background: var(--primary);
            color: #000;
            box-shadow: 0 0 10px var(--primary);
        }

        .qty-display {
            font-size: 1.2rem;
            font-weight: bold;
            min-width: 30px;
            text-align: center;
        }

        .btn-remove {
            background: transparent;
            color: var(--danger);
            border: none;
            cursor: pointer;
            font-size: 1.5rem;
            transition: 0.3s;
        }

        .btn-remove:hover {
            transform: scale(1.2);
            text-shadow: 0 0 10px var(--danger);
        }

        .cart-summary {
            margin-top: 30px;
            border-top: 1px solid var(--glass-border);
            padding-top: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .cart-total {
            font-size: 1.8rem;
            font-weight: bold;
        }

        .cart-total span {
            color: var(--primary);
            text-shadow: 0 0 15px rgba(0,255,234,0.4);
        }

        .btn-checkout {
            background: var(--primary);
            color: #000;
            border: none;
            padding: 15px 40px;
            border-radius: 50px;
            font-size: 1.2rem;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
            text-transform: uppercase;
            box-shadow: 0 0 15px rgba(0, 255, 234, 0.4);
        }

        .btn-checkout:hover {
            transform: scale(1.05);
            box-shadow: 0 0 25px rgba(0, 255, 234, 0.6);
        }

        .empty-cart {
            text-align: center;
            padding: 40px;
            color: #aaa;
            font-size: 1.2rem;
        }

        @media (max-width: 768px) {
            .cart-item {
                flex-direction: column;
                gap: 20px;
            }
            .cart-summary {
                flex-direction: column;
                gap: 20px;
                text-align: center;
            }
        }
    </style>
</head>
<body>

    <nav>
        <a href="ecommerce.php" class="btn-back">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
            Seguir Comprando
        </a>
        <div style="font-weight: bold; letter-spacing: 2px; color: var(--primary);">MI CARRITO</div>
    </nav>

    <div class="header-title">
        <h1>Tu Orden</h1>
    </div>

    <div class="cart-container">
        <div id="cart-items">
            <!-- Los items del carrito se cargarán aquí por JS -->
        </div>

        <div class="cart-summary" id="cart-summary" style="display: none;">
            <div class="cart-total">Total: <span id="total-price">$0.00</span></div>
            <button class="btn-checkout" onclick="procederPago()">Proceder al Pago</button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', loadCart);

        function loadCart() {
            let cart = JSON.parse(localStorage.getItem('sanAntonioCart')) || [];
            const cartItemsDiv = document.getElementById('cart-items');
            const cartSummary = document.getElementById('cart-summary');
            
            cartItemsDiv.innerHTML = '';
            
            if (cart.length === 0) {
                cartItemsDiv.innerHTML = '<div class="empty-cart"><p>🛒 Tu carrito está vacío.</p><br><a href="ecommerce.php" style="color: var(--primary); text-decoration: none;">Explorar Rastreadores</a></div>';
                cartSummary.style.display = 'none';
                return;
            }

            cartSummary.style.display = 'flex';
            let totalPrice = 0;

            cart.forEach((item, index) => {
                const itemTotal = item.price * item.quantity;
                totalPrice += itemTotal;

                cartItemsDiv.innerHTML += `
                    <div class="cart-item">
                        <div class="item-info">
                            <img src="${item.image}" alt="${item.title}" class="item-img">
                            <div>
                                <div class="item-title">${item.title}</div>
                                <div class="item-price">$${item.price.toFixed(2)} c/u</div>
                            </div>
                        </div>
                        <div class="item-actions">
                            <button class="qty-btn" onclick="updateQty(${index}, -1)">-</button>
                            <div class="qty-display">${item.quantity}</div>
                            <button class="qty-btn" onclick="updateQty(${index}, 1)">+</button>
                            <button class="btn-remove" onclick="removeItem(${index})" title="Eliminar">&times;</button>
                        </div>
                    </div>
                `;
            });

            document.getElementById('total-price').innerText = '$' + totalPrice.toFixed(2);
        }

        function updateQty(index, delta) {
            let cart = JSON.parse(localStorage.getItem('sanAntonioCart')) || [];
            if (cart[index]) {
                cart[index].quantity += delta;
                if (cart[index].quantity <= 0) {
                    cart.splice(index, 1);
                }
                localStorage.setItem('sanAntonioCart', JSON.stringify(cart));
                loadCart();
            }
        }

        function removeItem(index) {
            let cart = JSON.parse(localStorage.getItem('sanAntonioCart')) || [];
            cart.splice(index, 1);
            localStorage.setItem('sanAntonioCart', JSON.stringify(cart));
            loadCart();
        }

        function procederPago() {
            let cart = JSON.parse(localStorage.getItem('sanAntonioCart')) || [];
            if(cart.length === 0) return;
            
            window.location.href = 'pago.php';
        }
    </script>
</body>
</html>
