<?php
declare(strict_types=1);

class Ventana
{
    private bool $abierta = false;

    public function abrir(): void
    {
        $this->abierta = true;
    }

    public function cerrar(): void
    {
        $this->abierta = false;
    }

    public function estaAbierta(): bool
    {
        return $this->abierta;
    }

    public function __toString(): string
    {
        return $this->abierta ? 'abierta' : 'cerrada';
    }
}
