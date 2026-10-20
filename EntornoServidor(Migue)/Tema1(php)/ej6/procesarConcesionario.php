<?php
require_once __DIR__ . '/componentes.php'; // Datos iniciales de opciones, precios y descuentos.

// EJERCICIO 06.
// TODO 1: comprueba el método POST y valida las cinco opciones obligatorias.
if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    http_response_code(405);
    exit('Envía el formulario mediante POST.');
}
// TODO 2: recoge los accesorios seleccionados (pueden ser cero) y la cantidad (1–5).
// TODO 3: calcula el precio unitario SIN IVA a partir de los precios proporcionados.
// TODO 4: multiplica por el número de vehículos y aplica el descuento, si es válido.
// TODO 5: calcula un IVA del 21 % sobre la base una vez descontada la rebaja.
// TODO 6: genera un resumen con opciones, importes, descuentos, IVA y total.
// Si el código de descuento no existe, indica que es inválido y no apliques rebaja.
<?php
require_once __DIR__ . '/componentes.php';
if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    http_response_code(405);
    exit('Envía el formulario mediante POST.');
}
function mostrar($texto): string {
    return htmlspecialchars((string) $texto, ENT_QUOTES, 'UTF-8');
}
$obligatorios = ['Modelo', 'Motor', 'Color', 'Llantas', 'Equipamiento'];
$seleccion = [];
foreach ($obligatorios as $grupo) {
    $valor = $_POST[$grupo] ?? null;
    if (!is_string($valor) || !array_key_exists($valor, $componentes[$grupo])) {
        exit('Debes seleccionar una opción válida de ' . mostrar($grupo) . '.');
    }
    $seleccion[$grupo] = $valor;
}
$accesorios = $_POST['Accesorios'] ?? [];
if (!is_array($accesorios)) {
    exit('La selección de accesorios no es válida.');
}
foreach ($accesorios as $accesorio) {
    if (!is_string($accesorio) || !array_key_exists($accesorio, $componentes['Accesorios'])) {
        exit('Has seleccionado un accesorio no válido.');
    }
}
$accesorios = array_unique($accesorios);
$cantidad = filter_var($_POST['cantidad'] ?? null, FILTER_VALIDATE_INT,
    ['options' => ['min_range' => 1, 'max_range' => 5]]);
if ($cantidad === false) {
    exit('La cantidad debe ser un número entero entre 1 y 5.');
}
$codigo = strtoupper(trim((string) ($_POST['codigo_descuento'] ?? '')));
$descuentoPorcentaje = $codigosDescuento[$codigo] ?? 0;
$precioUnidad = 0;
echo '<!doctype html><html lang="es"><meta charset="utf-8"><title>Tu coche</title><body>';
echo '<h1>Resumen de tu coche personalizado</h1><ul>';
foreach ($seleccion as $grupo => $valor) {
    $precio = $componentes[$grupo][$valor];
    $precioUnidad += $precio;
    echo '<li>' . mostrar($grupo) . ': ' . mostrar($valor) . ' — ' . $precio . ' €</li>';
}
if (!$accesorios) { echo '<li>Accesorios: ninguno</li>'; }
foreach ($accesorios as $accesorio) {
    $precio = $componentes['Accesorios'][$accesorio];
    $precioUnidad += $precio;
    echo '<li>Accesorio: ' . mostrar($accesorio) . ' — ' . $precio . ' €</li>';
}
echo '</ul>';
$baseInicial = $precioUnidad * $cantidad;
$rebaja = round($baseInicial * $descuentoPorcentaje / 100, 2);
$baseFinal = $baseInicial - $rebaja;
$iva = round($baseFinal * .21, 2);
$totalConIva = $baseFinal + $iva;
function euros($importe): string { return number_format($importe, 2, ',', '.') . ' €'; }
echo '<p>Cantidad: ' . $cantidad . '</p>';
echo '<p>Precio por unidad sin IVA: ' . euros($precioUnidad) . '</p>';
echo '<p>Base inicial sin IVA: ' . euros($baseInicial) . '</p>';
if ($codigo !== '') {
    echo '<p>Código de descuento: ' . mostrar($codigo) .
         ($descuentoPorcentaje ? ' (válido)' : ' (no válido)') . '</p>';
} else {
    echo '<p>Código de descuento: no aplicado</p>';
}
echo '<p>Descuento aplicado: ' . euros($rebaja) . '</p>';
echo '<p>Base tras el descuento: ' . euros($baseFinal) . '</p>';
echo '<p>IVA (21 %): ' . euros($iva) . '</p>';
echo '<h2>Total con IVA: ' . euros($totalConIva) . '</h2>';
echo '</body></html>';

echo 'Pendiente de implementar el ejercicio 06.';
