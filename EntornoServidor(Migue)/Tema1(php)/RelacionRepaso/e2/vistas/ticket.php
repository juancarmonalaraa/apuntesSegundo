<!DOCTYPE html>
<html lang="es"><head><meta charset="UTF-8"><title>Ticket del pedido</title></head>
<body>
<h1>Ticket de material electoral</h1>

<table border="1">
    <tr>
        <th>Partido</th>
        <th>Pack</th>
        <th>Unidades</th>
        <th>Extras</th>
        <th>Transporte</th>
    </tr>
    <tr>
        <td><?= escapar($partido) ?></td>
        <td><?= escapar($packs[$pack]['nombre']) ?></td>
        <td><?= escapar($unidades) ?></td>
        <td>
            <?php if (empty($extrasElegidos)): ?>
                Sin extras
            <?php else: ?>
                <?php foreach ($extrasElegidos as $extra): ?>
                    <?= escapar($extras[$extra]['nombre']) ?><br>
                <?php endforeach; ?>
            <?php endif; ?>
        </td>
        <td><?= escapar($transporte) ?></td>
    </tr>
</table>

<table border="1">
    <tr><td>Subtotal (mercancía)</td><td><?= euros($mercancia) ?> €</td></tr>
    <tr><td>Descuento</td><td>- <?= euros($descuento) ?> €</td></tr>
    <tr><td>Transporte</td><td><?= euros($transportes[$transporte]) ?> €</td></tr>
    <tr><td>Base imponible</td><td><?= euros($mercanciaFinalTransporte) ?> €</td></tr>
    <tr><td>IVA (<?= escapar($ivaPorcentaje) ?> %)</td><td><?= euros($ivaTotal) ?> €</td></tr>
    <tr><td><strong>Total</strong></td><td><strong><?= euros($mercanciaFinalFinal) ?> €</strong></td></tr>
</table>

<?php if ($mercanciaValida): ?>

    <p>El pedido está dentro del presupuesto.</p>
<?php else: ?>
    <p style="color: red;">El pedido supera el presupuesto máximo de <?= escapar($presupuestoMaximo) ?> €.</p>
<?php endif; ?>

<p><a href="pedido.html">Volver al formulario</a></p>
</body></html>