<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

// Columnas que siempre deben exportarse como texto
const XLSX_COLUMNAS_TEXTO=[
    'RUC',
    'IDENTIFICACION',
    'NUM_RESOLUCION_CALIFICACION',
    'NUM_RESOLUCION',
    'OFICIO_SEGUIMIENTO',
    'NUM_ACTA_REUNION',
    'NUM_INFORME_SEG',
    'NUM_OFICIO',
    'CODIGO',
    'NICKNAME'
];

function exportarExcel(mysqli $conn,string $sql,string $nombreArchivo,string $tituloReporte):void{
    // Ejecutar consulta
    $result=$conn->query($sql);
    if(!$result){die('Error SQL: '.$conn->error);}

    // Crear libro
    $spreadsheet=new Spreadsheet();
    $sheet=$spreadsheet->getActiveSheet();
    $sheet->setTitle('Reporte');

    // Encabezado institucional
    $sheet->mergeCells('A1:H1');
    $sheet->mergeCells('A2:H2');
    $sheet->mergeCells('A3:H3');
    $sheet->setCellValue('A1','SUPERINTENDENCIA DE ECONOMÍA POPULAR Y SOLIDARIA');
    $sheet->setCellValue('A2','INTENDENCIA NACIONAL DE RIESGOS');
    $sheet->setCellValue('A3',mb_strtoupper($tituloReporte));
    $sheet->setCellValue('A4','Fecha de generación: '.date('d/m/Y H:i:s'));

    // Estilos encabezado institucional
    $sheet->getStyle('A1:H3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('A1:H3')->getFont()->setBold(true);
    $sheet->getStyle('A1:H1')->getFont()->setSize(14);
    $sheet->getStyle('A2:H2')->getFont()->setSize(12);
    $sheet->getStyle('A3:H3')->getFont()->setSize(12);

    // Encabezados columnas
    $campos=$result->fetch_fields();
    $col=1;
    foreach($campos as $campo){
        $sheet->setCellValue(Coordinate::stringFromColumnIndex($col).'6',$campo->name);
        $col++;
    }

    // Estilo encabezados
    $ultimaColumna=$sheet->getHighestColumn();
    $sheet->getStyle("A6:{$ultimaColumna}6")->getFont()->setBold(true);
    $sheet->getStyle("A6:{$ultimaColumna}6")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('1F4E78');
    $sheet->getStyle("A6:{$ultimaColumna}6")->getFont()->getColor()->setARGB('FFFFFF');
    $sheet->getStyle("A6:{$ultimaColumna}6")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    // Encabezados columnas


    // Cargar datos
    $fila=7;
    while($row=$result->fetch_assoc()){
        $col=1;
        foreach($row as $valor){
            $nombreCampo=$campos[$col-1]->name;

            if(in_array($nombreCampo,XLSX_COLUMNAS_TEXTO)){
                $sheet->setCellValueExplicit(
                    Coordinate::stringFromColumnIndex($col).$fila,
                    (string)$valor,
                    DataType::TYPE_STRING
                );
            }else{
                $sheet->setCellValue(
                    Coordinate::stringFromColumnIndex($col).$fila,
                    $valor
                );
            }
            $col++;
        }
        $fila++;
    }

    // Bordes
    $sheet->getStyle("A6:{$ultimaColumna}{$sheet->getHighestRow()}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

    // Autofiltro
    $sheet->setAutoFilter("A6:{$ultimaColumna}{$sheet->getHighestRow()}");

    // Congelar encabezados
    $sheet->freezePane('A7');

    // Ajustar ancho columnas
    foreach(range('A',$ultimaColumna) as $columnID){
        $sheet->getColumnDimension($columnID)->setAutoSize(true);
    }

    // Centrar fila encabezados
    $sheet->getStyle("A6:{$ultimaColumna}6")->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

    // Formato general
    $sheet->getDefaultRowDimension()->setRowHeight(-1);

    // Metadatos
    $spreadsheet->getProperties()
        ->setCreator('SEPS')
        ->setLastModifiedBy('SEPS')
        ->setTitle($tituloReporte)
        ->setSubject($tituloReporte)
        ->setDescription($tituloReporte);

    // Descargar
    $nombreFinal=$nombreArchivo.'_'.date('Ymd').'.xlsx';
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="'.$nombreFinal.'"');
    header('Cache-Control: max-age=0');

    // Limpiar buffers
    if(ob_get_length()){
        ob_end_clean();
}

    $writer=new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
}