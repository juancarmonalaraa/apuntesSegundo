<?php
declare(strict_types=1);

class ProductoRepositorio
{
    public function __construct(private PDO $pdo) {}

    public function todos(): array
    {
        return $this->pdo
            ->query('SELECT id, nombre, precio, stock FROM producto ORDER BY id DESC')
            ->fetchAll();
    }

    public function crear(string $nombre, float $precio, int $stock): void
    {
        $nombre = trim($nombre);
        if ($nombre === '' || strlen($nombre) > 120
            || !is_finite($precio) || $precio < 0 || $stock < 0) {
            throw new InvalidArgumentException('Producto no válido.');
        }
        $sentencia = $this->pdo->prepare(
            'INSERT INTO producto(nombre, precio, stock) VALUES (?, ?, ?)'
        );
        $sentencia->execute([$nombre, $precio, $stock]);
    }

    public function actualizarPrecio(int $id, float $precio): void
    {
        if ($id < 1 || !is_finite($precio) || $precio < 0) {
            throw new InvalidArgumentException('Id o precio no válido.');
        }
        $sentencia = $this->pdo->prepare(
            'UPDATE producto SET precio = ? WHERE id = ?'
        );
        $sentencia->execute([$precio, $id]);
    }

    public function eliminar(int $id): void
    {
        if ($id < 1) {
            throw new InvalidArgumentException('Id no válido.');
        }
        $sentencia = $this->pdo->prepare('DELETE FROM producto WHERE id = ?');
        $sentencia->execute([$id]);
    }
}
