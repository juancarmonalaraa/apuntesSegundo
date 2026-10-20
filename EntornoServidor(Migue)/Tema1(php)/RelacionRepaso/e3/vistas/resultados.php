<!DOCTYPE html>
<html lang="es"><head><meta charset="UTF-8"><title>Resultado del escrutinio</title></head>
<body>
<h1>Resultado ficticio del escrutinio</h1>

<!-- TODO: tabla de partidos por votos (descendente) y porcentaje sobre válidos. -->
<table border="1">
    <tr>
        <th>Partido</th>
        <th>Votos</th>
        <th>% sobre válidos</th>
    </tr>
    <?php foreach ($ordenados as $partido => $numVotos): ?>
        <tr>
            <td><?= $partido ?></td>
            <td><?= $numVotos ?></td>
            <td><?= number_format($porcentajes[$partido], 2) ?> %</td>
        </tr>
    <?php endforeach; ?>
</table>

<!-- TODO: censo, emitidos, válidos, nulos, blancos, abstención y participación. -->
<table border="1">
    <tr><td>Censo</td><td><?= $censo ?></td></tr>
    <tr><td>Votos emitidos</td><td><?= $emitidos ?></td></tr>
    <tr><td>Votos válidos</td><td><?= $validos ?></td></tr>
    <tr><td>Votos nulos</td><td><?= $nulos ?></td></tr>
    <tr><td>Votos en blanco</td><td><?= $blancos ?></td></tr>
    <tr><td>Abstención</td><td><?= $abstencion ?></td></tr>
    <tr><td>Participación</td><td><?= number_format($participacion, 2) ?> %</td></tr>
</table>

<!-- TODO: ganador o empate; número de partidos que alcanzan el 5 %. -->
<?php if ($hayEmpate): ?>
    <p>Hay un empate en el primer puesto.</p>
<?php else: ?>
    <p>Ganador: <?= $ganador ?></p>
<?php endif; ?>

<p>Partidos que alcanzan el <?= $umbralPorcentaje ?> %: <?= $partidosUmbral ?></p>

<p><a href="escrutinio.html">Nuevo recuento</a></p>
</body></html>