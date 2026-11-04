<?php
declare(strict_types=1);

class Perro
{
    public function __construct(
        private string $nombre = 'Sin nombre',
        private int $edad = 0,
        private string $raza = 'Mestizo',
        private string $sexo = 'Sin especificar'
    ) {
        if ($edad < 0) {
            throw new InvalidArgumentException('La edad no puede ser negativa');
        }
    }

    public function ladrar(): string
    {
        return '¡Guau, guau!';
    }

    public function calcularEdadHumana(): int
    {
        return $this->edad * 7;
    }

    public function cumplirAnios(): int
    {
        $this->edad++;
        return $this->edad;
    }

    public function __toString(): string
    {
        return "{$this->nombre} · {$this->raza} · {$this->edad} años "
            . "(equivalencia didáctica: {$this->calcularEdadHumana()}) · {$this->sexo}";
    }
}
