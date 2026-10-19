<!DOCTYPE html>
<html lang="es"><head><meta charset="UTF-8"><title>Resultados de agenda</title></head>
<body>
    <h1>Actos encontrados</h1>

    <p>Filtros: <?= $provincia ?> / <?= $partido ?> / <?= $tipo ?></p>
    <p>Total: <?= $total ?> actos, aforo acumulado: <?= $aforoTotal ?></p>

    <?php if ($total === 0): ?>
        <p>La búsqueda no tiene resultados.</p>
    <?php else: ?>
        <table border="1">
            <tr>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Provincia</th>
                <th>Partido</th>
                <th>Tipo</th>
                <th>Lugar</th>
                <th>Aforo</th>
            </tr>
            <?php foreach ($visibles as $acto): ?>
                <tr>
                    <td><?= $acto['fecha'] ?></td>
                    <td><?= $acto['hora'] ?></td>
                    <td><?= $acto['provincia'] ?></td>
                    <td><?= $acto['partido'] ?></td>
                    <td><?= $acto['tipo'] ?></td>
                    <td><?= $acto['lugar'] ?></td>
                    <td><?= $acto['aforo'] ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

    <p><a href="agenda.html">Nueva búsqueda</a></p>
</body></html>