<?php

function resultToArray($result)
{
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    return $data;
}

/**
 * ENTIDADES
 */
function obtenerEntidadesRIL()
{
    global $connEmpAux;
    $sql = "SELECT
                ID,
                FECHA_CORTE,
                RUC AS RUC_EMPRESA,
                RAZON_SOCIAL,
                NUMERO_PERIODO,
                CASE WHEN LINEA_BASE = 1 THEN 'SI' ELSE 'NO' END AS LINEA_BASE,
                CASE WHEN VALIDACION_SERVICIOS = 1 THEN 'SI' ELSE 'NO' END AS VALIDACION_SERVICIOS,
                ACTIVO,
                PASIVO,
                PATRIMONIO_NETO,
                RESULTADOS_DEL_EJERCICIO,
                INGRESOS_DE_ACTIVIDADES_ORDINARIAS,
                GASTOS,
                SEGMENTO_1,
                SEGMENTO_2,
                SEGMENTO_3,
                SEGMENTO_4,
                SEGMENTO_5
            FROM vw_full_entidad_data;";

    $stmt = $connEmpAux->prepare($sql);
    $stmt->execute();
    return $stmt->get_result();
}

/**
 * COOPERATIVAS
 */
function obtenerCoopsRIL()
{
    global $connEmpAux;
    $sql = "SELECT
                RUC_EMPRESA,
                RAZON_SOCIAL,
                SEGMENTO,
                NUM_ENTIDADES
            FROM vw_emp_aux_coops;";

    $stmt = $connEmpAux->prepare($sql);
    $stmt->execute();
    return $stmt->get_result();
}

/**
 * ESTADOS FINANCIEROS
 */
function obtenerEEFFRIL(){
    global $connEmpAux;
    $sql = "SELECT
                FECHA_CORTE,
                RUC AS RUC_EMPRESA,
                CODIGO_CTA,
                VALOR
            FROM vw_full_eeff_ruc_feccorte;";

    $stmt = $connEmpAux->prepare($sql);
    $stmt->execute();
    return $stmt->get_result();
}

/**
 * INDICADORES
 */
function obtenerIndicadoresRIL(){
    global $connEmpAux;
    $sql = "SELECT
                FECHA_CORTE,
                RUC AS RUC_EMPRESA,
                INDICADOR,
                VALOR
            FROM vw_full_ind_ruc_feccorte;";

    $stmt = $connEmpAux->prepare($sql);
    $stmt->execute();
    return $stmt->get_result();
}
