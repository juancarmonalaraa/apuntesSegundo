<?php
// Precios de los componentes y accesorios del coche
$componentes = [
    "Modelo" => [
        "Compacto" => 15000,
        "Sedán" => 20000,
        "SUV" => 30000
    ],
    "Motor" => [
        "Gasolina 1.6L" => 2000,
        "Diesel 2.0L" => 3000,
        "Eléctrico 75 kWh" => 10000
    ],
    "Color" => [
        "Blanco" => 0,
        "Negro" => 500,
        "Azul Metálico" => 1000,
        "Rojo" => 1500
    ],
    "Llantas" => [
        "16\" Aleación" => 500,
        "18\" Aleación" => 1000,
        "20\" Deportivo" => 1500
    ],
    "Equipamiento" => [
        "Básico" => 0,
        "Confort" => 2000,
        "Premium" => 5000
    ],
    "Accesorios" => [
        "Techo Panorámico" => 1200,
        "Asientos de Cuero" => 1500,
        "Sistema de Sonido Premium" => 1000,
        "Cámara de 360°" => 800,
        "Paquete Deportivo" => 2000,
        "Control de Crucero Adaptativo" => 1500,
        "Sensores de Aparcamiento" => 600,
        "Maletero Eléctrico" => 900
    ]
];

// Códigos de descuento válidos
$codigosDescuento = [
    "AUTODESCUENTO5" => 5,   // 5% de descuento
    "CARSALE10" => 10,       // 10% de descuento
    "PROMO15" => 15          // 15% de descuento
];
?>