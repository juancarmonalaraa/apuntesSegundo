<?php
declare(strict_types=1);
// Cuentas disponibles para el ejercicio. No modificar los hashes.
function obtenerCuentas(): array
{
    return [
        'ana@turnos.test' => [
            'nombre' => 'Ana',
            'hash' => '$2y$12$yW4CabPUmyLdh0Mz3HUmhOxfGnYc0yNqhLjJepbMJoBgp.GGgbxMC'
        ],
        'jose@turnos.test' => [
            'nombre' => 'José',
            'hash' => '$2y$12$Rn3KpTWZiOre0OJ.kXXjs.5Wz4Yr/u1sDGR7T2FCf4OhWKLNSP1uW'
        ]
    ];
}
