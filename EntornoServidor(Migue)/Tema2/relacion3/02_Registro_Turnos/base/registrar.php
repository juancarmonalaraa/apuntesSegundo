<?php
 
declare(strict_types=1);
require_once __DIR__ . '/sesion.php';
 
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Método no permitido');
}
exigirSesion();
 
// Validar el valor recibido: solo 'entrada' o 'salida'.
$tipo = $_POST['tipo'] ?? null;
if (!is_string($tipo) || !in_array($tipo, ['entrada', 'salida'], true)) {
    http_response_code(400);
    exit('Tipo de movimiento no válido.');
}
 
// Alternancia: la primera es entrada; después, siempre lo contrario del último.
$movimientos = $_SESSION['movimientos'] ?? [];
$ultimo = $movimientos === [] ? null : $movimientos[array_key_last($movimientos)]['tipo'];
$esperado = ($ultimo === null || $ultimo === 'salida') ? 'entrada' : 'salida';
 
if ($tipo !== $esperado) {
    http_response_code(409);
    exit('Operación no válida: ahora toca registrar una ' . $esperado . '. <a href="panel.php">Volver</a>');
}
 
$_SESSION['movimientos'][] = ['tipo' => $tipo, 'momento' => time()];
header('Location: panel.php');
exit;
 