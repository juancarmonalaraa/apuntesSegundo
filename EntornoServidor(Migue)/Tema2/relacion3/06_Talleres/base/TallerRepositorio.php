<?php
declare(strict_types=1);
class TallerRepositorio {
    public function __construct(private PDO $pdo) {}
    public function listarTalleres(): array {
        // TODO: aforo, plazas confirmadas y lista de espera.
        throw new LogicException('Pendiente de implementar');
    }
    public function listarInscripciones(): array {
        // TODO: JOIN y orden del enunciado.
        throw new LogicException('Pendiente de implementar');
    }
    public function inscribir(int $tallerId, string $nombre, string $email): string {
        // TODO: transacción, bloqueo, comprobación de aforo e inserción.
        throw new LogicException('Pendiente de implementar');
    }
    public function cancelar(int $inscripcionId): bool {
        // TODO: transacción, cancelación y promoción al primer lugar de espera.
        throw new LogicException('Pendiente de implementar');
    }
}
