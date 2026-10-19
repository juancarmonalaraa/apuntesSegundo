<?php
// Agenda INVENTADA para practicar PHP. Fechas 2026; no son eventos reales.
$provincias = ['Granada', 'Sevilla', 'Málaga'];
$partidos = ['PSOE', 'PP', 'Vox', 'Sumar', 'Podemos'];
$tipos = ['Mitin', 'Debate', 'Encuentro'];

// El array está ordenado cronológicamente. No hay que ordenar los actos.
$actos = [
    ['fecha'=>'2026-11-02', 'hora'=>'11:00', 'provincia'=>'Granada', 'partido'=>'PSOE', 'tipo'=>'Mitin', 'lugar'=>'Teatro Norte', 'aforo'=>250],
    ['fecha'=>'2026-11-02', 'hora'=>'18:00', 'provincia'=>'Sevilla', 'partido'=>'PP', 'tipo'=>'Encuentro', 'lugar'=>'Centro Cívico Sur', 'aforo'=>90],
    ['fecha'=>'2026-11-03', 'hora'=>'12:30', 'provincia'=>'Málaga', 'partido'=>'Vox', 'tipo'=>'Debate', 'lugar'=>'Salón del Puerto', 'aforo'=>180],
    ['fecha'=>'2026-11-03', 'hora'=>'19:00', 'provincia'=>'Granada', 'partido'=>'Sumar', 'tipo'=>'Encuentro', 'lugar'=>'Auditorio Sierra', 'aforo'=>120],
    ['fecha'=>'2026-11-04', 'hora'=>'10:00', 'provincia'=>'Sevilla', 'partido'=>'Podemos', 'tipo'=>'Mitin', 'lugar'=>'Pabellón Este', 'aforo'=>230],
    ['fecha'=>'2026-11-04', 'hora'=>'17:30', 'provincia'=>'Málaga', 'partido'=>'PSOE', 'tipo'=>'Encuentro', 'lugar'=>'Sala Alameda', 'aforo'=>100],
    ['fecha'=>'2026-11-05', 'hora'=>'11:00', 'provincia'=>'Granada', 'partido'=>'PP', 'tipo'=>'Debate', 'lugar'=>'Aula Magna', 'aforo'=>200],
    ['fecha'=>'2026-11-05', 'hora'=>'19:30', 'provincia'=>'Sevilla', 'partido'=>'Vox', 'tipo'=>'Mitin', 'lugar'=>'Teatro Central', 'aforo'=>300],
    ['fecha'=>'2026-11-06', 'hora'=>'11:30', 'provincia'=>'Málaga', 'partido'=>'Sumar', 'tipo'=>'Debate', 'lugar'=>'Palacio del Mar', 'aforo'=>160],
    ['fecha'=>'2026-11-06', 'hora'=>'18:00', 'provincia'=>'Granada', 'partido'=>'Podemos', 'tipo'=>'Encuentro', 'lugar'=>'Casa de Cultura', 'aforo'=>80],
    ['fecha'=>'2026-11-07', 'hora'=>'12:00', 'provincia'=>'Sevilla', 'partido'=>'PSOE', 'tipo'=>'Debate', 'lugar'=>'Salón Guadalquivir', 'aforo'=>170],
    ['fecha'=>'2026-11-07', 'hora'=>'19:00', 'provincia'=>'Málaga', 'partido'=>'PP', 'tipo'=>'Mitin', 'lugar'=>'Pabellón Oeste', 'aforo'=>320],
    ['fecha'=>'2026-11-08', 'hora'=>'10:30', 'provincia'=>'Granada', 'partido'=>'Vox', 'tipo'=>'Encuentro', 'lugar'=>'Centro Albaicín', 'aforo'=>70],
    ['fecha'=>'2026-11-08', 'hora'=>'18:30', 'provincia'=>'Sevilla', 'partido'=>'Sumar', 'tipo'=>'Mitin', 'lugar'=>'Teatro del Río', 'aforo'=>240],
    ['fecha'=>'2026-11-09', 'hora'=>'11:00', 'provincia'=>'Málaga', 'partido'=>'Podemos', 'tipo'=>'Debate', 'lugar'=>'Salón Litoral', 'aforo'=>140],
    ['fecha'=>'2026-11-09', 'hora'=>'18:30', 'provincia'=>'Granada', 'partido'=>'PSOE', 'tipo'=>'Debate', 'lugar'=>'Centro Zaidín', 'aforo'=>150],
    ['fecha'=>'2026-11-10', 'hora'=>'12:00', 'provincia'=>'Sevilla', 'partido'=>'PP', 'tipo'=>'Debate', 'lugar'=>'Sala Plaza', 'aforo'=>170],
    ['fecha'=>'2026-11-10', 'hora'=>'19:00', 'provincia'=>'Málaga', 'partido'=>'Vox', 'tipo'=>'Encuentro', 'lugar'=>'Centro Costero', 'aforo'=>110]
];