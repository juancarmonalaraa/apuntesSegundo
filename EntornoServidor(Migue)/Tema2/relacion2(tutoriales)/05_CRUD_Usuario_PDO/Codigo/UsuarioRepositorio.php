<?php
declare(strict_types=1);

class UsuarioRepositorio
{
    public function __construct(private PDO $pdo) {}

    public function listar(): array
    {
        return $this->pdo
            ->query('SELECT id, email, nombre FROM usuario ORDER BY id DESC')
            ->fetchAll();
    }

    public function crear(string $email, string $nombre, string $password): void
    {
        $email = strtolower(trim($email));
        $nombre = trim($nombre);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)
            || $nombre === '' || strlen($nombre) > 120 || strlen($password) < 8) {
            throw new InvalidArgumentException('Datos incorrectos; clave de al menos 8 caracteres.');
        }
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $sql = 'INSERT INTO usuario(email, nombre, clave_hash) VALUES (?, ?, ?)';
        $sentencia = $this->pdo->prepare($sql);
        $sentencia->execute([$email, $nombre, $hash]);
    }

    public function renombrar(int $id, string $nombre): void
    {
        $nombre = trim($nombre);
        if ($id < 1 || $nombre === '' || strlen($nombre) > 120) {
            throw new InvalidArgumentException('Id o nombre no válido.');
        }
        $sentencia = $this->pdo->prepare('UPDATE usuario SET nombre = ? WHERE id = ?');
        $sentencia->execute([$nombre, $id]);
    }

    public function borrar(int $id): void
    {
        if ($id < 1) {
            throw new InvalidArgumentException('Id no válido.');
        }
        $sentencia = $this->pdo->prepare('DELETE FROM usuario WHERE id = ?');
        $sentencia->execute([$id]);
    }
}
