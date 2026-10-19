<?php
require_once __DIR__ . '/repositorioPreguntas.php';

// EJERCICIO 07 - PARTE B: CORRECCIÓN.
// TODO 1: exige POST y recupera asignatura, preguntas y respuestas.
// TODO 2: comprueba que las preguntas enviadas pertenecen a la asignatura,
//          no están repetidas y que cada respuesta pertenece a sus opciones.
// TODO 3: consulta el mapa de soluciones que has completado en el repositorio.
// TODO 4: cuenta aciertos y fallos (incluidas las preguntas en blanco).
// TODO 5: calcula una nota entre 0 y 10 y muestra el resultado en HTML.

echo 'Pendiente de implementar la corrección del ejercicio 07.';

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    http_response_code(405);
    exit('Envía el formulario mediante POST.');
}
  
$asignatura = $_POST['asignatura'] ?? '';
$preguntas = $_POST['preguntas'] ?? [];
$respuestas = $_POST['respuestas'] ?? [];

if (!is_string($asignatura) || !array_key_exists ($asignatura, $repositorioPreguntas)) {
    exit('Asignatura no válida');
}

if (!is_array($preguntas) || !is_array($respuestas) || count($preguntas) === 0) {
    exit('Datos no válidos')
}
