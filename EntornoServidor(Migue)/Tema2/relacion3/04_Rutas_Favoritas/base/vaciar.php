<?php
declare(strict_types=1);
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); exit;
}
// TODO: enviar cookie vencida con la misma ruta y redirigir.
