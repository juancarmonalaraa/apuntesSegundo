# 01 · Mi primer formulario con GET

## Objetivo
Crear una página HTML que envíe un nombre y una edad a un programa PHP mediante **GET**, y mostrar un saludo generado en el servidor.

## Actividad
1. Completa `miPrimeritoFormulario.html`: añade un campo `edad` numérico (entre 0 y 130) y un botón de envío. Mantén el campo `nombre` y la acción hacia `miPrimeritoProcesar.php`.
2. Completa `miPrimeritoProcesar.php`: recupera `nombre` y `edad` desde `$_GET`, valida que el nombre no esté vacío y que la edad sea un entero dentro del intervalo indicado.
3. Muestra un mensaje con el formato «Hola, Ana. Tienes 19 años.» o informa al usuario si alguno de los datos no es válido.
4. Evita mostrar directamente etiquetas HTML introducidas como nombre: trata los datos antes de incluirlos en el HTML de respuesta.

## Prueba
Accede a `http://localhost/UD1/01_Primer_formulario_GET/miPrimeritoFormulario.html`, envía valores válidos y después prueba con la edad fuera de rango y con el nombre vacío. Observa también los parámetros que aparecen en la URL.

**Pistas del temario:** formularios GET, `$_GET`, condicionales y `htmlspecialchars()`.
