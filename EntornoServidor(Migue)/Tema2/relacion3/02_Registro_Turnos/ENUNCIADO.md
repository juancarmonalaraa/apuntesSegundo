# Ejercicio 2 · Registro de turnos con caducidad de sesión

**Objetivo.** Controlar un inicio de sesión con intentos fallidos, caducidad por inactividad y acciones privadas que dependen del estado guardado en la sesión.

## Material inicial

`base/cuentas.php` contiene dos cuentas de prueba con hashes de contraseña. Implementa `acceso.php`, `panel.php`, `registrar.php`, `sesion.php` y `salir.php`. No utilices MySQL ni almacenamiento permanente: los movimientos se conservan exclusivamente durante la sesión iniciada.

Cuentas para las pruebas:

- **ana@turnos.test** / **EntradaAna26!**.
- **jose@turnos.test** / **EntradaJose26!**.

## Requisitos

1. `acceso.php` permite identificarse con correo y contraseña. Usa `password_verify()` y, si el acceso es correcto, ejecuta `session_regenerate_id(true)`, guarda el **correo** y el **nombre** en `$_SESSION` e inicializa una lista vacía de movimientos y la hora de última actividad con `time()`.
2. Lleva en la sesión el contador `intentos` de autenticaciones fallidas. Cada `POST` que incluya ambos campos, pero tenga credenciales incorrectas, suma un intento. Al alcanzar **tres fallos consecutivos**, fija `bloqueado_hasta` a `time() + 90`. Durante esos **90 segundos**, el acceso se rechaza incluso con la contraseña correcta y se muestra cuánto falta aproximadamente. Al finalizar el bloqueo, permite probar de nuevo con cero intentos. Un acceso correcto también reinicia ambos datos.
3. Para cualquier página privada, `sesion.php` debe proporcionar una comprobación reutilizable. Si no hay usuario identificado, redirige a `acceso.php`. Si han transcurrido **más de 180 segundos** desde la última actividad, finaliza la sesión y redirige a `acceso.php?motivo=expirada`. En peticiones válidas actualiza la última actividad. La caducidad se comprueba **en el servidor**, no con JavaScript.
4. `panel.php` muestra el nombre y una tabla de movimientos de la sesión: tipo (**Entrada/Salida**) y fecha y hora generadas por el servidor. Incluye dos formularios que envían `POST` a `registrar.php`, con el campo `tipo` igual a `entrada` o `salida`.
5. `registrar.php` solo admite `POST` y necesita una sesión autenticada y vigente. La **primera operación debe ser Entrada**. Después, los tipos deben **alternarse**: no se permiten dos entradas seguidas ni dos salidas seguidas. Si una operación es inválida, no cambia la lista; si es válida, añade un movimiento con la hora actual y redirige al panel.
6. `salir.php` acepta solo `POST` y realiza el cierre completo de sesión, incluida la eliminación de su cookie; después redirige a `acceso.php`. El cierre voluntario y el vencimiento de la sesión deben impedir volver a consultar los movimientos privados.
7. No incluyas la contraseña ni los hashes en `$_SESSION`. Valida los valores de `POST` en el servidor y usa `htmlspecialchars()` al insertar datos en HTML.

## Pruebas

- Dos contraseñas incorrectas y una correcta permiten entrar y reinician el contador.
- Tres errores seguidos impiden acceder durante 90 segundos incluso con la clave correcta.
- Una petición directa a `registrar.php` sin sesión no añade movimientos.
- Se acepta **Entrada → Salida → Entrada**; se rechaza **Entrada → Entrada**.
- Con la sesión inactiva más de 180 segundos, la siguiente petición privada redirige al acceso y no conserva los movimientos.
