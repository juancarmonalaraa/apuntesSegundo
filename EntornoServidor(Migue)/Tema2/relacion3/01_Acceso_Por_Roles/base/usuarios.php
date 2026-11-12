<?php
declare(strict_types=1);
// Cuentas disponibles para el ejercicio. No modificar los hashes.
function obtenerUsuarios(): array
{
    return [
        'alba@centro.test' => [
            'nombre' => 'Alba',
            'perfil' => 'coordinacion',
            'hash' => '$2y$12$6e8iI6DnlSALXZudEdv0x.wFUwiUQ7q.vo6mB3G1iSNhQ/x5G7oUu'
        ],
        'marcos@centro.test' => [
            'nombre' => 'Marcos',
            'perfil' => 'tecnico',
            'hash' => '$2y$12$8LbrFwZ./rd1yXjgC2fONOOBlHDs15Yr2JR2.MN6278taBBEN/c7.'
        ]
    ];
}
