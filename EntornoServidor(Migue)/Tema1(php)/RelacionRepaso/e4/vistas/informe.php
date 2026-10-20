<!DOCTYPE html>
<html lang="es"><head><meta charset="UTF-8"><title>Informe del pacto</title></head>
<body>
<h1>Informe del pacto</h1>

<?php // Datos del pacto elegido: escenario, socios y escaños ?>
<p>Escenario: <?= $escenarios[$escenario]['nombre'] ?></p>
<p>Socios: <?= implode(', ', $sociosElegidos) ?></p>
<p>Escaños del pacto: <?= $escanosPacto ?> (mayoría absoluta: <?= $mayoriaAbsoluta ?>)</p>

<?php // TODO 4: mayoría sí/no, y cuántos escaños sobran o faltan ?>
<?php if ($hayMayoria): ?>
    <p>El pacto tiene mayoría y sobran <?= $diferencia ?> escaños.</p>
<?php else: ?>
    <?php // $diferencia es negativa si faltan escaños; abs() la muestra sin el signo ?>
    <p>El pacto no tiene mayoría y le faltan <?= abs($diferencia) ?> escaños.</p>
<?php endif; ?>

<?php // TODO 5: coalición con cada partido que NO forma parte del pacto ?>
<h2>Si se uniera otro partido</h2>
<table border="1">
    <tr><th>Partido</th><th>Escaños</th><th>¿Mayoría?</th></tr>
    <?php // $coaliciones tiene el formato 'Podemos' => 22 (nombre => escaños de la coalición) ?>
    <?php foreach ($coaliciones as $partido => $totalCoalicion): ?>
        <tr>
            <td><?= $partido ?></td>
            <td><?= $totalCoalicion ?></td>
            <?php // Si la coalición llega a la mayoría absoluta pone Sí, y si no, No ?>
            <td><?= $totalCoalicion >= $mayoriaAbsoluta ? 'Sí' : 'No' ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<?php // TODO 6: socio adicional que llega a la mayoría aportando menos escaños ?>
<h2>Socio adicional</h2>
<?php if ($hayMayoria): ?>
    <?php // Si el pacto ya tiene mayoría, no hace falta nadie más ?>
    <p>No se necesita socio adicional.</p>
<?php elseif ($mejorSocio !== null): ?>
    <?php // Había al menos un partido que llegaba a la mayoría y este es el que aporta menos ?>
    <p><?= $mejorSocio ?> es el que menos escaños aporta para llegar a la mayoría.</p>
<?php else: ?>
    <?php // $mejorSocio sigue en null: ningún partido por sí solo consigue la mayoría ?>
    <p>Ningún partido por sí solo permite llegar a la mayoría.</p>
<?php endif; ?>

<p><a href="pactometro.html">Volver al formulario</a></p>
</body></html>