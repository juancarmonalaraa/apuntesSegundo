<?php
declare(strict_types=1);
require_once __DIR__ . '/Producto.php';

$productos = [
    'P01' => new Producto('Pan integral', 1.50),
    'P02' => new Producto('Café molido', 4.80),
    'P03' => new Producto('Leche', 1.15),
    'P04' => new Producto('Queso', 3.20)
];
