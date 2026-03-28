<?php
session_start();

// Si ya está logueado, redirigir al admin
if (isset($_SESSION['admin_logueado']) && $_SESSION['admin_logueado'] === true) {
    header("Location: admin.php");
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validaciones
    if (empty($email) || empty($password)) {
        $error = "Todos los campos son obligatorios";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Correo electrónico inválido";
    } elseif (strlen($password) < 6) {
        $error = "La contraseña debe tener al menos 6 caracteres";
    } elseif ($password !== $confirm_password) {
        $error = "Las contraseñas no coinciden";
    } else {
        $conn = new mysqli("localhost", "root", "", "san_antonio");
        
        if ($conn->connect_error) {
            $error = "Error de conexión a la base de datos";
        } else {
            // Verificar si el email ya existe
            $stmt = $conn->prepare("SELECT id FROM admin WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $error = "Este correo ya está registrado";
            } else {
                // Hash de contraseña
                $password_hash = password_hash($password, PASSWORD_DEFAULT);
                
                $stmt = $conn->prepare("INSERT INTO admin (email, password) VALUES (?, ?)");
                $stmt->bind_param("ss", $email, $password_hash);
                
                if ($stmt->execute()) {
                    $success = "✅ Registro exitoso. Ahora puedes iniciar sesión.";
                } else {
                    $error = "Error al registrar. Intente nuevamente.";
                }
            }
            $stmt->close();
            $conn->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro Admin - San Antonio</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)),
                        url('sanantonio.jpg') no-repeat center center/cover;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .register-container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 450px;
        }
        h1 {
            text-align: center;
            color: #b35900;
            margin-bottom: 10px;
            font-size: 1.5rem;
        }
        .subtitle {
            text-align: center;
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 30px;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 10px 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 0.9rem;
        }
        .success {
            background: #d4edda;
            color: #155724;
            padding: 10px 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 0.9rem;
            border: 1px solid #c3e6cb;
        }
        form input {
            width: 100%;
            padding: 12px 15px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }
        form input:focus {
            outline: none;
            border-color: #b35900;
        }
        .password-container {
            position: relative;
            display: flex;
            align-items: center;
        }
        .password-container input {
            flex: 1;
            padding-right: 50px;
        }
        .btn-toggle-password {
            position: absolute;
            right: 10px;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1.2rem;
            padding: 5px;
            opacity: 0.6;
            transition: opacity 0.3s;
        }
        .btn-toggle-password:hover {
            opacity: 1;
        }
        .btn-register {
            width: 100%;
            padding: 12px;
            background: #b35900;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s;
        }
        .btn-register:hover {
            background: #8f4700;
        }
        .btn-volver {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #666;
            text-decoration: none;
            font-size: 0.9rem;
        }
        .btn-volver:hover {
            color: #b35900;
        }
        .logo {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo span {
            font-size: 3rem;
        }
        .login-link {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            font-size: 0.9rem;
            color: #666;
        }
        .login-link a {
            color: #b35900;
            font-weight: bold;
            text-decoration: none;
        }
        .login-link a:hover {
            text-decoration: underline;
        }
        .requirements {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 0.85rem;
            color: #666;
        }
        .requirements strong {
            color: #b35900;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="logo">
            <span>📝</span>
        </div>
        <h1>Registro de Administrador</h1>
        <p class="subtitle">San Antonio - Control de Objetos</p>
        
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <div class="requirements">
            <strong>Requisitos de contraseña:</strong>
            <ul style="margin: 10px 0 0 20px;">
                <li>Mínimo 6 caracteres</li>
                <li>Debe coincidir con la confirmación</li>
            </ul>
        </div>
        
        <form method="POST" action="">
            <input type="email" name="email" placeholder="Correo electrónico" required 
                   value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
            <div class="password-container">
                <input type="password" name="password" id="password" placeholder="Contraseña" required>
                <button type="button" class="btn-toggle-password" onclick="togglePassword('password', 'eye1')">
                    <span id="eye1">👁️</span>
                </button>
            </div>
            <div class="password-container">
                <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirmar contraseña" required>
                <button type="button" class="btn-toggle-password" onclick="togglePassword('confirm_password', 'eye2')">
                    <span id="eye2">👁️</span>
                </button>
            </div>
            <button type="submit" class="btn-register">Registrarse</button>
        </form>
        
        <div class="login-link">
            ¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a>
        </div>
        
        <a href="index.php" class="btn-volver">← Volver al Inicio</a>
    </div>

    <script>
        function togglePassword(inputId, eyeId) {
            const passwordInput = document.getElementById(inputId);
            const eyeIcon = document.getElementById(eyeId);
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.textContent = '🔒';
            } else {
                passwordInput.type = 'password';
                eyeIcon.textContent = '👁️';
            }
        }
    </script>
</body>
</html>
