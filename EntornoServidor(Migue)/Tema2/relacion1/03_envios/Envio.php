<?php
declare(strict_types=1);
abstract class Envio
{
    public function __construct(protected float $pesoKg)
    {
        if (!is_finite($pesoKg) || $pesoKg <= 0) {
            throw new InvalidArgumentException('Peso positivo obligatorio');
        }
    }
    abstract public function calcularPrecio(): float;
}
