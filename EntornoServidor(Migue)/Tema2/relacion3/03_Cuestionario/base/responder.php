<?php
declare(strict_types=1);
require_once __DIR__ . '/preguntas.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); exit;
}
// TODO: validar sesión y opción, registrar un solo acierto y avanzar.
// TODO: redirigir a index.php sin generar HTML.
