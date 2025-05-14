<?
session_start();
require('../../../includes/dbmssql_cfg.php');
require('../../../includes/class_caja_chica.php');

$info_cc_s = array();
$info_cc_s = array(
    'cod_ccs'       =>      trim($_REQUEST['cod_ccs']),
    'cod_resp'      =>      trim($_REQUEST['cod_resp']),
    'CodMon'        =>      trim($_REQUEST['CodMon']),
    'Monto'         =>      trim($_REQUEST['Monto']),
    'CodEmp'        =>      trim($_REQUEST['CodEmp']),
    'cod_categ'     =>      trim($_REQUEST['cod_categ']),
    'cod_concepto'  =>      trim($_REQUEST['cod_concepto']),
    'comentario'    =>      trim($_REQUEST['comentario']),
    'usureg'        =>      trim($_REQUEST['usureg'])
);

$cajaChica = new cajaChica();

if ($_REQUEST['guardarReciboEntrega']) {
    $cajaChica->insert_recibo_entrega($info_cc_s);
}

if ($_REQUEST['filtrarMovimientos']) {
    $cajaChica->filtar_movimiento(trim($_REQUEST['texto']));
}

if ($_REQUEST['verMovimientos']) {
    $cajaChica->ver_movimientos(trim($_REQUEST['codemp']));
}

if ($_REQUEST['actualizarRecibo']) {
    $cajaChica->actualizarRecibo($info_cc_s);
}

if ($_REQUEST['cancelarRecibo']) {
    $cajaChica->cancelarRecibo(trim($_REQUEST['idRecibo']), trim($_REQUEST['tipoCaja']));
}
