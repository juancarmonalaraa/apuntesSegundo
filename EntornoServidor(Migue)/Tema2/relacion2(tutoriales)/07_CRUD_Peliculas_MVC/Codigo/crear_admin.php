<?php
declare(strict_types=1);

// Ejecutar solo desde terminal en el laboratorio: php crear_admin.php
if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit;
}
require_once __DIR__ . '/config.php';

$email = 'admin@cine.test';
$clave = 'Admin2026!';
$sql = 'INSERT INTO usuario(nombre, email, hash, rol) VALUES (?, ?, ?, ?) '
    . 'ON DUPLICATE KEY UPDATE hash = VALUES(hash), rol = VALUES(rol)';
$sentencia = conectar()->prepare($sql);
$sentencia->execute([
    'Administrador', $email, password_hash($clave, PASSWORD_DEFAULT), 'ADMIN'
]);
echo "Cuenta de prueba: $email / $clave" . PHP_EOL;
