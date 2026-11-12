# Ejercicio 3 · Cuestionario por etapas con sesiones

**Objetivo.** Mantener el estado de una actividad que avanza por etapas, evitando que se responda varias veces a una misma pregunta.

## Material inicial

En `base/preguntas.php` se proporcionan cuatro preguntas de opción múltiple y la respuesta correcta. Crea el funcionamiento de `index.php`, `responder.php` y `reiniciar.php` sin modificar las preguntas.

## Requisitos

1. Al entrar sin una partida activa, muestra un formulario que solicita un **alias** de 2 a 30 caracteres y el botón **Comenzar**. El formulario envía `POST` a `index.php`.
2. Valida el alias y guarda en `$_SESSION['cuestionario']` la información necesaria: alias, índice de pregunta actual y aciertos. Redirige a `index.php`.
3. Presenta **solo la pregunta actual** y sus opciones mediante botones de radio. El formulario envía `POST` a `responder.php`. No envíes al navegador la respuesta correcta ni confíes en un índice de pregunta recibido del cliente.
4. En `responder.php`, comprueba que la sesión contiene una partida sin terminar. Acepta exclusivamente una opción válida de la pregunta actual. Si la opción no existe o falta, no avances ni sumes puntos y muestra un error comprensible. Una respuesta válida pero equivocada sí hace avanzar, aunque no suma aciertos.
5. Por cada respuesta válida, avanza **una pregunta** y suma un acierto únicamente cuando corresponda. Después de la cuarta respuesta muestra el alias y la nota sobre cuatro. No debe poder responderse de nuevo.
6. El botón **Reiniciar** debe enviar `POST` a `reiniciar.php`, que elimina solo `$_SESSION['cuestionario']` y redirige a `index.php`.
7. Recargar `index.php` nunca avanza preguntas ni suma aciertos. Acceder directamente a `responder.php` sin partida redirige a `index.php`.

## Pruebas

- Un alias de una letra se rechaza y uno de tres letras comienza el cuestionario.
- Recargar la primera pregunta mantiene la puntuación y la posición.
- Enviar manualmente una opción `99` no avanza ni puntúa.
- Tras cuatro respuestas se muestra el resultado y no hay botones para responder.
- Reiniciar vuelve al formulario de alias.
