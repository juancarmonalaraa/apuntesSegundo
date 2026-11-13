<?php

declare(strict_types=1);
session_start();
require_once __DIR__ . '/cuentas.php';

// Si ya hay sesión iniciada, no tiene sentido ver el login.
// (Si estaba caducada, panel.php la cerrará y volverá aquí con ?motivo=expirada.)
if (isset($_SESSION['correo'], $_SESSION['nombre'])) {
    header('Location: panel.php');
    exit;
}

const MAX_INTENTOS = 3;
const SEGUNDOS_BLOQUEO = 90;

function e(string $v): string
{
    return htmlspecialchars($v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

$error = '';
$correo = '';

// Si el bloqueo ya terminó, se empieza de cero.
$bloqueadoHasta = (int)($_SESSION['bloqueado_hasta'] ?? 0);
if ($bloqueadoHasta !== 0 && time() >= $bloqueadoHasta) {
    $_SESSION['intentos'] = 0;
    unset($_SESSION['bloqueado_hasta']);
    $bloqueadoHasta = 0;
}
$_SESSION['intentos'] = (int)($_SESSION['intentos'] ?? 0);

// Solo se procesa si llegan AMBOS campos.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['correo'], $_POST['clave'])) {
    $correo = strtolower(trim((string)$_POST['correo']));
    $clave = (string)$_POST['clave'];

    if ($bloqueadoHasta > time()) {
        // Bloqueado: se rechaza aunque la clave sea correcta y NO se suma intento.
    } else {
        $cuentas = obtenerCuentas();
        if (
            $correo !== '' && $clave !== '' && isset($cuentas[$correo])
            && password_verify($clave, $cuentas[$correo]['hash'])
        ) {
            session_regenerate_id(true);
            $_SESSION['correo'] = $correo;
            $_SESSION['nombre'] = $cuentas[$correo]['nombre'];
            $_SESSION['movimientos'] = [];
            $_SESSION['ultima_actividad'] = time();
            $_SESSION['intentos'] = 0;
            unset($_SESSION['bloqueado_hasta']);
            header('Location: panel.php');
            exit;
        }
        // Credenciales incorrectas: un fallo más.
        $_SESSION['intentos']++;
        if ($_SESSION['intentos'] >= MAX_INTENTOS) {
            $_SESSION['bloqueado_hasta'] = time() + SEGUNDOS_BLOQUEO;
            $bloqueadoHasta = $_SESSION['bloqueado_hasta'];
        } else {
            $error = 'Datos de acceso incorrectos.';
        }
    }
}

// Aviso de bloqueo (se calcula al final para mostrar el tiempo restante real).
if ($bloqueadoHasta > time()) {
    $restante = $bloqueadoHasta - time();
    $error = 'Demasiados intentos fallidos. Acceso bloqueado, faltan unos ' . $restante . ' segundos.';
}

$expirada = ($_GET['motivo'] ?? '') === 'expirada';
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Registro de turnos</title>
</head>

<body>
    <h1>Identificación</h1>
    <?php if ($expirada): ?><p role="status">Tu sesión ha caducado por inactividad. Identifícate de nuevo.</p><?php endif; ?>
    <?php if ($error !== ''): ?><p role="alert"><?= e($error) ?></p><?php endif; ?>
    <form method="post">
        <label>Correo <input type="email" name="correo" value="<?= e($correo) ?>" required></label>
        <label>Contraseña <input type="password" name="clave" required></label>
        <button type="submit">Entrar</button>
    </form>
</body>

</html>
