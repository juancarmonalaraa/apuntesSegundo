<?php
require_once __DIR__ . '/datosActos.php';

// TODO 1: comprobar que la petición es GET.
// TODO 2: leer y validar provincia, partido, tipo y limite (1..10).
//          Permitir "Todas" / "Todos" para no filtrar ese campo.
// TODO 3: recorrer $actos y guardar todos los que cumplen LOS TRES filtros.
// TODO 4: calcular cantidad de coincidencias y aforo total coincidente.
// TODO 5: mostrar solo los primeros $limite actos usando una vista externa.

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'GET') {
    http_response_code(405);
    exit('Envía el formulario de la agenda');
}

$provincia = $_GET['provincia'] ?? null;
$partido = $_GET['partido'] ?? null;
$tipo = $_GET['tipo'] ?? null;
$limite = $_GET['limite'] ?? null;

if (!is_string($provincia) || ($provincia !== 'Todas' && !in_array($provincia, $provincias, true))) {
    exit('Provincia no válida.');
}
if (!is_string($partido) || ($partido !== 'Todos' && !in_array($partido, $partidos, true))) {
    exit('Partido no válido.');
}
if (!is_string($tipo) || ($tipo !== 'Todos' && !in_array($tipo, $tipos, true))) {
    exit('Tipo no válido.');
}
$limite = filter_var($limite, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 10]]);
if ($limite === false) {
    exit('El límite debe ser un número entero entre 1 y 10.');
}

$coincidencias = [];
$aforoTotal = 0;

foreach ($actos as $acto) {
    if (
        ($provincia === 'Todas' || $acto['provincia'] === $provincia) &&
        ($partido === 'Todos' || $acto['partido'] === $partido) &&
        ($tipo === 'Todos' || $acto['tipo'] === $tipo)
    ) {
        $coincidencias[] = $acto;
        $aforoTotal += $acto['aforo'];
    }
}

// require __DIR__ . '/vistas/agendaResultado.php';
$visibles = array_slice($coincidencias, 0, $limite);
$total = count($coincidencias);

require __DIR__ . '/vistas/agendaResultado.php';