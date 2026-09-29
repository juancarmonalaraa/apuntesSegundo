<?php
// Ejemplo inicial: GET envía los parámetros en la URL.
$nombre = trim((string) ($_GET['nombre'] ?? ''));
$edad = filter_var($_GET['edad'] ?? null, FILTER_VALIDATE_INT);
if ($nombre === '' || $edad === false || $edad < 0 || $edad > 130) {
    exit('Introduce un nombre y una edad válida en el formulario.');
}
$nombreSeguro = htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8');
echo "Hola, $nombreSeguro. Tienes $edad años.";
