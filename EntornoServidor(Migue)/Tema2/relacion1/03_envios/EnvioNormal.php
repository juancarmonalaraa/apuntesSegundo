<?php
declare(strict_types=1);
require_once __DIR__ . '/Envio.php';
class EnvioNormal extends Envio
{
    public function calcularPrecio(): float
    {
        return 4 + 1.50 * $this->pesoKg;
    }
}
