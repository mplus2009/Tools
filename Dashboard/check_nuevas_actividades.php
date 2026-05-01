<?php
session_start();
require_once '../db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['logueado']) || $_SESSION['logueado'] !== true) {
    echo json_encode(['error' => 'No autorizado']);
    exit();
}

$usuario_id = $_SESSION['usuario_id'] ?? 0;
$usuario_cargo = $_SESSION['usuario_cargo'] ?? 'estudiante';

if ($usuario_cargo !== 'estudiante' || $usuario_id <= 0) {
    echo json_encode(['nuevas' => false, 'actividades' => [], 'stats' => []]);
    exit();
}

$id_formateado = 'estudiante_' . $usuario_id;

// Calcular inicio de semana (miércoles)
$hoy = date('Y-m-d');
$dia_semana = date('N', strtotime($hoy));
if ($dia_semana >= 3) {
    $inicio_semana = date('Y-m-d', strtotime("-" . ($dia_semana - 3) . " days"));
} else {
    $inicio_semana = date('Y-m-d', strtotime("-" . ($dia_semana + 4) . " days"));
}

// ============================================
// BUSCAR ACTIVIDADES NO LEÍDAS (leido = 0)
// ============================================
$nuevas_actividades = [];

$sql = "SELECT a.*, 
        CASE 
            WHEN a.id_star LIKE 'estudiante_%' THEN (SELECT CONCAT(nombre, ' ', apellidos) FROM estudiante WHERE id = SUBSTRING_INDEX(a.id_star, '_', -1))
            WHEN a.id_star LIKE 'profesor_%' THEN (SELECT CONCAT(nombre, ' ', apellidos) FROM profesor WHERE id = SUBSTRING_INDEX(a.id_star, '_', -1))
            WHEN a.id_star LIKE 'oficial_%' THEN (SELECT CONCAT(nombre, ' ', apellidos) FROM oficial WHERE id = SUBSTRING_INDEX(a.id_star, '_', -1))
            WHEN a.id_star LIKE 'directiva_%' THEN (SELECT CONCAT(nombre, ' ', apellidos) FROM directiva WHERE id = SUBSTRING_INDEX(a.id_star, '_', -1))
            WHEN a.id_star LIKE 'temporal_%' THEN REPLACE(SUBSTRING_INDEX(a.id_star, '_', -1), '_', ' ')
            ELSE 'Sistema'
        END as notificador
        FROM actividad a
        WHERE a.id_end = ? AND a.leido = 0
        ORDER BY a.id ASC
        LIMIT 20";

$stmt = $conexion->prepare($sql);
if ($stmt) {
    $stmt->bind_param("s", $id_formateado);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $nuevas_actividades[] = $row;
    }
    $stmt->close();
}

// MARCAR COMO LEÍDAS después de obtenerlas
if (!empty($nuevas_actividades)) {
    $ids = array_column($nuevas_actividades, 'id');
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $sql_update = "UPDATE actividad SET leido = 1 WHERE id IN ($placeholders)";
    $stmt_update = $conexion->prepare($sql_update);
    if ($stmt_update) {
        $types = str_repeat('i', count($ids));
        $stmt_update->bind_param($types, ...$ids);
        $stmt_update->execute();
        $stmt_update->close();
    }
}

// Estadísticas de esta semana
$meritos_semana = 0;
$demeritos_semana = 0;

$sql = "SELECT COALESCE(SUM(cantidad), 0) as total FROM actividad WHERE id_end = ? AND tipo = 'merito' AND fecha >= ?";
$stmt = $conexion->prepare($sql);
if ($stmt) {
    $stmt->bind_param("ss", $id_formateado, $inicio_semana);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) $meritos_semana = (int)$row['total'];
    $stmt->close();
}

$sql = "SELECT COALESCE(SUM(cantidad), 0) as total FROM actividad WHERE id_end = ? AND tipo = 'demerito' AND fecha >= ?";
$stmt = $conexion->prepare($sql);
if ($stmt) {
    $stmt->bind_param("ss", $id_formateado, $inicio_semana);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) $demeritos_semana = (int)$row['total'];
    $stmt->close();
}

// Alarma
$limites = ['10mo' => 15, '11no' => 11, '12mo' => 10];
$grado_estudiante = '10mo';
$sql = "SELECT grado FROM estudiante WHERE id = ?";
$stmt = $conexion->prepare($sql);
if ($stmt) {
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) $grado_estudiante = $row['grado'] ?? '10mo';
    $stmt->close();
}
$limite_semanal = $limites[$grado_estudiante] ?? 15;
$alarma_activa = ($demeritos_semana >= $limite_semanal);

$response = [
    'nuevas' => !empty($nuevas_actividades),
    'actividades' => $nuevas_actividades,
    'stats' => [
        'meritos_semana' => $meritos_semana,
        'demeritos_semana' => $demeritos_semana,
        'balance_semana' => $meritos_semana - $demeritos_semana
    ],
    'alarma_activa' => $alarma_activa
];

echo json_encode($response);
?>