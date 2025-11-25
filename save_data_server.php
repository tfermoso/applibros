<?php
session_start();

// Recolectar datos que queremos exportar
$data = [
    'miembros' => $_SESSION['miembros'] ?? [],
    'libros' => $_SESSION['libros'] ?? []
];

$json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
if ($json === false) {
    $_SESSION['flash'] = 'Error: no se pudo generar JSON.';
    header('Location: index.php');
    exit();
}

$dataDir = __DIR__ . DIRECTORY_SEPARATOR . 'data';
if (!is_dir($dataDir)) {
    mkdir($dataDir, 0755, true);
}

$now = (new DateTime())->format('Ymd_His');
$baseName = 'current.json';
$backupName = 'export_' . $now . '.json';

$saveCurrent = $dataDir . DIRECTORY_SEPARATOR . $baseName;
$saveBackup = $dataDir . DIRECTORY_SEPARATOR . $backupName;

if (file_put_contents($saveCurrent, $json) === false) {
    $_SESSION['flash'] = 'Error: no se pudo guardar current.json en el servidor.';
    header('Location: index.php');
    exit();
}
file_put_contents($saveBackup, $json); // no comprobación obligatoria; se intenta crear backup

$_SESSION['flash'] = 'Datos guardados en servidor: ' . htmlspecialchars($baseName) . ' (backup: ' . htmlspecialchars($backupName) . ')';
header('Location: index.php');
exit();