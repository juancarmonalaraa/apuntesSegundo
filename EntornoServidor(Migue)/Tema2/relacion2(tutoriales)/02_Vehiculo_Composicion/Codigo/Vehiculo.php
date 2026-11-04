<?php
declare(strict_types=1);
require_once __DIR__ . '/Puerta.php';
class Vehiculo
{
    private array $puertas = [];
    private bool $arrancado = false;
    public function __construct(private string $motor = 'Gasolina', private ?string $etiqueta = null, int $numeroPuertas = 4)
    {
        if ($numeroPuertas < 2 || $numeroPuertas > 6) {
            throw new InvalidArgumentException('Número de puertas entre 2 y 6');
        }
        for ($i = 0; $i < $numeroPuertas; $i++) {
            $this->puertas[] = new Puerta();
        }
    }
    public function getPuerta(int $i): Puerta
    {
        if (!isset($this->puertas[$i])) {
            throw new OutOfBoundsException('Puerta no encontrada');
        }
        return $this->puertas[$i];
    }
    public function encenderApagar(): bool
    {
        $this->arrancado = !$this->arrancado;
        return $this->arrancado;
    }
    public function cerrarVehiculo(): void
    {
        foreach ($this->puertas as $p) {
            $p->cerrar();
            $p->getVentana()->cerrar();
        }
    }
    public function puedeEntrarZBE(array $etiquetasPermitidas): bool
    {
        return $this->etiqueta !== null && in_array($this->etiqueta, $etiquetasPermitidas, true);
    }
    public function __toString(): string
    {
        $partes = [];
        foreach ($this->puertas as $i => $p) {
            $partes[] = 'Puerta ' . ($i + 1) . ': ' . $p;
        }
        return $this->motor . ' / etiqueta ' . ($this->etiqueta ?? 'sin etiqueta') . ' / ' . implode(' | ', $partes);
    }
}
