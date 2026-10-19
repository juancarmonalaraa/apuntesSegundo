<?php
// Precios FICTICIOS sin IVA por unidad, en euros.
$partidos = ['PSOE', 'PP', 'Vox', 'Sumar', 'Podemos'];
$packs = [
    'basico' => ['nombre'=>'Kit básico (carteles y folletos)', 'precio'=>35],
    'movil' => ['nombre'=>'Kit de calle (carteles y banderolas)', 'precio'=>65],
    'completo' => ['nombre'=>'Kit completo (material para un acto)', 'precio'=>110]
];
$extras = [
    'chapas' => ['nombre'=>'Lote de chapas', 'precio'=>9],
    'pegatinas' => ['nombre'=>'Lote de pegatinas', 'precio'=>6],
    'lanyards' => ['nombre'=>'Lote de acreditaciones', 'precio'=>12],
    'folletos' => ['nombre'=>'Lote extra de folletos', 'precio'=>8]
];
// El transporte se paga una sola vez por pedido.
$transportes = ['recogida'=>0, 'normal'=>12, 'urgente'=>28];
$codigos = ['SIN_CODIGO'=>0, 'LOCAL2026'=>5, 'VOLUNTARIADO'=>10];
$ivaPorcentaje = 21;
$presupuestoMaximo = 500;