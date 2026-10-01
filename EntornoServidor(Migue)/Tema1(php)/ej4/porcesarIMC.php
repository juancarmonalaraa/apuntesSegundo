<?php
// EJERCICIO 04. Recibe datos desde IMC.html.
// TODO 1: exige POST y comprueba que los cuatro datos existen y son válidos.
// TODO 2: convierte la altura desde centímetros a metros.
// TODO 3: calcula IMC y la estimación didáctica de pulsaciones máximas.
// TODO 4: muestra los resultados con una presentación HTML legible.
// TODO 5: si algún dato falla, no realices cálculos y muestra un aviso.

// Comprobar que el formulario se ha enviado mediante POST.
if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    http_response_code(405);
    exit('Envía el formulario mediante POST.');
}

function mostrar($valor): string {
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

// Poner en mayúscula únicamente la primera letra.
function primeraMayuscula($texto): string {

    $texto = (string) $texto;
    $texto = mb_strtolower($texto, 'UTF-8');
    return ucwords($texto);//convierte a mayuscula la primera letra de cada palabra
}
// Recoger los datos del formulario.
$nombre = trim((string) ($_POST['nombre'] ?? ''));
$edad = (string) ($_POST['edad'] ?? '');
$altura = filter_var($_POST['altura'] ?? null, FILTER_VALIDATE_FLOAT);
$peso = filter_var($_POST['peso'] ?? null, FILTER_VALIDATE_FLOAT);

if (
    $nombre === '' ||
    $edad === '' ||
    $altura === false ||
    $altura < 50 ||
    $altura > 300 ||
    $peso === false ||
    $peso < 20 ||
    $peso > 500
) {
    exit('Faltan datos o existe algún valor no válido en el formulario.');
}
// Convertir la altura de centímetros a metros.
$alturaMetros = $altura / 100;

//calcular el IMC
$imc = $peso / ($alturaMetros * $alturaMetros);

//Calcular la estimación didáctica de pulsaciones máximas
$pulsacionesMaximas = 220 - (int)$edad;


// Mostrar el resultado.
echo '<!doctype html>';
echo '<html lang="es">';
echo '<head>';
echo '<meta charset="utf-8">';
echo '<title>Datos personales</title>';
echo '</head>';
echo '<body>';

echo '<h1>'
    . mostrar(primeraMayuscula($nombre))
    . '</h1>';

echo '<p>Edad: ' . mostrar($edad) . '</p>';
echo '<p>Peso: ' . mostrar($peso) . ' kg</p>';

echo '<p>Altura: ' . mostrar($alturaMetros) . ' cm</p>';

echo '<p>IMC: ' . mostrar(number_format($imc, 2))
    . '</p>';

    echo '<p>Pulsaciones máximas estimadas: ' . mostrar($pulsacionesMaximas) . '</p>';


echo '</body>';
echo '</html>';
