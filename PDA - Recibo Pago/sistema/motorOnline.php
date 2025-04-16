<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

include "config.php";
$bd = "DESARROLLO";

if (isset($_REQUEST['recibo_pago']) && $_REQUEST['recibo_pago']) {
    $codemp = trim($_REQUEST['codemp']);
    if ($codemp != '0') {
        $sql_serie_recibo = "select Serie from $bd.dbo.NrosdeSerie where codEmpresa='" . $codemp . "' and codTipoDoc=7 and Estado=0";
        $dsl_serie_recibo = db_fetch_all($sql_serie_recibo);
        // echo $dsl_serie_recibo;
        if (!$dsl_serie_recibo || count($dsl_serie_recibo) === 0) {
            $serie_rec = "R";
        }
        foreach ($dsl_serie_recibo as $rec) {
            $serie_rec = trim($rec['Serie']);
        }

        $sql_cod_recibo = "select max(cod_recibo) as codUltimo from $bd.dbo.CAB_RECIBOPAGO where codemp='" . $codemp . "' and Estado not in('C') ";
        // echo $sql_cod_recibo;
        $dsl_cod_recibo = db_fetch_all($sql_cod_recibo);
        foreach ($dsl_cod_recibo as $val => $cod) {
            $result = trim($cod['codUltimo']);
        }
        $nuevo_cod = ($result === null) ? '0000001' : str_pad($result + 1, 7, '0', STR_PAD_LEFT);

        echo $resultado = $serie_rec . '-' . $nuevo_cod;
    } else
        echo "";
}
