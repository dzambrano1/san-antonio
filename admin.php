<?php
session_start();

// Verificar si está logueado
if (!isset($_SESSION['admin_logueado']) || $_SESSION['admin_logueado'] !== true) {
    header("Location: login.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "san_antonio");
$mensaje = '';

// Procesar guardado de cambios
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['guardar_cambios'])) {
    if (isset($_POST['objetos']) && is_array($_POST['objetos'])) {
        foreach ($_POST['objetos'] as $id => $datos) {
            $id = (int)$id;
            $estado = $conn->real_escape_string($datos['estado']);
            $fecha_vencimiento = !empty($datos['fecha_vencimiento']) ? $conn->real_escape_string($datos['fecha_vencimiento']) : NULL;
            
            if ($fecha_vencimiento) {
                $sql = "UPDATE objetos SET estado = '$estado', fecha_vencimiento = '$fecha_vencimiento' WHERE id = $id";
            } else {
                $sql = "UPDATE objetos SET estado = '$estado', fecha_vencimiento = NULL WHERE id = $id";
            }
            $conn->query($sql);
        }
        $mensaje = "✅ Cambios guardados exitosamente";
    }
}

// Obtener todos los objetos
$res = $conn->query("SELECT * FROM objetos ORDER BY id DESC");
$objetos = [];
while($row = $res->fetch_assoc()) {
    $objetos[] = $row;
}

