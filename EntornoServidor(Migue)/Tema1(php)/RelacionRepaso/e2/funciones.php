<?php
// TODO: crea una función euros($numero) para mostrar cantidades con 2 decimales.

function euros($numero){
    $resultado = number_format($numero, 2); 
    return $resultado;
    }

// TODO: crea una función escapar($texto) que use htmlspecialchars.

function escapar($texto){
return htmlspecialchars((string) $texto, ENT_QUOTES, 'UTF-8');
}