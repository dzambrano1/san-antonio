<?php
// Script para generar el hash correcto y actualizar la base de datos
require_once 'db.php';

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Generar hash para Admin123
$password = "Admin123";
$hash = password_hash($password, PASSWORD_DEFAULT);

echo "<h2>Hash generado para 'Admin123':</h2>";
echo "<code>$hash</code><br><br>";

// Verificar si existe el admin
$res = $conn->query("SELECT id, email, password FROM admin WHERE email = 'douglasezambrano@gmail.com'");

if ($res->num_rows > 0) {
    // Actualizar contraseña
    $sql = "UPDATE admin SET password = '$hash' WHERE email = 'douglasezambrano@gmail.com'";
    if ($conn->query($sql)) {
        echo "<h3 style='color: green;'>✅ Contraseña actualizada exitosamente!</h3>";
        echo "<p>Ahora puedes login con:</p>";
        echo "<ul>";
        echo "<li><strong>Email:</strong> douglasezambrano@gmail.com</li>";
        echo "<li><strong>Password:</strong> Admin123</li>";
        echo "</ul>";
        echo "<p><a href='login.php'>Ir al Login</a></p>";
    } else {
        echo "<h3 style='color: red;'>Error al actualizar: " . $conn->error . "</h3>";
    }
} else {
    // Insertar nuevo admin
    $sql = "INSERT INTO admin (email, password) VALUES ('douglasezambrano@gmail.com', '$hash')";
    if ($conn->query($sql)) {
        echo "<h3 style='color: green;'>✅ Admin creado exitosamente!</h3>";
        echo "<p>Ahora puedes login con:</p>";
        echo "<ul>";
        echo "<li><strong>Email:</strong> douglasezambrano@gmail.com</li>";
        echo "<li><strong>Password:</strong> Admin123</li>";
        echo "</ul>";
        echo "<p><a href='login.php'>Ir al Login</a></p>";
    } else {
        echo "<h3 style='color: red;'>Error al insertar: " . $conn->error . "</h3>";
    }
}

$conn->close();
?>
