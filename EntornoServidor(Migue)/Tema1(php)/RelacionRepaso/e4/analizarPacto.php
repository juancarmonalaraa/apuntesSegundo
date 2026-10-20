<?php
require_once __DIR__ . '/datosEscenarios.php';
require_once __DIR__ . '/funciones.php';

// TODO 1: exigir POST, validar escenario y socios[] (array de 1 a 3 partidos).
if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    http_response_code(405);
    exit('Envía el formulario del pactómetro');
}
$escenario = $_POST['escenario'] ?? null;
$sociosElegidos = $_POST['socios'] ?? [];


if (!is_array($sociosElegidos)) {
    exit('Socios no válidos.');
}

if (!is_string($escenario) || !array_key_exists($escenario, $escenarios)) {
    exit('Escenario no válido.');
}

$partidosEscenario = $escenarios[$escenario]['partidos'];

foreach ($sociosElegidos as $socio) {
    if (!is_string($socio) || !array_key_exists($socio, $partidosEscenario)) {
        exit('Socio no válido.');
    }
}

$cantidadSocios = count($sociosElegidos);

if ($cantidadSocios > 3 || $cantidadSocios < 1) {
    exit('La cantidad de socios debe ser entre 1 y 3');
}
// TODO 2: rechazar socios repetidos y nombres ajenos a la lista del escenario.

if (count(array_unique($sociosElegidos)) !== count($sociosElegidos)) {
    exit('Hay socios repetidos.');
}



// TODO 3: sumar escaños y calcular mayoría absoluta (mitad + 1).

    // Escaños del pacto: sumo los de cada socio elegido
$escanosPacto = 0;
foreach ($sociosElegidos as $socio) {
    $escanosPacto += $partidosEscenario[$socio];
}

// Mayoría absoluta: la mitad de los escaños totales + 1
$escanosTotales = $escenarios[$escenario]['escanos'];
$mayoriaAbsoluta = calcularMayoria($escanosTotales);


// TODO 4: mayoría sí/no, y cuántos sobran o faltan
$hayMayoria = $escanosPacto >= $mayoriaAbsoluta;
$diferencia = $escanosPacto - $mayoriaAbsoluta;   // positivo: sobran; negativo: faltan

// TODO 5: coalición con cada partido que NO está en el pacto
$coaliciones = [];
foreach ($partidosEscenario as $partido => $escanos) {
    if (!in_array($partido, $sociosElegidos, true)) {
        $coaliciones[$partido] = $escanosPacto + $escanos;
    }
}

// TODO 6: el partido que llega a la mayoría aportando menos escaños
$mejorSocio = null;
if (!$hayMayoria) {
    foreach ($coaliciones as $partido => $totalCoalicion) {
        if ($totalCoalicion >= $mayoriaAbsoluta) {
            if ($mejorSocio === null) {
                $mejorSocio = $partido;
            } elseif ($partidosEscenario[$partido] < $partidosEscenario[$mejorSocio]) {
                $mejorSocio = $partido;
            }
        }
    }
}
// TODO 7: construir el informe en vistas/informe.php.

require __DIR__ . '/vistas/informe.php';