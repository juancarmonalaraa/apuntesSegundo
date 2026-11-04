<?php
declare(strict_types=1);
session_start();
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/model/Repositorio.php';

$error = '';
$peliculas = [];
$pelicula = false;
$comentarios = [];

try {
    $repo = new Repositorio(conectar());

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $accion = (string) ($_POST['accion'] ?? '');
        $id = filter_var(
            $_POST['id'] ?? null,
            FILTER_VALIDATE_INT,
            ['options' => ['min_range' => 1]]
        );
        $usuario = $_SESSION['usuario'] ?? null;

        if ($accion === 'login') {
            $encontrado = $repo->buscarUsuario((string) ($_POST['email'] ?? ''));
            $clave = (string) ($_POST['clave'] ?? '');
            if (!$encontrado || !password_verify($clave, $encontrado['hash'])) {
                throw new InvalidArgumentException('Acceso incorrecto.');
            }
            session_regenerate_id(true);
            $_SESSION['usuario'] = [
                'id' => (int) $encontrado['id'],
                'nombre' => $encontrado['nombre'],
                'rol' => $encontrado['rol']
            ];
        } elseif ($accion === 'registro') {
            $repo->registrar(
                (string) ($_POST['nombre'] ?? ''),
                (string) ($_POST['email'] ?? ''),
                (string) ($_POST['clave'] ?? '')
            );
        } elseif ($accion === 'logout') {
            $_SESSION = [];
            session_regenerate_id(true);
        } elseif ($accion === 'guardar' && ($usuario['rol'] ?? '') === 'ADMIN') {
            $anio = filter_var($_POST['anio'] ?? null, FILTER_VALIDATE_INT);
            if ($anio === false) {
                throw new InvalidArgumentException('Año no válido.');
            }
            // ID vacío significa alta; ID numérico válido significa edición.
            $idTexto = trim((string) ($_POST['id'] ?? ''));
            if ($idTexto !== '' && $id === false) {
                throw new InvalidArgumentException('ID de película no válido.');
            }
            $repo->guardarPelicula(
                $idTexto === '' ? null : $id,
                (string) ($_POST['titulo'] ?? ''),
                (string) ($_POST['sinopsis'] ?? ''),
                $anio
            );
        } elseif ($accion === 'eliminar' && $id !== false
            && ($usuario['rol'] ?? '') === 'ADMIN') {
            $repo->borrarPelicula($id);
        } elseif ($accion === 'comentar' && $id !== false && $usuario) {
            $repo->comentar($id, (int) $usuario['id'], (string) ($_POST['texto'] ?? ''));
        } elseif ($accion === 'valorar' && $id !== false && $usuario) {
            $nota = filter_var($_POST['nota'] ?? null, FILTER_VALIDATE_INT);
            if ($nota === false) {
                throw new InvalidArgumentException('Nota no válida.');
            }
            $repo->valorar($id, (int) $usuario['id'], $nota);
        } else {
            throw new InvalidArgumentException('Acción no permitida.');
        }

        $volver = ($id !== false && in_array($accion, ['comentar', 'valorar'], true))
            ? 'index.php?id=' . $id : 'index.php';
        header('Location: ' . $volver);
        exit;
    }

    $peliculas = $repo->peliculas();
    $detalle = filter_var(
        $_GET['id'] ?? null,
        FILTER_VALIDATE_INT,
        ['options' => ['min_range' => 1]]
    );
    if ($detalle !== false) {
        $pelicula = $repo->pelicula($detalle);
        if ($pelicula) {
            $comentarios = $repo->comentarios($detalle);
        }
    }
} catch (InvalidArgumentException $errorValidacion) {
    http_response_code(400);
    $error = $errorValidacion->getMessage();
} catch (PDOException $errorBD) {
    http_response_code(500);
    $error = 'Error en la operación o la base de datos.';
}
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Películas</title>
</head>
<body>
    <h1>Películas</h1>
    <p><a href="index.php">Inicio</a></p>
    <?php if ($error !== ''): ?>
        <p><?= h($error) ?></p>
    <?php endif; ?>

    <?php if (!isset($_SESSION['usuario'])): ?>
        <h2>Iniciar sesión</h2>
        <form action="index.php" method="post">
            <input type="hidden" name="accion" value="login">
            <input type="email" name="email" placeholder="Correo" required>
            <input type="password" name="clave" placeholder="Contraseña" required>
            <button type="submit">Entrar</button>
        </form>
        <h2>Registrarse</h2>
        <form action="index.php" method="post">
            <input type="hidden" name="accion" value="registro">
            <input name="nombre" maxlength="100" placeholder="Nombre" required>
            <input type="email" name="email" placeholder="Correo" required>
            <input type="password" name="clave" minlength="8" placeholder="Clave" required>
            <button type="submit">Registrarse</button>
        </form>
    <?php else: ?>
        <p>
            <?= h($_SESSION['usuario']['nombre']) ?>
            (<?= h($_SESSION['usuario']['rol']) ?>)
        </p>
        <form action="index.php" method="post">
            <input type="hidden" name="accion" value="logout">
            <button type="submit">Cerrar sesión</button>
        </form>
    <?php endif; ?>

    <?php if (($_SESSION['usuario']['rol'] ?? '') === 'ADMIN'): ?>
        <h2>Alta / edición de película</h2>
        <form action="index.php" method="post">
            <input type="hidden" name="accion" value="guardar">
            <label>ID (vacío para alta)
                <input name="id" type="number" min="1">
            </label>
            <label>Título
                <input name="titulo" maxlength="150" required>
            </label>
            <label>Sinopsis
                <textarea name="sinopsis" required></textarea>
            </label>
            <label>Año
                <input name="anio" type="number" min="1895" max="2100" required>
            </label>
            <button type="submit">Guardar</button>
        </form>
    <?php endif; ?>

    <h2>Catálogo</h2>
    <ul>
        <?php foreach ($peliculas as $film): ?>
            <li>
                <a href="index.php?id=<?= (int) $film['id'] ?>">
                    <?= h($film['titulo']) ?>
                </a>
                (<?= (int) $film['anio'] ?>) —
                media <?= $film['media'] === null ? 'sin valorar' : h((string) $film['media']) ?>
                <?php if (($_SESSION['usuario']['rol'] ?? '') === 'ADMIN'): ?>
                    <form action="index.php" method="post" style="display:inline">
                        <input type="hidden" name="accion" value="eliminar">
                        <input type="hidden" name="id" value="<?= (int) $film['id'] ?>">
                        <button type="submit">Eliminar</button>
                    </form>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>

    <?php if ($pelicula): ?>
        <h2><?= h($pelicula['titulo']) ?></h2>
        <p><?= nl2br(h($pelicula['sinopsis'])) ?></p>
        <?php if (isset($_SESSION['usuario'])): ?>
            <h3>Nuevo comentario</h3>
            <form action="index.php" method="post">
                <input type="hidden" name="accion" value="comentar">
                <input type="hidden" name="id" value="<?= (int) $pelicula['id'] ?>">
                <textarea name="texto" maxlength="1000" required></textarea>
                <button type="submit">Comentar</button>
            </form>
            <h3>Tu valoración</h3>
            <form action="index.php" method="post">
                <input type="hidden" name="accion" value="valorar">
                <input type="hidden" name="id" value="<?= (int) $pelicula['id'] ?>">
                <input type="number" name="nota" min="1" max="5" required>
                <button type="submit">Valorar</button>
            </form>
        <?php endif; ?>
        <h3>Comentarios</h3>
        <ul>
            <?php foreach ($comentarios as $comentario): ?>
                <li><?= h($comentario['nombre']) ?>: <?= h($comentario['texto']) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</body>
</html>
