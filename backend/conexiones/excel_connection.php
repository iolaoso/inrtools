<?php
require __DIR__ . '/../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

// Definir la función conectarExcel 
function conectarExcel($filePath = null)
{
    // Aquí debes implementar la lógica para conectar y devolver el objeto Spreadsheet
    // Ejemplo básico (ajusta según tu implementación real):
    $inputFileName = $filePath;
    $reader = IOFactory::createReader('Xlsx');
    return $reader->load($inputFileName);
}
