<?php
require_once __DIR__ . '/catalogo.php';
require_once __DIR__ . '/funciones.php';

// TODO 1: aceptar solo POST y validar partido, pack, transporte, unidades (1..8).

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    http_response_code(405);
    exit('Envía el formulario de la agenda');
}

$partido = $_POST['partido'] ?? null;
$pack = $_POST['pack'] ?? null;
$transporte = $_POST['transporte'] ?? null;
$unidades = $_POST['unidades'] ?? null;

// TODO 2: recuperar extras[] (puede no existir), validar sus valores y eliminar duplicados.



// TODO 3: calcular precio unitario = pack + suma de extras.
// TODO 4: mercancía = precio unitario * unidades; aplicar código si es válido.
//          Un código inválido NO da descuento; el transporte NO se descuenta.
// TODO 5: calcular base con transporte, IVA 21 % y total final.
// TODO 6: indicar si total final supera 500 €, y preparar desglose en la vista.

// require __DIR__ . '/vistas/ticket.php';
echo 'Pendiente de implementar el pedido.';