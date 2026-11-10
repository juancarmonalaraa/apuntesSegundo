<?php
declare(strict_types=1);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit;
}

$nombre = trim($_POST['nombre'] ?? '');

if (mb_strlen($nombre) < 2 || mb_strlen($nombre) > 30) {
    http_response_code(400);
    exit('El nombre debe tener entre 2 y 30 caracteres.');
}

setcookie('saludo_demo', $nombre, [
    'expires'  => 3 * 86400 + time(),
    'path'     => '/',
    'secure'   => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
    'httponly' => true,
    'samesite' => 'Lax'
]);

header('Location: index.php');
exit;