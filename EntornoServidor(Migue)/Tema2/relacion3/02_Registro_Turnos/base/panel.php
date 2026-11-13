<?php

declare(strict_types=1);
require_once __DIR__ . '/sesion.php';
exigirSesion();

$movimientos = $_SESSION['movimientos'] ?? [];

function e(string $v): string
{
    return htmlspecialchars($v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Mi turno</title>
</head>

<body>
    <h1>Registro de turnos</h1>
    <p>Hola, <?= e((string)$_SESSION['nombre']) ?>.</p>
    <table border="1">
        <tr>
            <th>Tipo</th>
            <th>Fecha y hora</th>
        </tr>
        <?php foreach ($movimientos as $m): ?>
            <tr>
                <td><?= $m['tipo'] === 'entrada' ? 'Entrada' : 'Salida' ?></td>
                <td><?= e(date('d/m/Y H:i:s', (int)$m['momento'])) ?></td>
            </tr>
        <?php endforeach; ?>
        <?php if ($movimientos === []): ?>
            <tr><td colspan="2">Aún no hay movimientos.</td></tr>
        <?php endif; ?>
    </table>
    <form action="registrar.php" method="post"><button name="tipo" value="entrada">Entrada</button></form>
    <form action="registrar.php" method="post"><button name="tipo" value="salida">Salida</button></form>
    <form action="salir.php" method="post"><button>Salir</button></form>
</body>

</html>