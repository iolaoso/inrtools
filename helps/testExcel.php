<?php

require_once '../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();

$sheet = $spreadsheet->getActiveSheet();

$sheet->setCellValue('A1', 'Prueba');
$sheet->setCellValue('B1', 'Excel');

$writer = new Xlsx($spreadsheet);

$writer->save(__DIR__ . '/test.xlsx');

echo 'OK';