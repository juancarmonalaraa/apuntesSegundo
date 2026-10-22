<?php
// Catálogo ficticio del examen. Archivo proporcionado: NO MODIFICAR.
$modelos = [
    'urbana' => ['nombre' => 'Bicicleta urbana', 'precio' => 240],
    'montana' => ['nombre' => 'Bicicleta de montaña', 'precio' => 550],
    'carretera' => ['nombre' => 'Bicicleta de carretera', 'precio' => 950],
    'electrica' => ['nombre' => 'Bicicleta eléctrica', 'precio' => 1500],
];

$barrios = [
    'centro' => 'Centro',
    'zaidin' => 'Zaidín',
    'chana' => 'La Chana',
    'realejo' => 'Realejo',
];

// "fiable" determina la decisión recomendada. "valor" sólo se suma
// cuando una pista fiable se ha marcado como "investigar".
$pistas = [
    'camara' => [
        'descripcion' => 'Una cámara registra el robo y se reconoce claramente la bicicleta.',
        'fiable' => true, 'valor' => 3,
    ],
    'testigo' => [
        'descripcion' => 'Una persona que presenció el robo puede describir lo sucedido y la hora.',
        'fiable' => true, 'valor' => 2,
    ],
    'gps' => [
        'descripcion' => 'El GPS de la bicicleta transmite una ubicación reciente y comprobable.',
        'fiable' => true, 'valor' => 4,
    ],
    'candado' => [
        'descripcion' => 'Aparece el candado cortado de la bicicleta junto al aparcabicis.',
        'fiable' => true, 'valor' => 1,
    ],
    'anuncio' => [
        'descripcion' => 'Un anuncio de compraventa muestra un número de serie que coincide con la denuncia.',
        'fiable' => true, 'valor' => 3,
    ],
    'rumor' => [
        'descripcion' => 'En un chat dicen que conocen al ladrón, pero nadie aporta pruebas.',
        'fiable' => false, 'valor' => 0,
    ],
    'foto' => [
        'descripcion' => 'En redes circula una fotografía de una bicicleta de un modelo distinto.',
        'fiable' => false, 'valor' => 0,
    ],
    'anonimo' => [
        'descripcion' => 'Llega un mensaje anónimo que acusa a alguien sin datos comprobables.',
        'fiable' => false, 'valor' => 0,
    ],
];
