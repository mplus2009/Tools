<?php
session_start();
require_once '../db.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Diagnóstico de check_nuevas_actividades</h2>";

// 1. Verificar sesión
echo "<p><b>Sesión:</b> ";
if (!isset($_SESSION['logueado']) || $_SESSION['logueado'] !== true) {
    echo "<span style='color:red'>NO INICIADA</span>";
    exit();
}
echo "<span style='color:green'>OK</span></p>";

$usuario_id = $_SESSION['usuario_id'] ?? 0;
$usuario_cargo = $_SESSION['usuario_cargo'] ?? '';

echo "<p><b>Usuario:</b> ID=$usuario_id, Cargo=$usuario_cargo</p>";

if ($usuario_cargo !== 'estudiante') {
    echo "<p style='color:orange'>No eres estudiante, el sistema tiempo real es solo para estudiantes</p>";
    exit();
}

$id_formateado = 'estudiante_' . $usuario_id;
echo "<p><b>ID Formateado:</b> $id_formateado</p>";

// 2. Ver tabla actividad
$check = $conexion->query("SHOW TABLES LIKE 'actividad'");
if ($check->num_rows == 0) {
    echo "<p style='color:red'>Tabla 'actividad' NO EXISTE</p>";
    exit();
}
echo "<p style='color:green'>Tabla 'actividad' EXISTE</p>";

// 3. Ver columna leido
$check = $conexion->query("SHOW COLUMNS FROM actividad LIKE 'leido'");
if ($check->num_rows == 0) {
    echo "<p style='color:red'>Columna 'leido' NO EXISTE en tabla actividad</p>";
    echo "<p>Ejecuta: <code>ALTER TABLE actividad ADD COLUMN leido TINYINT(1) DEFAULT 0</code></p>";
    exit();
}
echo "<p style='color:green'>Columna 'leido' EXISTE</p>";

// 4. Contar actividades totales para este estudiante
$sql = "SELECT COUNT(*) as total FROM actividad WHERE id_end = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $id_formateado);
$stmt->execute();
$total = $stmt->get_result()->fetch_assoc()['total'];
echo "<p><b>Total actividades para este estudiante:</b> $total</p>";

// 5. Contar actividades NO LEÍDAS
$sql = "SELECT COUNT(*) as total FROM actividad WHERE id_end = ? AND leido = 0";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $id_formateado);
$stmt->execute();
$no_leidas = $stmt->get_result()->fetch_assoc()['total'];
echo "<p><b>Actividades NO LEÍDAS (leido=0):</b> <span style='color:" . ($no_leidas > 0 ? 'green' : 'red') . "'>$no_leidas</span></p>";

// 6. Mostrar últimas 10 actividades
$sql = "SELECT id, tipo, falta_causa, cantidad, fecha, leido FROM actividad WHERE id_end = ? ORDER BY id DESC LIMIT 10";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $id_formateado);
$stmt->execute();
$result = $stmt->get_result();

echo "<h3>Últimas 10 actividades:</h3>";
echo "<table border='1' cellpadding='5' style='border-collapse:collapse;font-size:12px;'>";
echo "<tr><th>ID</th><th>Tipo</th><th>Causa</th><th>Cant</th><th>Fecha</th><th>Leído</th></tr>";
while ($row = $result->fetch_assoc()) {
    $color = $row['leido'] == 0 ? '#ffcccc' : '#ccffcc';
    echo "<tr style='background:$color'>";
    echo "<td>{$row['id']}</td>";
    echo "<td>{$row['tipo']}</td>";
    echo "<td>{$row['falta_causa']}</td>";
    echo "<td>{$row['cantidad']}</td>";
    echo "<td>{$row['fecha']}</td>";
    echo "<td>" . ($row['leido'] == 0 ? 'NO LEÍDO' : 'Leído') . "</td>";
    echo "</tr>";
}
echo "</table>";

// 7. Simular la consulta del check
echo "<h3>Simulando check_nuevas_actividades.php:</h3>";
$sql = "SELECT a.*, 
        CASE 
            WHEN a.id_star LIKE 'estudiante_%' THEN (SELECT CONCAT(nombre, ' ', apellidos) FROM estudiante WHERE id = SUBSTRING_INDEX(a.id_star, '_', -1))
            ELSE 'Sistema'
        END as notificador
        FROM actividad a
        WHERE a.id_end = ? AND a.leido = 0
        ORDER BY a.id DESC
        LIMIT 10";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $id_formateado);
$stmt->execute();
$result = $stmt->get_result();

echo "<p>Resultados encontrados: " . $result->num_rows . "</p>";
while ($row = $result->fetch_assoc()) {
    echo "<pre style='background:#f0f0f0;padding:5px;'>" . print_r($row, true) . "</pre>";
}
?>