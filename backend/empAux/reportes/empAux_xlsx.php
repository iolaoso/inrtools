<?php

include_once __DIR__ . '/../../config.php';
include_once BASE_PATH . 'backend/conexiones/eeffempauxdb_connection.php';
//include_once __DIR__ . '/xlsx/exportador.php';

$reporte = $_GET['reporte'] ?? '';

switch ($reporte) {

    case 'CATASTRO_GENERAL':
        require 'xlsx/rpt_catastro_general.php';
        break;

    case 'EMPRESAS_NUEVAS':
        require 'xlsx/rpt_empresas_nuevas.php';
        break;

    case 'CON_CONDICION':
        require 'xlsx/rpt_con_condicion.php';
        break;

    case 'SIN_CONDICION':
        require 'xlsx/rpt_sin_condicion.php';
        break;

    case 'SEGUIMIENTOS':
        require 'xlsx/rpt_seguimientos.php';
        break;

    case 'INCUMPLIMIENTOS':
        require 'xlsx/rpt_incumplimientos.php';
        break;

    case 'PROCESOS_DNR':
        require 'xlsx/rpt_procesos_dnr.php';
        break;

    case 'PORTAFOLIO':
        require 'xlsx/rpt_portafolio.php';
        break;

    default:
        die('Reporte no definido');
}