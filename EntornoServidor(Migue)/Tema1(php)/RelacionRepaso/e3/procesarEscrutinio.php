<?php
require_once __DIR__ . '/datosPartidos.php';

// TODO 1: aceptar POST y validar censo entero entre 1 y 100000.

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    http_response_code(405);
    exit('Envía el pedido de manera correcta');
}

$censo = $_POST['censo'] ?? null;
$censo = filter_var($censo, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 100000]]);



// TODO 2: validar que votos[] contiene exactamente las claves de $partidos,
//          y que todos los votos, blancos y nulos son enteros >= 0.

$votos = $_POST['votos'] ?? null;
$blancos = $_POST['blancos'] ?? null;
$nulos = $_POST['nulos'] ?? null;

// Votos: tiene que ser un array con tantos elementos como partidos
if (!is_array($votos) || count($votos) !== count($partidos)) {
    exit('Votos no válidos.');
}

// Cada partido de mi lista tiene que existir y tener un entero >= 0
foreach ($partidos as $partido) {
    if (!array_key_exists($partido, $votos)) {
        exit('Falta algún partido.');
    }
    $votos[$partido] = filter_var($votos[$partido], FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]);
    if ($votos[$partido] === false) {
        exit('Los votos deben ser enteros de 0 en adelante.');
    }
}
$blancos = filter_var($blancos, FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]);
if ($blancos === false) {
    exit('Los votos en blanco no son válidos.');
}

$nulos = filter_var($nulos, FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]);
if ($nulos === false) {
    exit('Los votos nulos no son válidos.');
}




// TODO 3: totales
$candidaturas = array_sum($votos);          // votos a los partidos
$validos = $candidaturas + $blancos;        // candidaturas + blancos
$emitidos = $validos + $nulos;              // válidos + nulos
$abstencion = $censo - $emitidos;           // los que no han votado
$participacion = $emitidos / $censo * 100;  // % del censo que ha votado

// TODO 4: rechazar recuentos imposibles
if ($emitidos > $censo) {
    exit('Hay más votos emitidos que censo.');
}
if ($emitidos === 0) {
    exit('No hay votos emitidos.');
}
if ($validos === 0) {
    exit('No hay votos válidos.');          // evita dividir entre 0 en el TODO 5
}

// TODO 5: porcentajes, orden y empate
$porcentajes = [];
foreach ($votos as $partido => $numVotos) {
    $porcentajes[$partido] = $numVotos / $validos * 100;   // sobre VÁLIDOS
}

$ordenados = $votos;
arsort($ordenados);                         // de más a menos votos, conservando los nombres

$valores = array_values($ordenados);        // lista con posiciones 0, 1, 2...
$hayEmpate = $valores[0] === $valores[1];   // ¿el primero y el segundo tienen los mismos votos?
$ganador = array_key_first($ordenados);     // nombre del primero

// TODO 6: partidos que alcanzan el umbral
$partidosUmbral = 0;
foreach ($porcentajes as $porcentaje) {
    if ($porcentaje >= $umbralPorcentaje) {
        $partidosUmbral++;
    }
}

// TODO 7: mostrar tabla HTML mediante vistas/resultados.php.
require __DIR__ . '/vistas/resultados.php';
echo 'Pendiente de implementar el escrutinio.';