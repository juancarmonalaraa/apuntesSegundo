<?php
declare(strict_types=1);
session_start();
require_once __DIR__ . '/usuarios.php';

if (isset($_SESSION['nombre'])) {
    header('Location: zona.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim((string) ($_POST['email'] ?? ''));
    $clave = (string) ($_POST['clave'] ?? '');
    $nombre = autenticar($email, $clave);

    if ($nombre !== null) {
        session_regenerate_id(true);
        $_SESSION['nombre'] = $nombre;
        header('Location: zona.php');
        exit;
    }
    $error = 'Credenciales incorrectas.';
}
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Acceso</title>
</head>
<body>
    <h1>Club de lectura</h1>
    <?php if ($error !== ''): ?>
        <p><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>
    <form action="login.php" method="post">
        <label>Correo
            <input name="email" type="email" required>
        </label>
        <label>Contraseña
            <input name="clave" type="password" required>
        </label>
        <button type="submit">Entrar</button>
    </form>
</body>
</html>
