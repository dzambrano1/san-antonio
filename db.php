<?php
// Configuración de la Base de Datos
// Para desarrollo local (XAMPP):
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "san_antonio";

// Para producción (Hosting zya.me / hstn.me):
// $host = "sql306.hstn.me";
// $user = "mseet_41502660"; // Ejemplo: unaux_12345678
// $pass = "Sebastian123";
// $dbname = "mseet_41502660_san_antonio"; // Ejemplo: unaux_12345678_san_antonio

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Error de conexión a la base de datos: " . $conn->connect_error);
}
?>
