<?php
session_start();
require('../../../includes/dbmssql_cfg.php');
header('Content-Type: application/json');

// Detalle del Recibo //
$idRecibo   = trim($_REQUEST['idRecibo']);
$tipoCaja       = trim($_REQUEST['tipoCaja']);

if ($_REQUEST['verRecibo']) {

    if ($tipoCaja == "1") {
        $detalle_ree = "SELECT CCS.cod_cc_sal, CCS.CodEmp, R.tipo_resp, CCS.cod_resp, R.Dni, R.Nombre, CCS.CodMon, CCS.Monto, CCS.tipo_cambio, 
        CCS.cod_categ, CCS.cod_concepto, CCS.comentario, CCS.FecReg, COUNT(A.cod_adjunto) AS TotalAdjuntos
        FROM REE.CAJACHICA_SAL CCS
        LEFT JOIN ree.REE_RESPONSABLES R ON CCS.cod_resp = R.cod_resp
        LEFT JOIN ree.REE_ADJUNTOS A ON CCS.cod_cc_sal = A.cod_cc_sal
        WHERE CCS.Estado != 'C' AND CCS.cod_cc_sal = $idRecibo
        GROUP BY CCS.cod_cc_sal, CCS.CodEmp, R.tipo_resp, CCS.cod_resp, R.Dni, R.Nombre, CCS.CodMon, CCS.Monto, 
        CCS.tipo_cambio, CCS.cod_categ, CCS.cod_concepto, CCS.comentario, CCS.FecReg";
    }

    function convertir_fecha($fecha_datetime)
    {
        // Convertir el mes de texto a su formato numérico (Apr -> 04)
        $meses = array(
            "Jan" => "01",
            "Feb" => "02",
            "Mar" => "03",
            "Apr" => "04",
            "May" => "05",
            "Jun" => "06",
            "Jul" => "07",
            "Aug" => "08",
            "Sep" => "09",
            "Oct" => "10",
            "Nov" => "11",
            "Dec" => "12"
        );

        // Dividir la fecha
        $fecha_partes = explode(" ", trim($fecha_datetime));

        // Obtener los valores del mes y día
        $mes_num = $meses[$fecha_partes[0]]; // Ejemplo: "Apr" -> "04"
        $dia = str_pad($fecha_partes[1], 2, "0", STR_PAD_LEFT); // Asegurar dos dígitos

        // Obtener el año, hora y minutos
        $anio = $fecha_partes[2];
        $hora_minuto_segundos = substr($fecha_partes[3], 0, 8); // "04:01:21"
        $periodo = substr($fecha_partes[3], 8); // "PM" o "AM"

        // Crear una fecha en formato "Y-m-d H:i:s" para ser compatible con DateTime
        $fecha_completa = "$anio-$mes_num-$dia $hora_minuto_segundos $periodo";

        // Crear el objeto DateTime
        $date = DateTime::createFromFormat('Y-m-d h:i:s A', $fecha_completa);

        // Verificar si la fecha es válida
        if ($date instanceof DateTime) {
            return $date->format('Y-m-d'); // Ejemplo: "22/04/2025 04:01 PM" d/m/Y h:i A
        } else {
            return 'Fecha inválida';
        }
    }

    $query_detalle = $_SESSION['dbmssql']->getAll($detalle_ree);
    foreach ($query_detalle as $id_ree => $all_ree) {
        $cod_cc_sal     = trim($all_ree['cod_cc_sal']);
        $CodEmp         = trim($all_ree['CodEmp']);
        $tipo_resp      = trim($all_ree['tipo_resp']);
        $cod_resp       = trim($all_ree['cod_resp']);
        $Dni            = trim($all_ree['Dni']);
        $Nombre         = utf8_encode(trim($all_ree['Nombre']));
        $CodMon         = trim($all_ree['CodMon']);
        $Monto          = trim($all_ree['Monto']);
        $tipo_cambio    = trim($all_ree['tipo_cambio']);
        $cod_categ      = trim($all_ree['cod_categ']);
        $cod_concepto   = trim($all_ree['cod_concepto']);
        $comentario     = utf8_encode(trim($all_ree['comentario']));
        $FecReg         = trim($all_ree['FecReg']);
        $Fecha          = convertir_fecha($FecReg);
        $TotalAdjuntos  = trim($all_ree['TotalAdjuntos']);

        $datos = array(
            "cod_cc_sal"    => $cod_cc_sal,
            "CodEmp"        => $CodEmp,
            "tipo_resp"     => $tipo_resp,
            "cod_resp"      => $cod_resp,
            "Dni"           => $Dni,
            "Nombre"        => $Nombre,
            "CodMon"        => $CodMon,
            "Monto"         => $Monto,
            "tipo_cambio"   => $tipo_cambio,
            "cod_categ"     => $cod_categ,
            "cod_concepto"  => $cod_concepto,
            "comentario"    => $comentario,
            "FecReg"        => $Fecha,
            "TotalAdjuntos" => $TotalAdjuntos
        );
    }
    //Devolvemos el array pasado a JSON como objeto
    echo json_encode($datos, JSON_FORCE_OBJECT);
}
