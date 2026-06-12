<?php

/** Escapa texto para mostrar en HTML */
function h($value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}


function normalizarNombreColumna($texto){
    $texto = strtoupper($texto);
    $texto = preg_replace('/[^A-Z0-9]/', '_', $texto);
    $texto = preg_replace('/_+/', '_', $texto);
    return trim($texto, '_');
}
