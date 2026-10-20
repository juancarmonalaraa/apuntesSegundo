<?php
// TODO: función calcularMayoria($totalEscanos) que devuelva intdiv($totalEscanos, 2) + 1.
function calcularMayoria($totalEscanos)
{
    return intdiv($totalEscanos, 2) + 1;
}
// TODO: función escapar($valor) para mostrar texto seguro en HTML.
function escapar($texto){
return htmlspecialchars((string) $texto, ENT_QUOTES, 'UTF-8');
}