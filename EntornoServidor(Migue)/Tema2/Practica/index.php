<?php
declare(strict_types=1);

function h(string $valor): string
{
    return htmlspecialchars($valor, ENT_QUOTES, 'UTF-8');
}
$nombre = $_COOKIE['saludo_demo'] ?? '';
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Saludo</title>
</head>
<body>
    <h1>Saludo con cookies</h1>

    <?php if ($nombre !== ''): ?>
        <p>Hola, <?= h($nombre) ?></p>

        <form action="olvidar.php" method="post">
            <button type="submit">Olvidarme</button>
        </form>
    <?php else: ?>
        <form action="guardar.php" method="post">
            <label>
                Tu nombre:
                <input type="text" name="nombre">
            </label>
            <button type="submit">Recordarme</button>
        </form>
    <?php endif; ?>

</body>
</html>