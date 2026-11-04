<?php
declare(strict_types=1);
require_once __DIR__ . '/Perro.php';

$perros = [
    new Perro(),
    new Perro('Roco', 4, 'Border Collie', 'Macho')
];

foreach ($perros as $perro) {
    echo htmlspecialchars((string) $perro, ENT_QUOTES, 'UTF-8') . '<br>';
    echo htmlspecialchars($perro->ladrar(), ENT_QUOTES, 'UTF-8') . '<br>';
}

echo '<hr>';
echo 'Roco cumple años: ' . $perros[1]->cumplirAnios() . '<br>';
echo htmlspecialchars((string) $perros[1], ENT_QUOTES, 'UTF-8') . '<br>';

try {
    new Perro('Error', -2);
} catch (InvalidArgumentException $error) {
    echo htmlspecialchars($error->getMessage(), ENT_QUOTES, 'UTF-8');
}
