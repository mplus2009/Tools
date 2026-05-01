<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['logueado']) || $_SESSION['logueado'] !== true) {
    header('Location: ../login.php');
    exit();
}

require_once '../db.php';

$usuario_id = $_SESSION['usuario_id'];
$usuario_cargo = $_SESSION['usuario_cargo'];
$usuario_nombre = $_SESSION['usuario_nombre'];
$usuario_apellidos = $_SESSION['usuario_apellidos'];

$catalogo_meritos = [];
$catalogo_demeritos = [];

$result = $conexion->query("SELECT * FROM meritos ORDER BY categoria, causa");
while ($row = $result->fetch_assoc()) {
    $catalogo_meritos[] = $row;
}

$result = $conexion->query("SELECT * FROM demeritos ORDER BY categoria, falta");
while ($row = $result->fetch_assoc()) {
    $catalogo_demeritos[] = $row;
}

$usuarios_escaneados = [];

// ============================================
// ACEPTAR DESTINATARIO DESDE DASHBOARD
// El Dashboard envía: notificar.php?destinatario=ID
// ============================================
if (isset($_GET['destinatario']) && !empty($_GET['destinatario'])) {
    $id_dest = intval($_GET['destinatario']);
    $sql = "SELECT id, nombre, apellidos, ci, grado FROM estudiante WHERE id = ?";
    $stmt = $conexion->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $id_dest);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $usuarios_escaneados[] = $row;
        }
        $stmt->close();
    }
}

// También aceptar múltiples destinatarios desde QR
if (isset($_GET['escaneados']) && !empty($_GET['escaneados'])) {
    $json = base64_decode($_GET['escaneados']);
    $decoded = json_decode($json, true);
    if ($decoded) {
        // Si ya hay destinatarios, agregar sin duplicar
        $ids_existentes = array_column($usuarios_escaneados, 'id');
        foreach ($decoded as $nuevo) {
            if (!in_array($nuevo['id'], $ids_existentes)) {
                $usuarios_escaneados[] = $nuevo;
            }
        }
    }
}

require_once 'notificar_vista.php';
?>