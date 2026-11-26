<?php
declare(strict_types=1);

class EquipoRepositorio
{
    public function __construct(private PDO $pdo) {}

    public function listarEquipos(): array
    {
        // Todos los equipos (aunque no tengan préstamos)
        $equipos = $this->pdo
            ->query('SELECT id, nombre, unidades FROM equipo ORDER BY nombre')
            ->fetchAll();

        // Un registro por cada prestamo abierto
        $abiertos = $this->pdo
            ->query('SELECT equipo_id FROM prestamo WHERE devuelto = 0')
            ->fetchAll();

        // Contador prestamos abiertos de cada equipo
        $contador = [];
        foreach ($abiertos as $fila) {
            $id = $fila['equipo_id'];
            $contador[$id] = ($contador[$id] ?? 0) + 1;
        }

        // Añado "disponibles" = unidades - abiertos
        $resultado = [];
        foreach ($equipos as $equipo) {
            $equipo['disponibles'] = $equipo['unidades'] - ($contador[$equipo['id']] ?? 0);
            $resultado[] = $equipo;
        }

        return $resultado;
    }

    public function listarPrestamos(): array
    {
        return $this->pdo->query(
            'SELECT p.id, e.nombre AS equipo, p.persona, p.devuelto
             FROM prestamo p
             INNER JOIN equipo e ON e.id = p.equipo_id
             ORDER BY p.id DESC'
        )->fetchAll();
    }

    public function prestar(int $equipoId, string $persona): void
    {
        // 1. ¿Existe el equipo?
        $stmt = $this->pdo->prepare('SELECT unidades FROM equipo WHERE id = :id');
        $stmt->execute([':id' => $equipoId]);
        $equipo = $stmt->fetch();

        if ($equipo === false) {
            throw new DomainException('El equipo seleccionado no existe.');
        }

        // 2. ¿Quedan unidades libres?
        $stmt = $this->pdo->prepare(
            'SELECT COUNT(*) FROM prestamo WHERE equipo_id = :id AND devuelto = 0'
        );
        $stmt->execute([':id' => $equipoId]);
        $abiertos = (int) $stmt->fetchColumn();

        if ($abiertos >= $equipo['unidades']) {
            throw new DomainException('No quedan unidades disponibles.');
        }

        // 3. Creo el préstamo abierto
        $stmt = $this->pdo->prepare(
            'INSERT INTO prestamo (equipo_id, persona, devuelto) VALUES (:equipo, :persona, 0)'
        );
        $stmt->execute([':equipo' => $equipoId, ':persona' => $persona]);
    }

    public function devolver(int $prestamoId): bool
    {
        // Solo actualiza si sigue abierto (devuelto = 0)
        $stmt = $this->pdo->prepare(
            'UPDATE prestamo SET devuelto = 1 WHERE id = :id AND devuelto = 0'
        );
        $stmt->execute([':id' => $prestamoId]);

        return $stmt->rowCount() > 0;
    }
}