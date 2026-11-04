<?php
declare(strict_types=1);
require_once __DIR__ . '/Envio.php';
require_once __DIR__ . '/Rastreable.php';
class EnvioExpress extends Envio implements Rastreable
{
    public function __construct(float $pesoKg, private string $codigo)
    {
        parent::__construct($pesoKg);
    }
    public function calcularPrecio(): float
    {
        return 9 + 2.75 * $this->pesoKg;
    }
    public function codigoSeguimiento(): string
    {
        return $this->codigo;
    }
}
