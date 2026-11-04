<?php
declare(strict_types=1);
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/ProductoRepositorio.php';

function h(string $valor): string
{
    return htmlspecialchars($valor, ENT_QUOTES, 'UTF-8');
}

$error = '';
$productos = [];
try {
    $repo = new ProductoRepositorio(conectar());

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $accion = (string) ($_POST['accion'] ?? '');
        $id = filter_var(
            $_POST['id'] ?? null,
            FILTER_VALIDATE_INT,
            ['options' => ['min_range' => 1]]
        );
        if ($accion === 'crear') {
            $nombre = trim((string) ($_POST['nombre'] ?? ''));
            $precio = filter_var($_POST['precio'] ?? null, FILTER_VALIDATE_FLOAT);
            $stock = filter_var(
                $_POST['stock'] ?? null,
                FILTER_VALIDATE_INT,
                ['options' => ['min_range' => 0]]
            );
            if ($precio === false || $stock === false) {
                throw new InvalidArgumentException('Precio o stock no válido.');
            }
            $repo->crear($nombre, $precio, $stock);
        } elseif ($accion === 'actualizar') {
            $precio = filter_var($_POST['precio'] ?? null, FILTER_VALIDATE_FLOAT);
            if ($id === false || $precio === false) {
                throw new InvalidArgumentException('Id o precio no válido.');
            }
            $repo->actualizarPrecio($id, $precio);
        } elseif ($accion === 'eliminar') {
            if ($id === false) {
                throw new InvalidArgumentException('Id no válido.');
            }
            $repo->eliminar($id);
        } else {
            throw new InvalidArgumentException('Acción no reconocida.');
        }
        header('Location: index.php');
        exit;
    }
    $productos = $repo->todos();
} catch (InvalidArgumentException $errorValidacion) {
    http_response_code(400);
    $error = $errorValidacion->getMessage();
} catch (PDOException $errorBD) {
    http_response_code(500);
    $error = 'Error de base de datos. Revisa la conexión y el servidor.';
}
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Catálogo de productos</title>
</head>
<body>
    <h1>Catálogo de productos</h1>
    <?php if ($error !== ''): ?>
        <p><?= h($error) ?></p>
    <?php endif; ?>

    <h2>Nuevo producto</h2>
    <form action="index.php" method="post">
        <input type="hidden" name="accion" value="crear">
        <label>Nombre <input name="nombre" maxlength="120" required></label>
        <label>Precio <input name="precio" type="number" step="0.01" min="0" required></label>
        <label>Stock <input name="stock" type="number" step="1" min="0" required></label>
        <button type="submit">Crear</button>
    </form>

    <h2>Productos guardados</h2>
    <ul>
        <?php foreach ($productos as $producto): ?>
            <li>
                <?= h((string) $producto['nombre']) ?> —
                <?= h((string) $producto['precio']) ?> € —
                <?= (int) $producto['stock'] ?> unidades
                <form action="index.php" method="post" style="display:inline">
                    <input type="hidden" name="accion" value="actualizar">
                    <input type="hidden" name="id" value="<?= (int) $producto['id'] ?>">
                    <input type="number" name="precio" min="0" step="0.01"
                           value="<?= h((string) $producto['precio']) ?>" required>
                    <button type="submit">Actualizar precio</button>
                </form>
                <form action="index.php" method="post" style="display:inline">
                    <input type="hidden" name="accion" value="eliminar">
                    <input type="hidden" name="id" value="<?= (int) $producto['id'] ?>">
                    <button type="submit">Eliminar</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
