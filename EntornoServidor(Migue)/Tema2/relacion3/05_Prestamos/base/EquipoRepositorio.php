<?php
declare(strict_types=1);
class EquipoRepositorio
{
    public function __construct(private PDO $pdo)
    {
    }
    public function listarEquipos(): array
{
    return $this->pdo->query(
        'SELECT e.id, e.nombre, e.unidades,
                e.unidades - COUNT(p.id) AS disponibles
         FROM equipo e
         LEFT JOIN prestamo p ON p.equipo_id = e.id AND p.devuelto = 0
         GROUP BY e.id, e.nombre, e.unidades
         ORDER BY e.id'
    )->fetchAll();
}
    public function listarPrestamos(): array
    {
        // TODO: JOIN y orden de préstamos.
        throw new LogicException('Pendiente de implementar');
    }
    public function prestar(int $equipoId, string $persona): int
    {
        // TODO: transacción, bloqueo de equipo y comprobación de disponibilidad.
        throw new LogicException('Pendiente de implementar');
    }
    public function devolver(int $prestamoId): bool
    {
        // TODO: actualizar solo los préstamos abiertos.
        throw new LogicException('Pendiente de implementar');
    }
}
