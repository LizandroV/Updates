<?php
header('Content-Type: text/xml; charset=utf-8');///ISO-8859-1
session_start();
require('../../../includes/dbmssql_cfg.php');
require('../../../includes/class_reporte_stock_tienda.php');

$info_stock = array();
$info_stock = array(
				'CodEmp'	=>trim($_REQUEST['CodEmp']),
	   			'CodAlmacen'=>trim($_REQUEST['CodAlmacen']),
	   			'Prod'		=>trim($_REQUEST['Prod']),
	   			'Part'		=>trim($_REQUEST['Part']),
				'lote'		=>trim($_REQUEST['lote']),
	   			'contenedor'=>trim($_REQUEST['contenedor'])
				);

$lista_stock=new ReporteStockTienda(); 
$lista_stock->listado_stock_tienda($info_stock);   

?>