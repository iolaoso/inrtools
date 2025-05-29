<?php
// psi_actions.php
include_once '../session.php';
include_once '../config.php';
include_once 'psiList.php'; // Incluye el archivo donde está definida la función
header('Content-Type: application/json'); // Establecer el tipo de contenido a JSON

header('Content-Type: application/json');

$accion = $_POST['accion'] ?? '';

switch ($accion) {
    case 'guardar':
        // Recoger datos del formulario (ajusta según campos que envíes)
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;

        // Campos a guardar / actualizar (ajustar según tu formulario)
        $data = [
            'NUMERO' => $_POST['NUMERO'] ?? null,
            'COD_UNICO' => $_POST['COD_UNICO'] ?? null,
            'RUC' => $_POST['RUC'] ?? null,
            'RAZON_SOCIAL' => $_POST['RAZON_SOCIAL'] ?? null,
            'SEGMENTO' => $_POST['SEGMENTO'] ?? null,
            'ZONAL' => $_POST['ZONAL'] ?? null,
            'ESTADO_JURIDICO' => $_POST['ESTADO_JURIDICO'] ?? null,
            'TIPO_SUPERVISION' => $_POST['TIPO_SUPERVISION'] ?? null,
            'FECHA_INICIO' => $_POST['FECHA_INICIO'] ?? null,
            'FECHA_FIN' => $_POST['FECHA_FIN'] ?? null,
            'ANIO_INICIO' => $_POST['ANIO_INICIO'] ?? null,
            'MES_INICIO' => $_POST['MES_INICIO'] ?? null,
            'ANIO_VENCIMIENTO' => $_POST['ANIO_VENCIMIENTO'] ?? null,
            'MES_VENCIMIENTO' => $_POST['MES_VENCIMIENTO'] ?? null,
            'TRIMESTRE' => $_POST['TRIMESTRE'] ?? null,
            'ESTADO_PSI' => $_POST['ESTADO_PSI'] ?? null,
            'VIGENCIA_PSI' => $_POST['VIGENCIA_PSI'] ?? null,
            'FECHA_APROBACION_PLAN_FISICO' => $_POST['FECHA_APROBACION_PLAN_FISICO'] ?? null,
            'NUM_INFORME' => $_POST['NUM_INFORME'] ?? null,
            'FECHA_INFORME' => $_POST['FECHA_INFORME'] ?? null,
            'NUM_RESOLUCION' => $_POST['NUM_RESOLUCION'] ?? null,
            'FECHA_RESOLUCION' => $_POST['FECHA_RESOLUCION'] ?? null,
            'NUM_RESOLUCION_AMPLIACION' => $_POST['NUM_RESOLUCION_AMPLIACION'] ?? null,
            'FECHA_RESOLUCION_AMPLIACION' => $_POST['FECHA_RESOLUCION_AMPLIACION'] ?? null,
            'FECHA_ULTIMO_BALANCE' => $_POST['FECHA_ULTIMO_BALANCE'] ?? null,
            'ACTIVOS' => $_POST['ACTIVOS'] ?? null,
            'ULTIMO_RIESGO' => $_POST['ULTIMO_RIESGO'] ?? null,
            'NUM_RESOLUCION_FIN_PSI' => $_POST['NUM_RESOLUCION_FIN_PSI'] ?? null,
            'FECHA_RESOLUCION_FIN_PSI' => $_POST['FECHA_RESOLUCION_FIN_PSI'] ?? null,
            'MOTIVO_CIERRE' => $_POST['MOTIVO_CIERRE'] ?? null,
            'ESTRATEGIA_SUPERVISION' => $_POST['ESTRATEGIA_SUPERVISION'] ?? null,
            'FECHA_CORTE_INFORMACION' => $_POST['FECHA_CORTE_INFORMACION'] ?? null,
            'ULTIMO_CORTE' => $_POST['ULTIMO_CORTE'] ?? 2,
            'EST_REGISTRO' => 'ACT',
            'USR_CREACION' => $_POST['USR_CREACION'] ?? 'webuser',
            'FECHA_ACTUALIZACION' => date('Y-m-d H:i:s')
        ];

        if ($id > 0) {
            $exito = actualizarPsi($id, $data);
        } else {
            $data['FECHA_CREACION'] = date('Y-m-d H:i:s');
            $exito = insertarPsi($data);
        }

        echo json_encode(['success' => $exito]);
        break;

    case 'eliminar':
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        if ($id > 0) {
            $exito = eliminarPsi($id);
            echo json_encode(['success' => $exito]);
        } else {
            echo json_encode(['success' => false, 'error' => 'ID inválido']);
        }
        break;

    case 'obtener':
        $datos = obtenerPsiActivos();
        echo json_encode(['success' => true, 'data' => $datos]);
        break;

    default:
        echo json_encode(['success' => false, 'error' => 'Acción no válida']);
}