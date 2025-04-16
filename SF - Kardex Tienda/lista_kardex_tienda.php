<?php
header('Content-Type: text/xml; charset=utf-8');///ISO-8859-1
session_start();
require('../../../includes/dbmssql_cfg.php');
require('../../../includes/class_kardex_tienda.php');

$info_st = array();
$info_st = array(
	   			'codalmacen'	=>trim($_REQUEST['almacen']),
				'codempresa'	=>trim($_REQUEST['empresa']),
				'codproducto'	=>trim($_REQUEST['producto']),
				'prod'			=>trim($_REQUEST['partida']),
				'fecinicio'		=>trim($_REQUEST['inicio']),
				'fecfin'		=>trim($_REQUEST['fin'])
				);

$list_st=new KardexTienda(); 
$list_st->listado_kardex_tienda($info_st);   

?>