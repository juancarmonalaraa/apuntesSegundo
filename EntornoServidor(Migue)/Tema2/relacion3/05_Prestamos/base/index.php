<?php
declare(strict_types=1);
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/EquipoRepositorio.php';

$error = '';
$equipos = [];
$prestamos = [];

try {
    $repo = new EquipoRepositorio(conectar());

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $accion = $_POST['accion'] ?? '';

        try {
            if ($accion === 'prestar') {
                $equipoId = filter_var($_POST['equipo_id'] ?? '', FILTER_VALIDATE_INT);
                $persona = trim($_POST['persona'] ?? '');

                if ($equipoId === false) {
                    $error = 'Equipo no válido.';
                } elseif ($persona === '' || mb_strlen($persona) > 80) {
                    $error = 'Introduce un nombre válido (máximo 80 caracteres).';
                } else {
                    $repo->prestar($equipoId, $persona);
                    header('Location: index.php');
                    exit;
                }
            } elseif ($accion === 'devolver') {
                $prestamoId = filter_var($_POST['prestamo_id'] ?? '', FILTER_VALIDATE_INT);

                if ($prestamoId === false) {
                    $error = 'Préstamo no válido.';
                } elseif ($repo->devolver($prestamoId)) {
                    header('Location: index.php');
                    exit;
                } else {
                    $error = 'El préstamo no existe o ya estaba devuelto.';
                }
            }
        } catch (DomainException $e) {
            $error = $e->getMessage(); // mensajes propios, p. ej. "No quedan unidades disponibles."
        }
    }

    $equipos = $repo->listarEquipos();
    $prestamos = $repo->listarPrestamos();
} catch (PDOException $e) {
    $error = $e->getMessage();
    }
?>
<!doctype html>
<html lang="es">
<head><meta charset="utf-8"><title>Préstamos de equipos</title></head>
<body>
<h1>Préstamos de equipos</h1>

<?php if ($error !== ''): ?>
    <p style="color:red"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<h2>Equipos</h2>
<table border="1" cellpadding="5">
    <tr><th>Equipo</th><th>Unidades</th><th>Disponibles</th></tr>
    <?php foreach ($equipos as $equipo): ?>
        <tr>
            <td><?= htmlspecialchars($equipo['nombre']) ?></td>
            <td><?= (int) $equipo['unidades'] ?></td>
            <td><?= (int) $equipo['disponibles'] ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<h2>Prestar un equipo</h2>
<form method="post">
    <input type="hidden" name="accion" value="prestar">
    <label>Equipo:
        <select name="equipo_id">
            <?php foreach ($equipos as $e): ?>
                <option value="<?= (int) $e['id'] ?>"><?= htmlspecialchars($e['nombre']) ?></option>
            <?php endforeach; ?>
        </select>
    </label>
    <label>Persona: <input type="text" name="persona"></label>
    <button type="submit">Prestar</button>
</form>

<h2>Préstamos</h2>
<table border="1" cellpadding="5">
    <tr><th>ID</th><th>Equipo</th><th>Persona</th><th>Estado</th><th></th></tr>
    <?php foreach ($prestamos as $p): ?>
        <tr>
            <td><?= (int) $p['id'] ?></td>
            <td><?= htmlspecialchars($p['equipo']) ?></td>
            <td><?= htmlspecialchars($p['persona']) ?></td>
            <td><?= $p['devuelto'] ? 'Devuelto' : 'Abierto' ?></td>
            <td>
                <?php if (!$p['devuelto']): ?>
                    <form method="post">
                        <input type="hidden" name="accion" value="devolver">
                        <input type="hidden" name="prestamo_id" value="<?= (int) $p['id'] ?>">
                        <button type="submit">Devolver</button>
                    </form>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
</body>
</html>