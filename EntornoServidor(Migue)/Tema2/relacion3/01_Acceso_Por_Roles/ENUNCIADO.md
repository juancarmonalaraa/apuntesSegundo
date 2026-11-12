# Ejercicio 1 · Control de acceso al panel de incidencias

**Objetivo.** Autenticar usuarios, conservar su identidad en una sesión, comprobar permisos según su perfil y cerrar el acceso correctamente.

## Material inicial

En `base/usuarios.php` se proporcionan dos cuentas con contraseñas almacenadas mediante `password_hash()`. `base/incidencias.php` contiene cinco incidencias identificadas por un número. Completa `login.php`, `panel.php`, `revisar.php`, `informe.php` y `logout.php`. No necesitas base de datos.

Cuentas para las pruebas:

- **alba@centro.test** / **AccesoAlba26!** — perfil `coordinacion`.
- **marcos@centro.test** / **AccesoMarcos26!** — perfil `tecnico`.

## Requisitos

1. `login.php` presenta un formulario de **correo** y **contraseña**. Al enviarlo mediante `POST`, comprueba que ambos campos existen, busca el correo sin distinguir mayúsculas y minúsculas y valida la contraseña con `password_verify()`. Si falla, muestra **«Datos de acceso incorrectos.»** y mantiene el formulario.
2. Si las credenciales son válidas, regenera el identificador mediante `session_regenerate_id(true)` y guarda en `$_SESSION` **nombre, correo y perfil**. Redirige a `panel.php`. Si una persona ya identificada abre `login.php`, redirige directamente al panel.
3. `panel.php` solo puede mostrarse cuando la sesión contiene un usuario identificado. Enseña un saludo con su nombre y perfil, las **cinco incidencias** del archivo de datos y su estado **Pendiente/Revisada**. Si no hay sesión, redirige al acceso.
4. Cada incidencia pendiente tiene un botón **Marcar como revisada**, que envía su identificador por `POST` a `revisar.php`. Conserva en `$_SESSION['revisadas']` los IDs de las incidencias revisadas por esa persona. Repetir un ID no lo duplica; un ID desconocido se rechaza sin modificar la sesión. El estado no debe guardarse en cookies ni en los datos originales.
5. `informe.php` es una página privada **exclusiva de `coordinacion`**. Muestra el número de incidencias pendientes y revisadas de la sesión actual. Una persona identificada con perfil `tecnico` que intente acceder directamente recibe **HTTP 403**, sin ver el informe.
6. `logout.php` acepta únicamente `POST`, vacía `$_SESSION`, solicita la eliminación de la cookie de sesión con sus parámetros, destruye la sesión y redirige a `login.php`. El panel incluye un formulario para salir; no debe bastar con visitar `logout.php` mediante `GET`.
7. Coloca `session_start()` al principio de los scripts que necesiten acceder a la sesión. Escapa los valores mostrados en HTML con `htmlspecialchars()` y no envíes HTML antes de las redirecciones.

## Pruebas

- Una contraseña incorrecta no permite entrar y muestra un mensaje genérico.
- Marcos puede revisar una incidencia, recargar el panel y seguir viéndola como revisada; no puede abrir `informe.php`.
- Alba puede entrar al informe y el recuento cambia al marcar incidencias.
- Modificar manualmente el ID enviado a `revisar.php` por uno inexistente no altera el listado.
- Tras cerrar sesión, una petición directa a `panel.php` vuelve al formulario de acceso.
