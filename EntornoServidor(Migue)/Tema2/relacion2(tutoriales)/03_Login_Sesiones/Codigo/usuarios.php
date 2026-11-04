<?php
declare(strict_types=1);

function obtenerUsuariosDemo(): array
{
    // Solo para aprendizaje local: en una aplicación real los hashes están almacenados.
    return [
        'ana@club.test' => [
            'nombre' => 'Ana',
            'hash' => password_hash('claveAna123', PASSWORD_DEFAULT)
        ],
        'luis@club.test' => [
            'nombre' => 'Luis',
            'hash' => password_hash('claveLuis123', PASSWORD_DEFAULT)
        ]
    ];
}

function autenticar(string $email, string $clave): ?string
{
    $cuentas = obtenerUsuariosDemo();
    $email = strtolower(trim($email));
    if (!isset($cuentas[$email])) {
        return null;
    }
    if (!password_verify($clave, $cuentas[$email]['hash'])) {
        return null;
    }
    return $cuentas[$email]['nombre'];
}
