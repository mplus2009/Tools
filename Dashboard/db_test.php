<?php
echo "Probando rutas:<br>";

// Prueba 1
if (file_exists('db.php')) {
    echo "Opción 1 (db.php): ENCONTRADO<br>";
} else {
    echo "Opción 1 (db.php): NO ENCONTRADO<br>";
}

// Prueba 2
if (file_exists('../db.php')) {
    echo "Opción 2 (../db.php): ENCONTRADO<br>";
} else {
    echo "Opción 2 (../db.php): NO ENCONTRADO<br>";
}

// Prueba 3
if (file_exists('../../db.php')) {
    echo "Opción 3 (../../db.php): ENCONTRADO<br>";
} else {
    echo "Opción 3 (../../db.php): NO ENCONTRADO<br>";
}

// Prueba 4 - buscar db.php desde la raíz
echo "<br>Buscando db.php...<br>";
$root = $_SERVER['DOCUMENT_ROOT'];
echo "Raíz del servidor: " . $root . "<br>";

if (file_exists($root . '/db.php')) {
    echo "En raíz: ENCONTRADO<br>";
} elseif (file_exists($root . '/Dashboard/db.php')) {
    echo "En Dashboard/: ENCONTRADO<br>";
} else {
    echo "No encontrado en rutas comunes<br>";
}
?>