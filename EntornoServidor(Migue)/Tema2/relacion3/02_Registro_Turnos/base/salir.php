<?php
 
declare(strict_types=1);
require_once __DIR__ . '/sesion.php';
 
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Método no permitido');
}
cerrarSesionCompleta();
header('Location: acceso.php');
exit;