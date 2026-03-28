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
            // Send webhook notification via CallMeBot
            $api_key = "1189818"; // REEMPLAZA ESTO CON TU API KEY DE CALLMEBOT (DONE!)
            $phone = "+584143332662";
            
            $mensaje = "🚨 *Nuevo objeto publicado*\n📦 Nombre: $nombre\n📍 Ubicación: $ubicacion\n📞 Contacto: $contacto\n📝 Descripción: $desc";
            
            // CallMeBot Webhook URL configuration
            $url = "https://api.callmebot.com/whatsapp.php?phone=" . urlencode($phone) . "&text=" . urlencode($mensaje) . "&apikey=" . $api_key;

            if (function_exists('curl_init')) {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Evita problemas de certificado SSL en XAMPP/localhost
                curl_setopt($ch, CURLOPT_TIMEOUT, 10); // Evita que se quede cargando infinitamente si la API demora
                $response = curl_exec($ch);
                curl_close($ch);
            } else {
                // Alternativa en caso de que cURL no esté activado en XAMPP
                $opciones = ["http" => ["ignore_errors" => true, "timeout" => 10]];
                $contexto = stream_context_create($opciones);
                $response = @file_get_contents($url, false, $contexto);
            }

            header("Location: index.php?mensaje=publicado");
        }
    }
}
?>