// Contar por estado
$conteo = ['prohibido' => 0, 'vencido' => 0, 'publicado' => 0, 'investigando' => 0];
foreach ($objetos as $obj) {
    if (isset($conteo[$obj['estado']])) {
        $conteo[$obj['estado']]++;
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administrativo - San Antonio</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Arial', sans-serif; background: #f5f5f5; }
        
        .header {
            background: linear-gradient(135deg, #b35900, #8f4700);
            color: white;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }
        .header h1 { font-size: 1.5rem; }
        .header-info { display: flex; align-items: center; gap: 20px; }
        .header-info span { font-size: 0.9rem; opacity: 0.9; }
        .btn-logout {
            background: rgba(255,255,255,0.2);
            color: white;
            padding: 8px 16px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 0.9rem;
            transition: background 0.3s;
        }
        .btn-logout:hover { background: rgba(255,255,255,0.3); }
        
        .container { max-width: 1400px; margin: 0 auto; padding: 30px; }
        
        .mensaje {
            background: #d4edda;
            color: #155724;
            padding: 15px 20px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
        }
        
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        .stat-card h3 { font-size: 2rem; margin-bottom: 5px; }
        .stat-card p { color: #666; font-size: 0.9rem; text-transform: uppercase; }
        .stat-card.prohibido h3 { color: #dc3545; }
        .stat-card.vencido h3 { color: #ffc107; }
        .stat-card.publicado h3 { color: #28a745; }
        .stat-card.investigando h3 { color: #17a2b8; }
        
        .form-section {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .form-header {
            background: #f8f9fa;
            padding: 20px 30px;
            border-bottom: 1px solid #dee2e6;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .form-header h2 { font-size: 1.2rem; color: #333; }
        .btn-guardar {
            background: #28a745;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s;
        }
        .btn-guardar:hover { background: #218838; }
        
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 15px 20px; text-align: left; border-bottom: 1px solid #dee2e6; }
        th { background: #f8f9fa; font-weight: 600; color: #333; font-size: 0.85rem; text-transform: uppercase; }
        tr:hover { background: #f8f9fa; }
        
        .objeto-info { display: flex; align-items: center; gap: 15px; }
        .objeto-info img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #ddd;
        }
        .objeto-info .detalles h4 { font-size: 1rem; margin-bottom: 3px; }
        .objeto-info .detalles p { font-size: 0.85rem; color: #666; }
        
        .estado-select {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 0.9rem;
            cursor: pointer;
        }
        .estado-select.prohibido { border-color: #dc3545; background: #fff5f5; }
        .estado-select.vencido { border-color: #ffc107; background: #fffdf0; }
        .estado-select.publicado { border-color: #28a745; background: #f0fff4; }
        .estado-select.investigando { border-color: #17a2b8; background: #f0fbff; }
        
        .fecha-input {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 0.9rem;
        }
        
        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: bold;
            text-transform: uppercase;
        }
        .badge-prohibido { background: #dc3545; color: white; }
        .badge-vencido { background: #ffc107; color: #333; }
        .badge-publicado { background: #28a745; color: white; }
        .badge-investigando { background: #17a2b8; color: white; }
        
        .no-objetos {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }
        
        .btn-volver-index {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background: #6c757d;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 0.9rem;
            transition: background 0.3s;
        }
        .btn-volver-index:hover { background: #5a6268; }
    </style>
</head>
<body>
    <div class="header">
        <h1>📋 Panel Administrativo</h1>
        <div class="header-info">
            <span>👤 <?php echo htmlspecialchars($_SESSION['admin_email']); ?></span>
            <a href="logout.php" class="btn-logout">Cerrar Sesión</a>
        </div>
    </div>
    
    <div class="container">
        <?php if ($mensaje): ?>
            <div class="mensaje"><?php echo $mensaje; ?></div>
        <?php endif; ?>
        
        <div class="stats">
            <div class="stat-card prohibido">
                <h3><?php echo $conteo['prohibido']; ?></h3>
                <p>Prohibidos</p>
            </div>
            <div class="stat-card vencido">
                <h3><?php echo $conteo['vencido']; ?></h3>
                <p>Vencidos</p>
            </div>
            <div class="stat-card publicado">
                <h3><?php echo $conteo['publicado']; ?></h3>
                <p>Publicados</p>
            </div>
            <div class="stat-card investigando">
                <h3><?php echo $conteo['investigando']; ?></h3>
                <p>Investigando</p>
            </div>
        </div>
        
        <form method="POST" action="" class="form-section">
            <div class="form-header">
                <h2>Control de Objetos Publicados</h2>
                <button type="submit" name="guardar_cambios" class="btn-guardar">💾 Guardar Cambios</button>
            </div>
            
            <?php if (empty($objetos)): ?>
                <div class="no-objetos">
                    <p>No hay objetos registrados en el sistema.</p>
                    <a href="index.php" class="btn-volver-index">← Volver al Inicio</a>
                </div>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th style="width: 80px;">ID</th>
                            <th>Objeto</th>
                            <th>Ubicación</th>
                            <th>Fecha Publicación</th>
                            <th>Estado</th>
                            <th>Vencimiento</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($objetos as $obj): ?>
                            <tr>
                                <td><strong>#<?php echo $obj['id']; ?></strong></td>
                                <td>
                                    <div class="objeto-info">
                                        <img src="uploads/<?php echo $obj['imagen']; ?>" alt="Objeto">
                                        <div class="detalles">
                                            <h4><?php echo htmlspecialchars($obj['nombre']); ?></h4>
                                            <p><?php echo htmlspecialchars(substr($obj['descripcion'], 0, 50)); ?>...</p>
                                        </div>
                                    </div>
                                </td>
                                <td><?php echo htmlspecialchars($obj['ubicacion']); ?></td>
                                <td><?php echo date('d/m/Y', strtotime($obj['fecha_creacion'])); ?></td>
                                <td>
                                    <select name="objetos[<?php echo $obj['id']; ?>][estado]" class="estado-select <?php echo $obj['estado']; ?>">
                                        <option value="prohibido" <?php echo $obj['estado'] === 'prohibido' ? 'selected' : ''; ?>>🚫 Prohibido</option>
                                        <option value="vencido" <?php echo $obj['estado'] === 'vencido' ? 'selected' : ''; ?>>⏰ Vencido</option>
                                        <option value="publicado" <?php echo $obj['estado'] === 'publicado' ? 'selected' : ''; ?>>✅ Publicado</option>
                                        <option value="investigando" <?php echo $obj['estado'] === 'investigando' ? 'selected' : ''; ?>>🔍 Investigando</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="date" 
                                           name="objetos[<?php echo $obj['id']; ?>][fecha_vencimiento]" 
                                           class="fecha-input"
                                           value="<?php echo $obj['fecha_vencimiento'] ? htmlspecialchars($obj['fecha_vencimiento']) : ''; ?>">
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
            
            <div style="padding: 20px 30px; background: #f8f9fa; border-top: 1px solid #dee2e6; text-align: center;">
                <a href="index.php" class="btn-volver-index">← Ver Página Principal</a>
            </div>
        </form>
    </div>
    
    <script>
        // Actualizar clase del select cuando cambia
        document.querySelectorAll('.estado-select').forEach(select => {
            select.addEventListener('change', function() {
                this.className = 'estado-select ' + this.value;
            });
        });
    </script>
</body>
</html>
