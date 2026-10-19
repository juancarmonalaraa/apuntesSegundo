<?php
require_once __DIR__ . '/repositorioPreguntas.php';

// EJERCICIO 07 - PARTE B: CORRECCIÓN.
// TODO 1: exige POST y recupera asignatura, preguntas y respuestas.
// TODO 2: comprueba que las preguntas enviadas pertenecen a la asignatura,
//          no están repetidas y que cada respuesta pertenece a sus opciones.
// TODO 3: consulta el mapa de soluciones que has completado en el repositorio.
// TODO 4: cuenta aciertos y fallos (incluidas las preguntas en blanco).
// TODO 5: calcula una nota entre 0 y 10 y muestra el resultado en HTML.

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    http_response_code(405);
    exit('Envía el test desde el formulario.');
}
$asignatura = $_POST['asignatura'] ?? null;
$preguntas = $_POST['preguntas'] ?? null;
$respuestas = $_POST['respuestas'] ?? [];
$cantidad = $_POST['cantidad'] ?? null;
if (
    !is_string($asignatura) || !array_key_exists($asignatura, $repositorioPreguntas) ||
    !is_array($preguntas) || !is_array($respuestas) ||
    count($preguntas) < 1 || count($preguntas) > 5
) {
    exit('El test recibido no es válido.');
}
$soluciones = $repositorioRespuestas[$asignatura];
$acertadas = 0;
$usadas = [];
foreach ($preguntas as $indice => $pregunta) {
    if (
        !is_string($pregunta) || !array_key_exists($pregunta, $soluciones) ||
        in_array($pregunta, $usadas, true)
    ) {
        exit('El test contiene una pregunta no válida o repetida.');
    }
    $usadas[] = $pregunta;
    $respuesta = $respuestas[$indice] ?? '';
    if (!is_string($respuesta)) {
        exit('Se ha recibido una respuesta no válida.');
    }
    // Se admiten respuestas en blanco, pero nunca valores ajenos a las opciones permitidas.
    if ($respuesta !== '' && !in_array($respuesta, $repositorioPreguntas[$asignatura][$pregunta], true)) {
        exit('Se ha recibido una opción de respuesta no válida.');
    }
    if ($respuesta === $soluciones[$pregunta]) {
        $acertadas++;
    }
}
$totalPreguntas = count($preguntas);
$totalRespuestas = count($respuestas);
$falladas = $totalRespuestas - $acertadas;
$sinResponder = $cantidad - $totalRespuestas    ;
$nota = round($acertadas / $totalPreguntas * 10, 2);
echo '<!doctype html><html lang="es"><meta charset="utf-8"><title>Resultado del test</title><body>';
echo '<h1>Resultado</h1><p>Aciertos: ' . $acertadas . '</p>';
echo '<p>Fallos: ' . $falladas . '</p>';
echo '<p>Preguntas sin contestar: ' . $sinResponder . '</p>';
echo '<p>Nota: ' . number_format($nota, 2, ',', '.') . ' / 10</p>';
echo '</body></html>';
