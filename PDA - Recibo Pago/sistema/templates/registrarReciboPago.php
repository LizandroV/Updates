<?php

// error_reporting(E_ALL);
// ini_set('display_errors', 1);

include "../config.php";
$bd = "DESARROLLO";

$cod_emp = trim($_POST['codemp']);
$cod_neg = trim($_POST['codneg']);
$cod_cli = trim($_POST['cod_cli']);
$fecha   = trim($_POST['fecha_recibo']);
$cod_mon = trim($_POST['cod_moneda']);
$importe = trim($_POST['importe']);
$txt_obs = trim($_POST['obs']);
$cod_usu = trim($_POST['usuario']);

$txt_obs = utf8_decode($txt_obs); //FORMATO CON TILDES Y Ñ
// OBTENIENDO NUEVO CODIGO DE COTIZACION DE CLIENTES
$sql_NuevoCod = "select max(cod_recibo) as codUltimo from $bd.dbo.CAB_RECIBOPAGO where codemp='$cod_emp' and Estado not in('C') ";
// echo $sql_NuevoCod;

$dsl_NuevoCod = db_fetch_all($sql_NuevoCod);
foreach ($dsl_NuevoCod as $valor => $a) {
    $result = $a['codUltimo'];
}

if (is_null($result)) {
    $cod_recibo = 1;
    $nuevo_cod = '0000001';
} else {
    $cod_recibo = $result + 1;
    $nuevo_cod = (string)str_pad($result + 1, 7, '0', STR_PAD_LEFT);
}

if ($cod_neg == "") {
    $cod_neg = "NULL";
}

if ($cod_cli == "") {
    $cod_cli = "NULL";
}

$sql_serie_recibo = "select Serie from $bd.dbo.NrosdeSerie where codEmpresa='$cod_emp' and codTipoDoc=7 and Estado=0";
// echo $sql_serie_recibo;
$dsl_serie_recibo = db_fetch_all($sql_serie_recibo);
if (!$dsl_serie_recibo || count($dsl_serie_recibo) === 0) {
    $serie_rec = "R";
}
foreach ($dsl_serie_recibo as $rec) {
    $serie_rec = trim($rec['Serie']);
}

$recibo_pago = $serie_rec . '-' . $nuevo_cod;

$insert_cab = "insert into $bd.dbo.CAB_RECIBOPAGO(cod_recibo, codneg, codemp,
    codcli, fecha_recibo, recibopago, codmone, importe, usuareg, fechareg, Estado, obs)
    values(
    " . $cod_recibo . ",
    " . $cod_neg . ",
    " . $cod_emp . ",
    " . $cod_cli . ",
    convert(date,'" . $fecha . "'),
    '" . $recibo_pago . "',
    '" . $cod_mon . "',
    '" . $importe . "',
    '" . $cod_usu . "',
    getdate(),
    'I',
    '" . $txt_obs . "') ";

$resultado = db_query($insert_cab);

if ($resultado === false) {
    echo "ERROR AL REGISTRAR";
} else {
    echo "REGISTRADO";
}
