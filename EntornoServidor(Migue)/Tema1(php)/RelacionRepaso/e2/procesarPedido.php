<?php
require_once __DIR__ . '/catalogo.php';
require_once __DIR__ . '/funciones.php';

// TODO 1: aceptar solo POST y validar partido, pack, transporte, unidades (1..8).

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    http_response_code(405);
    exit('Envía el pedido de manera correcta');
}

$partido = $_POST['partido'] ?? null;
$pack = $_POST['pack'] ?? null;
$transporte = $_POST['transporte'] ?? null;
$unidades = $_POST['unidades'] ?? null;
$unidades = filter_var($unidades, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 8]]);
if ($unidades === false) {
    exit('Maximo numero 8 y minimo 1');
}
$extrasElegidos = $_POST['extras'] ?? [];
if (!is_array($extrasElegidos)) {
    exit('Extras no válidos.');
}
foreach ($extrasElegidos as $extra) {
    if (!is_string($extra) || !array_key_exists($extra, $extras)) {
        exit('Extra no válido.');
    }
}




// TODO 2: recuperar extras[] (puede no existir), validar sus valores y eliminar duplicados.

$extrasElegidos = array_unique($extrasElegidos);



if (!is_string($partido) || !in_array($partido, $partidos, true)) {
    exit('Partido no válido.');
}
if (!is_string($pack) || !array_key_exists($pack, $packs)) {
    exit('Pack no válido.');
}
if (!is_string($transporte) || !array_key_exists($transporte, $transportes)) {
    exit('Transporte no válido.');
}

// TODO 3: calcular precio unitario = pack + suma de extras.

$precioUnitario = $packs[$pack]['precio'];

foreach ($extrasElegidos as $extra){
    $precioUnitario += $extras[$extra]['precio'];
}



// TODO 4: mercancía = precio unitario * unidades; aplicar código si es válido.
//          Un código inválido NO da descuento; el transporte NO se descuenta.

$mercancia= $precioUnitario * $unidades;


$codigo = $_POST['codigo'] ?? '';
if (!is_string($codigo)) {
    exit('Código no válido.');
}

$codigo=trim($codigo);
$descuento = 0;

if (array_key_exists($codigo, $codigos) ) {
   $descuento = $mercancia * $codigos [$codigo] /100;
}
$mercanciaFinal = $mercancia - $descuento;

// TODO 5: calcular base con transporte, IVA 21 % y total final.

$mercanciaFinalTransporte = $mercanciaFinal + $transportes[$transporte];
$ivaTotal= $mercanciaFinalTransporte * 0.21;
$mercanciaFinalFinal = $mercanciaFinalTransporte + $ivaTotal;

// TODO 6: indicar si total final supera 500 €, y preparar desglose en la vista.

if ($mercanciaFinalFinal>500) {
    $mercanciaValida = false;
}else {
    $mercanciaValida = true;
}

require __DIR__ . '/vistas/ticket.php';