# 07 · Generador y corrector de tests

## Objetivo
Crear una aplicación que seleccione preguntas aleatorias de un repositorio y corrija un test para obtener una nota entre 0 y 10.

## Archivos de trabajo
- `generadorDeTests.html`: formulario de configuración del test.
- `repositorioPreguntas.php`: **banco de partida** con tres asignaturas y diez preguntas de cada una, con sus opciones. Debes completar el mapa `$repositorioRespuestas` con las respuestas correctas, manteniendo las mismas claves de asignatura y pregunta.
- `generadorDeTests.php`: genera el test de la asignatura seleccionada.
- `comprobarTest.php`: corrige el test enviado.

## Actividad
1. Completa el formulario para elegir **Matemáticas, Historia o Ciencias** y un número entero de preguntas entre **1 y 5**, enviando por POST a `generadorDeTests.php`.
2. Comprueba que la asignatura y el número de preguntas recibidos son válidos. Selecciona aleatoriamente el número solicitado de preguntas **sin repetir ninguna**.
3. Genera una página HTML con las preguntas y sus posibles respuestas como botones radio. Añade un botón **Corregir test** que envíe por POST a `comprobarTest.php`.
4. Transmite también la asignatura y los identificadores de las preguntas seleccionadas para que el corrector sepa qué respuestas comparar. **No envíes al navegador el mapa de respuestas correctas.**
5. En el corrector, valida que los datos pertenecen al repositorio, cuenta **aciertos y fallos** (una pregunta en blanco cuenta como fallo) y muestra una **nota de 0 a 10** calculada como `10 × aciertos / total de preguntas`.
6. Cuida que los textos de pregunta y opción se inserten con seguridad en el HTML y que no se admitan preguntas ni respuestas inventadas por el navegador.

## Prueba
Genera tests de 1 y 5 preguntas; comprueba que cambian las preguntas al repetir la operación; deja una sin responder y revisa la nota. Las respuestas correctas están en el mapa que debes completar, no en el formulario HTML.

**Pistas del temario:** arrays multidimensionales, `array_rand()`, `foreach`, `$_POST`, campos ocultos, comprobación de claves y validación de opciones.
