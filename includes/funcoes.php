<?php
function e($texto)
{
    return htmlspecialchars((string) $texto, ENT_QUOTES, 'UTF-8');
}

function formatar_preco($valor)
{
    return number_format((float) $valor, 2, ',', '.') . ' €';
}