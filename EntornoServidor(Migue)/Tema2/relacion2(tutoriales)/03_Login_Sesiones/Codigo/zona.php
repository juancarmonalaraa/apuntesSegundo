<?php
declare(strict_types=1);
session_start();

if (!isset($_SESSION['nombre'])) {
    header('Location: login.php');
    exit;
}
$nombre = htmlspecialchars((string) $_SESSION['nombre'], ENT_QUOTES, 'UTF-8');
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Zona privada</title>
</head>
<body>
    <h1>Bienvenida, <?= $nombre ?></h1>
    <form action="logout.php" method="post">
        <button type="submit">Cerrar sesión</button>
    </form>
</body>
</html>
