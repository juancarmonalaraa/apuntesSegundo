<?php
declare(strict_types=1);
require_once __DIR__ . '/rutas.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); exit;
}
// TODO: validar acción/código, leer y normalizar cookie, añadir/quitar.
// TODO: guardar la cookie de 30 días y redirigir.
