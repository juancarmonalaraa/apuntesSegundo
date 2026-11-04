<?php
declare(strict_types=1);
require_once __DIR__ . '/Vehiculo.php';

$coche = new Vehiculo('Gasolina', 'C', 4);
$coche->getPuerta(0)->abrir();
$coche->getPuerta(0)->getVentana()->abrir();

echo '<h2>Antes de cerrar</h2>';
echo htmlspecialchars((string) $coche, ENT_QUOTES, 'UTF-8') . '<br>';
echo 'Segunda ventana abierta: '
    . ($coche->getPuerta(1)->getVentana()->estaAbierta() ? 'sí' : 'no') . '<br>';

$coche->cerrarVehiculo();
echo '<h2>Después de cerrar</h2>';
echo htmlspecialchars((string) $coche, ENT_QUOTES, 'UTF-8') . '<br>';

echo '<p>Motor encendido: ' . ($coche->encenderApagar() ? 'sí' : 'no') . '</p>';
// Comprobación inventada para practicar arrays; no reproduce la normativa de ZBE.
echo '<p>Etiqueta aceptada en este ejemplo: '
    . ($coche->puedeEntrarZBE(['0', 'ECO', 'C']) ? 'sí' : 'no') . '</p>';

try {
    $coche->getPuerta(99);
} catch (OutOfBoundsException $error) {
    echo '<p>' . htmlspecialchars($error->getMessage(), ENT_QUOTES, 'UTF-8') . '</p>';
}
