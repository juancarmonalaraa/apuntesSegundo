<?php
declare(strict_types=1);
require_once __DIR__ . '/productos.php';

function h(string $valor): string
{
    return htmlspecialchars($valor, ENT_QUOTES, 'UTF-8');
}

$carrito = json_decode($_COOKIE['carrito_demo'] ?? '{}', true);
if (!is_array($carrito)) {
    $carrito = [];
}
$total = 0.0;
foreach ($productos as $id => $producto) {
    $cantidad = filter_var(
        $carrito[$id] ?? 0,
        FILTER_VALIDATE_INT,
        ['options' => ['min_range' => 0, 'max_range' => 20]]
    );
    $total += ($cantidad === false ? 0 : $cantidad) * $producto->getPrecio();
}
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Carrito con cookies</title>
</head>
<body>
    <h1>Carrito con cookies</h1>
    <form action="guardar.php" method="post">
        <?php foreach ($productos as $id => $producto): ?>
            <?php
            $cantidad = filter_var(
                $carrito[$id] ?? 0,
                FILTER_VALIDATE_INT,
                ['options' => ['min_range' => 0, 'max_range' => 20]]
            );
            ?>
            <label>
                <?= h($producto->getNombre()) ?>
                (<?= number_format($producto->getPrecio(), 2, ',', '.') ?> €)
                <input type="number" name="cantidad[<?= h($id) ?>]"
                       min="0" max="20"
                       value="<?= $cantidad === false ? 0 : $cantidad ?>">
            </label>
            <br>
        <?php endforeach; ?>
        <button type="submit">Guardar cantidades</button>
    </form>
    <p>Total: <?= number_format($total, 2, ',', '.') ?> €</p>
    <form action="borrar.php" method="post">
        <button type="submit">Vaciar carrito</button>
    </form>
</body>
</html>
