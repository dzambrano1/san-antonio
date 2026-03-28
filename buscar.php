<?php
header('Content-Type: application/json');

$conn = new mysqli("localhost", "root", "", "san_antonio");

if ($conn->connect_error) {
    echo json_encode(['error' => 'Error de conexión', 'objetos' => [], 'total' => 0]);
    exit;
}

$busqueda = isset($_GET['q']) ? trim($_GET['q']) : '';

if ($busqueda === '') {
    $res = $conn->query("SELECT * FROM objetos WHERE estado = 'publicado' ORDER BY id DESC");
} else {
    $busqueda = $conn->real_escape_string($busqueda);
    $sql = "SELECT * FROM objetos 
            WHERE estado = 'publicado'
            AND (nombre LIKE '%$busqueda%' 
               OR ubicacion LIKE '%$busqueda%' 
               OR descripcion LIKE '%$busqueda%') 
            ORDER BY id DESC";
    $res = $conn->query($sql);
}

$objetos = [];
while($row = $res->fetch_assoc()) {
    $objetos[] = $row;
}

echo json_encode([
    'objetos' => $objetos,
    'total' => count($objetos)
]);

$conn->close();
?>
