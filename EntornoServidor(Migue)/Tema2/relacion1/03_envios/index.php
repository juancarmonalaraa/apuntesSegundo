<?php
declare(strict_types=1);
require_once __DIR__ . '/EnvioNormal.php';
require_once __DIR__ . '/EnvioExpress.php';
$envios = [new EnvioNormal(2), new EnvioExpress(2, 'EX-204')];
foreach ($envios as $envio) {
    echo get_class($envio) . ': ' . number_format($envio->calcularPrecio(), 2, ',', '.') . ' €' . PHP_EOL;
    if ($envio instanceof Rastreable) {
        echo 'Seguimiento: ' . $envio->codigoSeguimiento() . PHP_EOL;
    }
}
try {
    new EnvioNormal(0);
} catch (InvalidArgumentException $e) {
    echo 'Validación: ' . $e->getMessage() . PHP_EOL;
}
