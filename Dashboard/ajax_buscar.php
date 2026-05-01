<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once '../db.php';

header('Content-Type: application/json');

$query = $_GET['q'] ?? '';

if (strlen($query) < 2) {
    echo json_encode([]);
    exit();
}

$search = "%$query%";
$sql = "SELECT id, nombre, apellidos, ci, grado FROM estudiante WHERE nombre LIKE ? OR apellidos LIKE ? OR ci LIKE ? LIMIT 10";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("sss", $search, $search, $search);
$stmt->execute();
$result = $stmt->get_result();

$response = [];
while ($row = $result->fetch_assoc()) {
    $response[] = $row;
}
$stmt->close();

echo json_encode($response);
?>