<?php
$conn = new mysqli("localhost", "root", "", "san_antonio");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $ubicacion = $conn->real_escape_string($_POST['ubicacion']);
    $desc = $conn->real_escape_string($_POST['descripcion']);
    $contacto = $conn->real_escape_string($_POST['contacto']);

    $foto_nombre = time() . "_" . $_FILES['foto']['name'];
    $ruta_destino = "uploads/" . $foto_nombre;

    // Fecha de vencimiento por defecto: 30 días
    $fecha_vencimiento = date('Y-m-d', strtotime('+30 days'));

    if (move_uploaded_file($_FILES['foto']['tmp_name'], $ruta_destino)) {
        $sql = "INSERT INTO objetos (imagen, nombre, descripcion, ubicacion, contacto_hallador, estado, fecha_vencimiento)
                VALUES ('$foto_nombre', '$nombre', '$desc', '$ubicacion', '$contacto', 'publicado', '$fecha_vencimiento')";

        if ($conn->query($sql)) {
            header("Location: index.php?mensaje=publicado");
        }
    }
}
?>
