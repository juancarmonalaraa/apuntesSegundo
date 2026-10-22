<?php
require_once __DIR__ . '/datosRobo.php';

function euros(float $importe): string
{
    return number_format($importe, 2, ',', '.') . ' €';
}

function prioridad(int $valor): string
{
    if ($valor <= 2) {
        return 'BAJA';
    }
    if ($valor <= 5) {
        return 'MEDIA';
    }
    return 'ALTA';
}

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    http_response_code(405);
    exit('Accede desde el expediente generado.');
}

$nombre = $_POST['nombre'] ?? '';
$barrio = $_POST['barrio'] ?? '';
$modelo = $_POST['modelo'] ?? '';
$antiguedad = filter_var(
    $_POST['antiguedad'] ?? null,
    FILTER_VALIDATE_INT,
    ['options' => ['min_range' => 0, 'max_range' => 5]]
);
$mostradas = $_POST['pistas'] ?? [];
$decisiones = $_POST['decisiones'] ?? [];

if (!is_string($nombre) || trim($nombre) === '' || strlen($nombre) > 120 ||
    !is_string($barrio) || !array_key_exists($barrio, $barrios) ||
    !is_string($modelo) || !array_key_exists($modelo, $modelos) ||
    $antiguedad === false ||
    !is_array($mostradas) || count($mostradas) < 3 || count($mostradas) > 5 ||
    !is_array($decisiones)) {
    http_response_code(400);
    exit('Los datos recibidos no son válidos. <a href="denuncia.html">Volver</a>');
}
$nombre = trim($nombre);

// Ninguna pista repetida ni ajena al catálogo.
$clavesVerificadas = [];
foreach ($mostradas as $clave) {
    if (!is_string($clave) || !array_key_exists($clave, $pistas) ||
        in_array($clave, $clavesVerificadas, true)) {
        http_response_code(400);
        exit('Lista de pistas no válida.');
    }
    $clavesVerificadas[] = $clave;
}

// No se aceptan decisiones sobre pistas que no aparecieron ni opciones inventadas.
foreach ($decisiones as $clave => $decision) {
    if (!in_array($clave, $clavesVerificadas, true) ||
        !is_string($decision) ||
        !in_array($decision, ['investigar', 'descartar'], true)) {
        http_response_code(400);
        exit('Hay decisiones no válidas.');
    }
}

$filas = [];
$coincidencias = 0;
$valorTotal = 0;
$avisoGps = false;

foreach ($clavesVerificadas as $clave) {
    $ficha = $pistas[$clave];
    $esperada = $ficha['fiable'] ? 'investigar' : 'descartar';
    $decision = $decisiones[$clave] ?? '';
    $coincide = ($decision !== '' && $decision === $esperada);
    if ($coincide) {
        $coincidencias++;
    }

    $aportacion = ($ficha['fiable'] && $decision === 'investigar')
        ? $ficha['valor'] : 0;
    $valorTotal += $aportacion;
    if ($clave === 'gps' && $aportacion > 0) {
        $avisoGps = true;
    }

    $filas[] = [
        'texto' => $ficha['descripcion'],
        'decision' => $decision === '' ? 'Sin decidir' : ucfirst($decision),
        'esperada' => ucfirst($esperada),
        'resultado' => $decision === '' ? 'Sin decidir' : ($coincide ? 'Coincide' : 'No coincide'),
        'aportacion' => $aportacion,
    ];
}

$precioOriginal = $modelos[$modelo]['precio'];
$porcentaje = $antiguedad * 10;
$descuento = $precioOriginal * $antiguedad * 0.10;
$valorActual = $precioOriginal - $descuento;
$prioridad = prioridad($valorTotal);
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Operación Cortacandados - Informe de investigación</title>
</head>
<body>
    <h1>Informe de investigación</h1>
    <h2>Datos del expediente</h2>
    <p>Denunciante: <?= htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8') ?></p>
    <p>Barrio: <?= htmlspecialchars($barrios[$barrio], ENT_QUOTES, 'UTF-8') ?></p>
    <p>Bicicleta: <?= htmlspecialchars($modelos[$modelo]['nombre'], ENT_QUOTES, 'UTF-8') ?></p>

    <h2>Valoración económica</h2>
    <ul>
        <li>Precio original: <?= euros($precioOriginal) ?></li>
        <li>Antigüedad: <?= $antiguedad ?> años</li>
        <li>Porcentaje de depreciación: <?= $porcentaje ?> %</li>
        <li>Descuento: <?= euros($descuento) ?></li>
        <li>Valor actual estimado: <?= euros($valorActual) ?></li>
    </ul>

    <h2>Revisión de las pistas</h2>
    <table border="1" cellpadding="5">
        <thead>
            <tr>
                <th>Descripción</th><th>Decisión</th><th>Recomendada</th>
                <th>Resultado</th><th>Aportación</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($filas as $fila): ?>
                <tr>
                    <td><?= htmlspecialchars($fila['texto'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($fila['decision'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($fila['esperada'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($fila['resultado'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= $fila['aportacion'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h2>Conclusiones</h2>
    <p>Decisiones coincidentes: <?= $coincidencias ?> de <?= count($clavesVerificadas) ?></p>
    <p>Valor de investigación: <?= $valorTotal ?></p>
    <p>Prioridad: <strong><?= $prioridad ?></strong></p>
    <?php if ($avisoGps): ?>
        <p><strong>Revisar la ubicación del rastreador GPS</strong></p>
    <?php endif; ?>
    <p><a href="denuncia.html">Nuevo expediente</a></p>
</body>
</html>
