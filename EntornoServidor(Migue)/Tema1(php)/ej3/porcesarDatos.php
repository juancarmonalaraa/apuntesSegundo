<?php
// EJERCICIO 03. Los datos llegan desde radioCheckbox.html por POST.
// TODO 1: comprueba el método de la petición y recoge los campos del formulario.
// TODO 2: valida los datos obligatorios y que las opciones recibidas estén permitidas.
// TODO 3: muestra nombre y apellidos en un <h1> y el resto de campos en párrafos.
// TODO 4: recorre las aficiones y muéstralas en una lista <ul>.
// TODO 5: contempla el caso de no haber seleccionado ninguna afición.
// Nota: al imprimir texto enviado por el usuario, escápalo para HTML.¡

// Comprobar que el formulario se ha enviado mediante POST.
if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    http_response_code(405);
    exit('Envía el formulario mediante POST.');
}

// Escapar el contenido antes de mostrarlo en HTML.
function mostrar($valor): string {
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

// Poner en mayúscula únicamente la primera letra.
function primeraMayuscula($texto): string {

//AlFonso marTIN => Alfonso Martin
    $texto = (string) $texto;
    $texto = mb_strtolower($texto, 'UTF-8');
    return ucwords($texto);//convierte a mayuscula la primera letra de cada palabra
}


// Recoger los datos del formulario.
$nombre = trim((string) ($_POST['nombre'] ?? ''));
$apellidos = trim((string) ($_POST['apellidos'] ?? ''));
$edad = (string) ($_POST['edad'] ?? '');
$peso = filter_var($_POST['peso'] ?? null, FILTER_VALIDATE_FLOAT);
$sexo = (string) ($_POST['sexo'] ?? '');
$estadoCivil = (string) ($_POST['estado-civil'] ?? '');
$aficiones = $_POST['aficiones'] ?? [];


// Valores permitidos.
$edades = [
    'Menos de 20 años',
    'Entre 20 y 39 años',
    'Entre 40 y 59 años',
    '60 años o más'
];

$sexos = [
    'masculino',
    'femenino'
];

$estados = [
    'soltero',
    'casado',
    'otro'
];

$aficionesValidas = [
    'cine',
    'literatura',
    'tebeos',
    'deporte',
    'musica',
    'television'
];


// Validar los datos.
if (
    $nombre === '' ||
    $apellidos === '' ||
    preg_match_all('/./us', $nombre) > 20 ||
    preg_match_all('/./us', $apellidos) > 20 ||
    !in_array($edad, $edades, true) ||
    $peso === false ||
    $peso < 20 ||
    $peso > 500 ||
    !in_array($sexo, $sexos, true) ||
    !in_array($estadoCivil, $estados, true) ||
    !is_array($aficiones)
) {
    exit('Faltan datos o existe algún valor no válido en el formulario.');
}


// Validar cada afición recibida.
foreach ($aficiones as $aficion) {
    if (
        !is_string($aficion) ||
        !in_array($aficion, $aficionesValidas, true)
    ) {
        exit('Se ha recibido una afición no válida.');
    }
}


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
    . ' '
    . mostrar(primeraMayuscula($apellidos))
    . '</h1>';

echo '<p>Edad: ' . mostrar($edad) . '</p>';
echo '<p>Peso: ' . mostrar($peso) . ' kg</p>';

echo '<p>Sexo: '
    . mostrar(primeraMayuscula($sexo))
    . '</p>';

echo '<p>Estado civil: '
    . mostrar(primeraMayuscula($estadoCivil))
    . '</p>';

echo '<h2>Aficiones:</h2>';
echo '<ul>';

foreach ($aficiones as $aficion) {
    echo '<li>'
        . mostrar(primeraMayuscula($aficion))
        . '</li>';
}

if (!$aficiones) {
    echo '<li>Ninguna seleccionada</li>';
}

echo '</ul>';
echo '</body>';
echo '</html>';

echo 'Pendiente de implementar el ejercicio 03.';
