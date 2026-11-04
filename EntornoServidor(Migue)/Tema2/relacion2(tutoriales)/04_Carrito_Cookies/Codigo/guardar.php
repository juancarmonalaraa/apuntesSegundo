<?php
declare(strict_types=1);
require_once __DIR__ . '/productos.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit;
}

$datos = $_POST['cantidad'] ?? [];
if (!is_array($datos)) {
    http_response_code(400);
    exit('Las cantidades deben enviarse en un array.');
}

$carrito = [];
foreach ($productos as $id => $producto) {
    $cantidad = filter_var(
        $datos[$id] ?? 0,
        FILTER_VALIDATE_INT,
        ['options' => ['min_range' => 0, 'max_range' => 20]]
    );
    if ($cantidad === false) {
        http_response_code(400);
        exit('Cada cantidad debe ser un entero de 0 a 20.');
    }
    if ($cantidad > 0) {
        $carrito[$id] = $cantidad;
    }
}

setcookie('carrito_demo', json_encode($carrito, JSON_THROW_ON_ERROR), [
    'expires' => time() + 7 * 86400,
    'path' => '/',
    'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
    'httponly' => true,
    'samesite' => 'Lax'
]);
header('Location: index.php');
exit;
