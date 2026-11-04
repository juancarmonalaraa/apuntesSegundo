<?php
declare(strict_types=1);
require_once __DIR__ . '/Ventana.php';
class Puerta
{
    private bool $abierta = false;
    private Ventana $ventana;
    public function __construct()
    {
        $this->ventana = new Ventana();
    }
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
    public function getVentana(): Ventana
    {
        return $this->ventana;
    }
    public function __toString(): string
    {
        return ($this->abierta ? 'abierta' : 'cerrada') . ' · ventana ' . $this->ventana;
    }
}
