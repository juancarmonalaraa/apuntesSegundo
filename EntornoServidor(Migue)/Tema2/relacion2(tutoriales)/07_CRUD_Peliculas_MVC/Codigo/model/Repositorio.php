<?php
declare(strict_types=1);

class Repositorio
{
    public function __construct(private PDO $db) {}

    public function buscarUsuario(string $email): array|false
    {
        $sentencia = $this->db->prepare(
            'SELECT id, nombre, hash, rol FROM usuario WHERE email = ?'
        );
        $sentencia->execute([strtolower(trim($email))]);
        return $sentencia->fetch();
    }

    public function registrar(string $nombre, string $email, string $clave): void
    {
        $nombre = trim($nombre);
        $email = strtolower(trim($email));
        if ($nombre === '' || strlen($nombre) > 100
            || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($clave) < 8) {
            throw new InvalidArgumentException('Datos de registro no válidos.');
        }
        $sentencia = $this->db->prepare(
            'INSERT INTO usuario(nombre, email, hash, rol) VALUES (?, ?, ?, ?)'
        );
        $sentencia->execute([
            $nombre, $email, password_hash($clave, PASSWORD_DEFAULT), 'USUARIO'
        ]);
    }

    public function peliculas(): array
    {
        $sql = 'SELECT p.*, ROUND(AVG(v.nota), 1) AS media '
            . 'FROM pelicula p '
            . 'LEFT JOIN valoracion v ON v.pelicula_id = p.id '
            . 'GROUP BY p.id ORDER BY p.id DESC';
        return $this->db->query($sql)->fetchAll();
    }

    public function pelicula(int $id): array|false
    {
        $sentencia = $this->db->prepare(
            'SELECT id, titulo, sinopsis, anio FROM pelicula WHERE id = ?'
        );
        $sentencia->execute([$id]);
        return $sentencia->fetch();
    }

    public function comentarios(int $id): array
    {
        $sql = 'SELECT c.texto, u.nombre '
            . 'FROM comentario c JOIN usuario u ON u.id = c.usuario_id '
            . 'WHERE c.pelicula_id = ? ORDER BY c.id DESC';
        $sentencia = $this->db->prepare($sql);
        $sentencia->execute([$id]);
        return $sentencia->fetchAll();
    }

    public function guardarPelicula(?int $id, string $titulo, string $sinopsis, int $anio): void
    {
        $titulo = trim($titulo);
        $sinopsis = trim($sinopsis);
        if ($titulo === '' || strlen($titulo) > 150 || $sinopsis === ''
            || $anio < 1895 || $anio > 2100) {
            throw new InvalidArgumentException('Datos de película no válidos.');
        }
        if ($id === null) {
            $sentencia = $this->db->prepare(
                'INSERT INTO pelicula(titulo, sinopsis, anio) VALUES (?, ?, ?)'
            );
            $sentencia->execute([$titulo, $sinopsis, $anio]);
        } else {
            $sentencia = $this->db->prepare(
                'UPDATE pelicula SET titulo = ?, sinopsis = ?, anio = ? WHERE id = ?'
            );
            $sentencia->execute([$titulo, $sinopsis, $anio, $id]);
        }
    }

    public function borrarPelicula(int $id): void
    {
        $sentencia = $this->db->prepare('DELETE FROM pelicula WHERE id = ?');
        $sentencia->execute([$id]);
    }

    public function comentar(int $id, int $usuario, string $texto): void
    {
        $texto = trim($texto);
        if ($texto === '' || strlen($texto) > 1000) {
            throw new InvalidArgumentException('Comentario no válido.');
        }
        $sentencia = $this->db->prepare(
            'INSERT INTO comentario(pelicula_id, usuario_id, texto) VALUES (?, ?, ?)'
        );
        $sentencia->execute([$id, $usuario, $texto]);
    }

    public function valorar(int $id, int $usuario, int $nota): void
    {
        if ($nota < 1 || $nota > 5) {
            throw new InvalidArgumentException('La nota debe estar entre 1 y 5.');
        }
        $sql = 'INSERT INTO valoracion(pelicula_id, usuario_id, nota) VALUES (?, ?, ?) '
            . 'ON DUPLICATE KEY UPDATE nota = VALUES(nota)';
        $sentencia = $this->db->prepare($sql);
        $sentencia->execute([$id, $usuario, $nota]);
    }
}
