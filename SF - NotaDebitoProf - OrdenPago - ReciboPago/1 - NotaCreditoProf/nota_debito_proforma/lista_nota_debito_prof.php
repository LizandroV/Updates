<?php
session_start();
require('../../../includes/dbmssql_cfg.php');
require('../../../includes/class_notadebito_prof.php');

$info_oserv = array();
$info_oserv = array(
    'order_factura'     => trim($_REQUEST['order_factura']),
    'codNegocio'        => trim($_REQUEST['codNegocio']),
    'dateInicio'        => trim($_REQUEST['dateInicio']),
    'cod_motivo'        => trim($_REQUEST['cod_motivo']),
    'txt_subtotal'      => trim($_REQUEST['txt_subtotal']),
    'usuario'           => trim($_REQUEST['usuario']),
    'comentario'        => trim($_REQUEST['comentario']),
    'monto_total'       => trim($_REQUEST['monto_total']),
    'monto_al_cambio'   => trim($_REQUEST['monto_al_cambio']),
    'numero_nota'       => trim($_REQUEST['numero_nota']),
    'codigoNota'        => trim($_REQUEST['codigoNota']),
    'arreglo'           => trim($_REQUEST['arreglo']),
    'tipo_almacen'      => trim($_REQUEST['tipo_almacen'])
);

$nota = new NotaDebitoProforma();
if ($_REQUEST['insertar']) {
    $nota->insert_nota_debito_cabecera_detalle_prof($info_oserv);
}
if ($_REQUEST['update']) {
    $nota->update_nota_debito_cabecera_detalle_prof($info_oserv);
}
