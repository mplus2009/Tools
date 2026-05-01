<?php
session_start();
require_once '../db.php';

header('Content-Type: application/json');

$response = ['success' => false, 'message' => 'Error al procesar'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $destinatarios = json_decode($_POST['destinatarios'] ?? '[]', true);
    $actividades = json_decode($_POST['actividades'] ?? '[]', true);
    $fecha = $_POST['fecha'] ?? date('Y-m-d');
    $hora = $_POST['hora'] ?? date('H:i');
    $observaciones = $_POST['observaciones'] ?? '';
    $id_star = intval($_POST['id_star'] ?? 0);
    $tipo_notificador = $_POST['tipo_notificador'] ?? 'cuenta';
    $cargo_notificador = $_POST['cargo_notificador'] ?? 'estudiante';
    
    // Formatear id_star como el Dashboard: "cargo_id"
    $id_star_formateado = $cargo_notificador . '_' . $id_star;
    
    if (empty($destinatarios) || empty($actividades)) {
        $response['message'] = 'Faltan destinatarios o actividades';
        echo json_encode($response);
        exit;
    }
    
    $errores = [];
    $insertados = 0;
    
    foreach ($destinatarios as $dest) {
        // Formatear id_end como el Dashboard: "estudiante_ID"
        $id_end_formateado = 'estudiante_' . intval($dest['id']);
        $grado_dest = $dest['grado'] ?? '10mo';
        
        foreach ($actividades as $act) {
            // Determinar cantidad según grado para deméritos
            $cantidad = intval($act['cantidad']);
            
            if ($act['tipo'] === 'demerito') {
                // Si hay valores separados por grado
                if (isset($act['valor10mo']) && $act['valor10mo'] !== null && 
                    isset($act['valor11_12']) && $act['valor11_12'] !== null) {
                    if ($grado_dest === '10mo') {
                        $cantidad = intval($act['valor10mo']);
                    } else {
                        $cantidad = intval($act['valor11_12']);
                    }
                }
            }
            
            $sql = "INSERT INTO actividad (id_star, id_end, tipo, categoria, falta_causa, cantidad, fecha, hora, observaciones, leido) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 0)";
            $stmt = $conexion->prepare($sql);
            
            if ($stmt) {
                $tipo = $act['tipo'];
                $categoria = $act['categoria'];
                $falta_causa = $act['nombre'];
                
                $stmt->bind_param("sssssisss", 
                    $id_star_formateado, 
                    $id_end_formateado, 
                    $tipo, 
                    $categoria, 
                    $falta_causa, 
                    $cantidad, 
                    $fecha, 
                    $hora, 
                    $observaciones
                );
                
                if ($stmt->execute()) {
                    $insertados++;
                } else {
                    $errores[] = "Error al insertar: " . $stmt->error;
                }
                $stmt->close();
            }
        }
    }
    
    if ($insertados > 0) {
        $response = ['success' => true, 'message' => "Se insertaron $insertados actividades correctamente"];
    } else {
        $response['message'] = 'No se pudo insertar ninguna actividad. ' . implode(', ', $errores);
    }
}

echo json_encode($response);
?>