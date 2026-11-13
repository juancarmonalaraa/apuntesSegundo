<?php
declare(strict_types=1);
// TODO: escribir una función reutilizable que verifique la sesión.
// Debe comprobar usuario y límite de inactividad de 180 segundos.
// Si sigue vigente, actualizar la hora de la última actividad.
// Si ha expirado, cerrar la sesión y redirigir al acceso.

<?php

declare(strict_types=1);

// Huso horario del servidor: así las fechas salen en hora de España.
date_default_timezone_set('Europe/Madrid');

// Segundos máximos de inactividad antes de que caduque la sesión.
const LIMITE_INACTIVIDAD = 180;

// Cierre COMPLETO: vacía los datos, borra la cookie y destruye la sesión.
// Lo usan salir.php (cierre voluntario) y exigirSesion() (caducidad).
function cerrarSesionCompleta(): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', [
            'expires'  => time() - 3600,
            'path'     => $p['path'],
            'domain'   => $p['domain'],
            'secure'   => $p['secure'],
            'httponly' => $p['httponly'],
            'samesite' => $p['samesite'] ?: 'Lax',
        ]);
    }
    session_destroy();
}

// Comprobación reutilizable: se llama al principio de CUALQUIER página privada.
function exigirSesion(): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    // Que el navegador no guarde copia de páginas privadas (botón "Atrás" tras salir).
    header('Cache-Control: no-store');

    // 1) ¿Hay usuario identificado?
    if (!isset($_SESSION['correo'], $_SESSION['nombre'])) {
        header('Location: acceso.php');
        exit;
    }

    // 2) ¿Han pasado MÁS de 180 segundos desde la última actividad?
    $ultima = $_SESSION['ultima_actividad'] ?? null;
    if (!is_int($ultima) || time() - $ultima > LIMITE_INACTIVIDAD) {
        cerrarSesionCompleta();
        header('Location: acceso.php?motivo=expirada');
        exit;
    }

    // 3) Petición válida: se renueva la última actividad.
    $_SESSION['ultima_actividad'] = time();
}