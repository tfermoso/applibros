<?php
session_start();

$dataDir = __DIR__ . DIRECTORY_SEPARATOR . 'data';
$currentPath = $dataDir . DIRECTORY_SEPARATOR . 'current.json';

if (!file_exists($currentPath)) {
    $_SESSION['flash'] = 'Error: no existe data/current.json en el servidor.';
    header('Location: index.php');
    exit();
}

$contents = file_get_contents($currentPath);
$data = json_decode($contents, true);
if (!is_array($data)) {
    $_SESSION['flash'] = 'Error: JSON inválido en current.json.';
    header('Location: index.php');
    exit();
}

// validar estructura mínima
$miembros = $data['miembros'] ?? null;
$libros = $data['libros'] ?? null;
if (!is_array($miembros) || !is_array($libros)) {
    $_SESSION['flash'] = 'Error: current.json debe contener "miembros" y "libros".';
    header('Location: index.php');
    exit();
}

// Reemplazar sesión con datos del server
$_SESSION['miembros'] = $miembros;
$_SESSION['libros']   = $libros;

$_SESSION['flash'] = 'Datos importados desde data/current.json';
header('Location: index.php');
exit();