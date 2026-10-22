<?php
require_once __DIR__ . '/datosRobo.php';

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    http_response_code(405);
    exit('Debes utilizar el formulario inicial.');
}

$nombre = $_POST['nombre'] ?? '';
$barrio = $_POST['barrio'] ?? '';
$modelo = $_POST['modelo'] ?? '';
$antiguedad = filter_var(
    $_POST['antiguedad'] ?? null,
    FILTER_VALIDATE_INT,
    ['options' => ['min_range' => 0, 'max_range' => 5]]
);
$cantidad = filter_var(
    $_POST['cantidad'] ?? null,
    FILTER_VALIDATE_INT,
    ['options' => ['min_range' => 3, 'max_range' => 5]]
);

if (
    !is_string($nombre) || trim($nombre) === '' || strlen($nombre) > 120 ||
    !is_string($barrio) || !array_key_exists($barrio, $barrios) ||
    !is_string($modelo) || !array_key_exists($modelo, $modelos) ||
    $antiguedad === false || $cantidad === false
) {
    http_response_code(400);
    exit('Datos del expediente incorrectos. <a href="denuncia.html">Volver</a>');
}
$nombre = trim($nombre);

// array_rand devuelve las claves; el cast mantiene un array para una o varias.
$seleccionadas = (array) array_rand($pistas, $cantidad);
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Operación Cortacandados - Pistas del expediente</title>
</head>

<body>
    <h1>Operación Cortacandados</h1>
    <h2>Expediente generado</h2>
    <p>Denunciante: <?= htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8') ?></p>
    <p>Barrio: <?= htmlspecialchars($barrios[$barrio], ENT_QUOTES, 'UTF-8') ?></p>
    <p>Bicicleta: <?= htmlspecialchars($modelos[$modelo]['nombre'], ENT_QUOTES, 'UTF-8') ?></p>
    <p>Antigüedad: <?= $antiguedad ?> años</p>
    <p>Revisa las siguientes <?= count($seleccionadas) ?> pistas. Puedes dejar alguna sin decidir.</p>

    <form action="analizarExpediente.php" method="post">
        <input type="hidden" name="nombre" value="<?= htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8') ?>">
        <input type="hidden" name="barrio" value="<?= htmlspecialchars($barrio, ENT_QUOTES, 'UTF-8') ?>">
        <input type="hidden" name="modelo" value="<?= htmlspecialchars($modelo, ENT_QUOTES, 'UTF-8') ?>">
        <input type="hidden" name="antiguedad" value="<?= $antiguedad ?>">

        <?php foreach ($seleccionadas as $clave): ?>
            <section>
                <h3>Pista: <?= htmlspecialchars($clave, ENT_QUOTES, 'UTF-8') ?></h3>
                <p><?= htmlspecialchars($pistas[$clave]['descripcion'], ENT_QUOTES, 'UTF-8') ?></p>
                <input type="hidden" name="pistas[]" value="<?= htmlspecialchars($clave, ENT_QUOTES, 'UTF-8') ?>">
                <label>
                    <input type="radio" name="decisiones[<?= htmlspecialchars($clave, ENT_QUOTES, 'UTF-8') ?>]"
                        value="investigar">
                    Investigar
                </label>
                <label>
                    <input type="radio" name="decisiones[<?= htmlspecialchars($clave, ENT_QUOTES, 'UTF-8') ?>]"
                        value="descartar">
                    Descartar
                </label>
            </section>
            <hr>
        <?php endforeach; ?>
        <button type="submit">Analizar expediente</button>
    </form>
</body>

</html>