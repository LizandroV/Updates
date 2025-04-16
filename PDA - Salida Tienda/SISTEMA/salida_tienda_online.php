<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

include "config.php";
$bd = "DESARROLLO";

if (isset($_REQUEST['salida_tienda']) && $_REQUEST['salida_tienda']) {
    $alm_origen = $_REQUEST['stalmaceno'];
    if ($alm_origen != '0') {
        $sql_nuevoCodSal = "select max(codsal_tienda) as codUltimo from $bd.alm.cab_salidas_tienda ";
        $dsl_nuevoCodSal = db_fetch_all($sql_nuevoCodSal);
        foreach ($dsl_nuevoCodSal as $val => $valor) {
            $result = $valor['codUltimo'];
        }

        if (is_null($result))
            $NewCodigo_st = '0000001';
        else
            $NewCodigo_st = (string)str_pad($result + 1, 7, '0', STR_PAD_LEFT);

        echo $resultado = "S.T. N&ordm; " . $NewCodigo_st;
    } else
        echo "S.T. N&ordm;";
}
