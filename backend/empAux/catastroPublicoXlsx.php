<?php

require '../../vendor/autoload.php';
include_once __DIR__ . '/../../backend/config.php';
include_once BASE_PATH . 'backend/session.php';
include_once BASE_PATH . 'backend/conexiones/eeffempauxdb_connection.php'; // Asegúrate de incluir la conexión a la base de datos


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

global $connEmpAux; // Usar la conexión global
$sql = "
SELECT RUC
    ,RAZON_SOCIAL
    ,TIPO_ORGANIZACION
    ,NUM_RESOLUCION_CALIFICACION
    ,FECHA_RESOLUCION
    ,SOFTWARE_FINANCIERO_Y_COMPUTACIONAL
    ,TRANSACCIONALES_Y_DE_PAGO
    ,TRANSPORTE_DE_ESPECIES_MONETARIAS_Y_DE_VALORES
    ,RED_Y_CAJEROS_AUTOMATICOS
    ,COBRANZAS
    ,SERVICIOS_CONTABLES
    ,GENERADORAS_DE_CARTERA
    ,ADMINISTRADORAS_Y_OPERADORAS_DE_TARJETAS
    ,GIRO_INMOBILIARIO
FROM catastro
ORDER BY RUC
";

$result = $connEmpAux->query($sql);

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$headers = [
     'RUC'
    ,'RAZON_SOCIAL'
    ,'TIPO_ORGANIZACION'
    ,'NUM_RESOLUCION_CALIFICACION'
    ,'FECHA_RESOLUCION'
    ,'SOFTWARE_FINANCIERO_Y_COMPUTACIONAL'
    ,'TRANSACCIONALES_Y_DE_PAGO'
    ,'TRANSPORTE_DE_ESPECIES_MONETARIAS_Y_DE_VALORES'
    ,'RED_Y_CAJEROS_AUTOMATICOS'
    ,'COBRANZAS'
    ,'SERVICIOS_CONTABLES'
    ,'GENERADORAS_DE_CARTERA'
    ,'ADMINISTRADORAS_Y_OPERADORAS_DE_TARJETAS'
    ,'GIRO_INMOBILIARIO'
];

$col = 'A';
foreach ($headers as $header) {
    $sheet->setCellValue($col . '1', $header);
    $sheet->getStyle($col . '1')->getFont()->setBold(true);
    $col++;
}

$fila = 2;

while ($row = $result->fetch_assoc()) {

    $sheet->setCellValue('A'.$fila, $row['RUC']);
    $sheet->setCellValue('B'.$fila, $row['RAZON_SOCIAL']);
    $sheet->setCellValue('C'.$fila, $row['TIPO_ORGANIZACION']);
    $sheet->setCellValue('D'.$fila, $row['NUM_RESOLUCION_CALIFICACION']);
    $sheet->setCellValue('E'.$fila, $row['FECHA_RESOLUCION']);
    $sheet->setCellValue('F'.$fila, $row['SOFTWARE_FINANCIERO_Y_COMPUTACIONAL']);
    $sheet->setCellValue('G'.$fila, $row['TRANSACCIONALES_Y_DE_PAGO']);
    $sheet->setCellValue('H'.$fila, $row['TRANSPORTE_DE_ESPECIES_MONETARIAS_Y_DE_VALORES']);
    $sheet->setCellValue('I'.$fila, $row['RED_Y_CAJEROS_AUTOMATICOS']);
    $sheet->setCellValue('J'.$fila, $row['COBRANZAS']);
    $sheet->setCellValue('K'.$fila, $row['SERVICIOS_CONTABLES']);
    $sheet->setCellValue('L'.$fila, $row['GENERADORAS_DE_CARTERA']);
    $sheet->setCellValue('M'.$fila, $row['ADMINISTRADORAS_Y_OPERADORAS_DE_TARJETAS']);
    $sheet->setCellValue('N'.$fila, $row['GIRO_INMOBILIARIO']);
    
    $fila++;
}

foreach (range('A', 'K') as $column) {
    $sheet->getColumnDimension($column)->setAutoSize(true);
}

$nombreArchivo = 'Catastro_Emp_Aux_Publico_' . date('Ymd_His') . '.xlsx';

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=\"$nombreArchivo\"");
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;