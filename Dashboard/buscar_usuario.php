<?php
session_start();
header('Content-Type: application/json');

// Mostrar todos los errores (solo para diagnóstico, luego se quita)
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['logueado']) || $_SESSION['logueado'] !== true) {
    echo json_encode([]);
    exit();
}

require_once '../db.php';

// Verificar conexión
if ($conexion->connect_error) {
    echo json_encode(['error' => 'Conexión fallida: ' . $conexion->connect_error]);
    exit();
}

$query = $_GET['q'] ?? '';
$response = [];

if (strlen($query) >= 2) {
    // Escapar manualmente
    $q = $conexion->real_escape_string($query);
    $like = "%$q%";
    
    $sql = "SELECT id, nombre, apellidos, ci, 'estudiante' as tipo 
            FROM estudiante 
            WHERE activo = 1 AND (nombre LIKE '$like' OR apellidos LIKE '$like' OR ci LIKE '$like') 
            LIMIT 10";
    
    $result = $conexion->query($sql);
    
    if (!$result) {
        echo json_encode(['error' => 'Error SQL: ' . $conexion->error, 'sql' => $sql]);
        exit();
    }
    
    while ($row = $result->fetch_assoc()) {
        $response[] = $row;
    }
}

echo json_encode($response);
?>