# 04 · Calculadora de IMC con validación

## Objetivo
Procesar datos numéricos recibidos por un formulario y realizar cálculos **solo después de validar** la petición y los campos.

## Actividad
1. Completa `IMC.html` con `nombre` (1–20 caracteres), `edad` (entero entre 0 y 130), `altura` (entero entre 50 y 300 **cm**) y `peso` (decimal entre 20 y 500 **kg**). Añade un botón de envío y conserva el método `POST` y la acción hacia `porcesarIMC.php`.
2. En `porcesarIMC.php` verifica el método HTTP, la presencia de los cuatro campos y los tipos/rangos indicados. Si algún dato no es válido, muestra un mensaje y no realices cálculos.
3. Convierte la altura a metros, calcula el **IMC = peso / (altura en metros)²** y muéstralo con dos decimales.
4. Calcula también una **estimación didáctica** de pulsaciones máximas con `220 - edad`. No debe presentarse como una medición clínica.
5. Muestra un resultado que incluya el nombre del usuario de forma segura.

## Prueba
Prueba con valores válidos y también con altura 0, peso fuera de rango, ausencia de nombre y acceso directo al archivo PHP sin enviar el formulario.

**Pistas del temario:** `$_SERVER['REQUEST_METHOD']`, `$_POST`, `filter_var()`, condicionales y `round()`.
