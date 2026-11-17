<?php
declare(strict_types=1);
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/EquipoRepositorio.php';


function h(string $valor): string
{
    return htmlspecialchars($valor, ENT_QUOTES, 'UTF-8');
}
$error = '';
$equipos = [];
try {
    $repo = new EquipoRepositorio(conectar());

    $equipos = $repo->listar();
} catch (InvalidArgumentException $errorValidacion) {
    http_response_code(400);
    $error = $errorValidacion->getMessage();
} catch (PDOException $errorBD) {
    http_response_code(500);
    $error = 'No se pudo completar la operación. Revisa la conexión o los datos duplicados.';
}


// Completa las acciones POST y recupera el listado.
// Utiliza htmlspecialchars() para mostrar datos de la base de datos.
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Catálogo de equipos</title>
</head>

<body>
    <h1>Catálogo de equipos</h1>

    <h2>*LISTADO*</h2>
    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Unidades</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($equipos as $equipo): ?>
                <tr>
                    <td><?= h($equipo['nombre']) ?></td>
                    <td><?= h((string) $equipo['unidades']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h2>Crear usuario</h2>
    <form action="index.php" method="post">
        <input type="hidden" name="accion" value="crear">
        <input type="equipo" name="string" placeholder="Nombre del equipo" required>
        <input type="number" name="unidades" placeholder="Unidades" required>
        <button type="submit">Crear</button>
    </form>

    <h2>Equipos guardados</h2>
    <ul>
        <?php foreach ($equipos as $equipo): ?>
            <li>
                <?= h((string) $equipo['nombre']) ?> —
                <?= h((string) $equipo['unidades']) ?> € —
                <form action="index.php" method="post" style="display:inline">
                    <input type="hidden" name="accion" value="actualizar">

                    <input type="number" name="unidades" min="0" step="0.01" value="<?= h((string) $equipo['unidades']) ?>"
                        required>
                    <button type="submit">Actualizar unidades del producto</button>
                </form>
                <form action="index.php" method="post" style="display:inline">
                    <input type="hidden" name="accion" value="eliminar">

                    <button type="submit">Eliminar</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>

</body>


</html>


<style>
    table {
        border-collapse: collapse;
    }

    th,
    td {
        border: 1px solid black;
        padding: 6px 12px;
    }
</style>


<?php


