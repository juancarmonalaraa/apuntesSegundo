<?php
require_once __DIR__ . '/repositorioPreguntas.php';

// EJERCICIO 07 - PARTE A: GENERACIÓN.
// TODO 1: exige POST y valida asignatura y numeroDePreguntas (entre 1 y 5).
// TODO 2: elige al azar preguntas distintas del repositorio de esa asignatura.
// TODO 3: genera un formulario HTML POST dirigido a comprobarTest.php.
// TODO 4: incluye identificadores de preguntas y opciones de respuesta
//          (radios) para que el corrector pueda cotejar las respuestas.
// TODO 5: incorpora información suficiente para conocer qué asignatura
//          y qué preguntas se han mostrado, sin enviar al cliente las soluciones.
if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    http_response_code(405);
    exit('Envía el formulario mediante POST.');
}

$asignatura = $_POST['asignatura'] ?? '';
$numero = $_POST['numeroDePreguntas'] ?? '';

if (!is_string($asignatura) || !array_key_exists ($asignatura, $repositorioPreguntas)) {
    exit('Selecciona una asignatura');
}
$numero = filter_var($numero, FILTER_VALIDATE_INT, [
    'options' => ['min_range' => 1, 'max_range' => 5]
]);
if ($numero===false) {
     exit('El número de preguntas debe ser un entero entre 1 y 5.');
}

$preguntasAsignatura= $repositorioPreguntas[$asignatura];
$textos = array_keys($preguntasAsignatura);
$preguntasElegidas = (array) array_rand($textos, $numero);


// Protegemos la asignatura una sola vez y usamos esa variable luego
$asignaturaSegura = htmlspecialchars($asignatura);

// ----- Cabecera de la página -----
echo '<!DOCTYPE html>';
echo '<html lang="es">';
echo '<head><meta charset="UTF-8"><title>Test de ' . $asignaturaSegura . '</title></head>';
echo '<body>';
echo '<h1>Test de ' . $asignaturaSegura . '</h1>';

// ----- Empieza el formulario -----
echo '<form action="comprobarTest.php" method="POST">';


// Campo oculto: así el corrector sabrá de qué asignatura es el test
echo '<input type="hidden" name="asignatura" value="' . $asignaturaSegura . '">';

// ----- Una vuelta por cada pregunta elegida -----
foreach ($preguntasElegidas as $id) { 
    $pregunta = $textos[$id];
    echo '<input type="hidden" name="preguntas[]" value="' . $id . '">';
    echo '<p>' . htmlspecialchars($pregunta) . '</p>';
    foreach($preguntasAsignatura[$pregunta] as $opcion){
    echo '<input type="radio" name="respuestas[' . $id . ']" value="' . htmlspecialchars($opcion) . '">';
    echo htmlspecialchars($opcion) . '<br>';
    }
    
 }

// ----- Botón y cierre -----
echo '<br><button type="submit">Corregir test</button>';
echo '</form>';
echo '</body>';
echo '</html>';

?>


