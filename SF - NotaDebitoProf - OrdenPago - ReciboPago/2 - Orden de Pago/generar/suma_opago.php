<?php
session_start();
header('Content-Type: text/xml; charset=ISO-8859-1');
require('../../../includes/dbmssql_cfg.php');

$codFacturas	= $_REQUEST['facts'];
$codNegocio		= $_REQUEST['codNegocio'];
$codEmpresa		= $_REQUEST['codEmpresa'];
$codCliente		= $_REQUEST['codCliente'];
$valorProceso	= $_REQUEST['valorProceso'];

$cod_los_documentos	= $_REQUEST['facts'];

//Declarando los tipo de arreglosDocs
$arreglo_fac = array();
$arreglo_pro = array();
$arreglo_cre = array();
$arreglo_deb = array();
$arreglo_crep = array();
$arreglo_debp = array();

$f = 0;
$p = 0;
$c = 0;
$d = 0;
$cp;
$dp;

//Convirtiendo esa cadena de texto dentro de una array.
$cod_in_arreglo = explode("-", $cod_los_documentos);
foreach ($cod_in_arreglo as $key => $value) {
	if (substr($value, 0, 1) == 'F') {
		$value = str_replace('F_', '', $value);
		$arreglo_fac[$f] = $value;
		$f++;
	}
	if (substr($value, 0, 1) == 'P') {
		$value = str_replace('P_', '', $value);
		$arreglo_pro[$p] = $value;
		$p++;
	}

	///Notas
	if (substr($value, 0, 1) == 'C') {
		$value = str_replace('C_', '', $value);
		$arreglo_cre[$c] = $value;
		$c++;
	}
	if (substr($value, 0, 1) == 'D') {
		$value = str_replace('D_', '', $value);
		$arreglo_deb[$d] = $value;
		$d++;
	}

	//NOTA DE CREDITO PROFORMA
	if (substr($value, 0, 1) == 'H') {
		$value = str_replace('H_', '', $value);
		$arreglo_crep[$cp] = $value;
		$cp++;
	}
	//NOTA DE DEBITO PROFORMA
	if (substr($value, 0, 1) == 'I') {
		$value = str_replace('I_', '', $value);
		$arreglo_debp[$dp] = $value;
		$dp++;
	}
}

//Convirtiendo a texto los 4 arreglos.
$txt_arreglo_fac = implode(',', $arreglo_fac);
$txt_arreglo_pro = implode(',', $arreglo_pro);
$txt_arreglo_cre = implode(',', $arreglo_cre);
$txt_arreglo_deb = implode(',', $arreglo_deb);
$txt_arreglo_crep = implode(',', $arreglo_crep);
$txt_arreglo_debp = implode(',', $arreglo_debp);

if ($valorProceso == 'F') {
	$procedure = 'BF_ObtenerDatos_Fac';
	$codFacturas = $txt_arreglo_fac;
}
if ($valorProceso == 'P') {
	$procedure = 'BF_ObtenerDatos_Prof';
	$codFacturas = $txt_arreglo_pro;
}
$sql_pro = " execute " . $procedure . " " . $codNegocio . ",
										" . $codEmpresa . ",
										" . $codCliente . ",
										'" . str_replace('-', ',', $codFacturas) . "' ";
$query_pro  = $_SESSION['dbmssql']->getAll($sql_pro);

$suma = 0.0;

if ($_REQUEST['totalCobrar']) {
	//////////////////////////////Factura o Proforma//////////////////////////////////////////
	if (strlen($txt_arreglo_fac) > 0  or strlen($txt_arreglo_pro) > 0) {
		foreach ($query_pro as $pro => $descripcion) {
			$impxcobrar	= round($descripcion['impxcobrar'], 2);
			$suma = $suma + $impxcobrar;
		}
	}

	/////////////////////////////////Nota de Credito//////////////////////////////////////////
	if (strlen($txt_arreglo_cre) > 0) {
		$tipodoc_Proceso = 'C';
		$procedure = 'BF_ObtenerDatos_Cre';
		$codNotasCredito = $txt_arreglo_cre;
		$label = 'NC:';
		///Este query sera solo para Notas de credito
		$sql_pro_cre = " execute BF_ObtenerDatos_Cre 	" . $codNegocio . ",
														" . $codEmpresa . ",
														" . $codCliente . ",
														'" . str_replace('-', ',', $codNotasCredito) . "' ";
		$query_pro_cre  = $_SESSION['dbmssql']->getAll($sql_pro_cre);
		foreach ($query_pro_cre as $pro => $descripcion_cre) {
			$impxcobrar	= round($descripcion_cre['impxcobrar'], 2);
			$suma = $suma - $impxcobrar;
		}
	}

	////////////////////Nota de Debito/////////////////////////////////////////////
	if (strlen($txt_arreglo_deb) > 0) {
		$tipodoc_Proceso = 'D';
		$procedure = 'BF_ObtenerDatos_Debi';
		$codNotasDebito = $txt_arreglo_deb;
		$label = 'ND:';
		///Este query sera solo para Notas de credito
		$sql_pro_deb = " execute BF_ObtenerDatos_Debi 	" . $codNegocio . ",
														" . $codEmpresa . ",
														" . $codCliente . ",
														'" . str_replace('-', ',', $codNotasDebito) . "' ";
		$query_pro_deb  = $_SESSION['dbmssql']->getAll($sql_pro_deb);
		foreach ($query_pro_deb as $pro => $descripcion_deb) {
			$impxcobrar	= round($descripcion_deb['impxcobrar'], 2);
			$suma = $suma + $impxcobrar;
		}
	}

	////////////////////// NOTA DE CREDITO DE PROFORMA //////////////////////////
	if (strlen($txt_arreglo_crep) > 0) {
		$tipodoc_Proceso = 'H';
		$procedure = 'BF_ObtenerDatos_Cre_Prof';
		$codNotasCredito = $txt_arreglo_crep;
		$label = 'NC:';
		$sql_pro_cre = " execute BF_ObtenerDatos_Cre_Prof " . $codNegocio . ",
													" . $codEmpresa . ",
													" . $codCliente . ",
													'" . str_replace('-', ',', $codNotasCredito) . "' ";
		$query_pro_cre  = $_SESSION['dbmssql']->getAll($sql_pro_cre);
		foreach ($query_pro_cre as $pro => $descripcion_cre) {
			$impxcobrar	= round($descripcion_cre['impxcobrar'], 2);
			$suma = $suma - $impxcobrar;
		}
	}

	////////////////////// NOTA DE DEBITO DE PROFORMA //////////////////////////
	if (strlen($txt_arreglo_debp) > 0) {
		$tipodoc_Proceso = 'I';
		$procedure = 'BF_ObtenerDatos_Deb_Prof';
		$codNotasDebito = $txt_arreglo_debp;
		$label = 'ND:';
		$sql_pro_deb = " execute BF_ObtenerDatos_Deb_Prof " . $codNegocio . ",
													" . $codEmpresa . ",
													" . $codCliente . ",
													'" . str_replace('-', ',', $codNotasDebito) . "' ";
		$query_pro_deb  = $_SESSION['dbmssql']->getAll($sql_pro_deb);
		foreach ($query_pro_deb as $pro => $descripcion_deb) {
			$impxcobrar	= round($descripcion_deb['impxcobrar'], 2);
			$suma = $suma + $impxcobrar;
		}
	}

	echo $suma;
}

if ($_REQUEST['totalDetraccion']) {
	//////////////////////////////Factura o Proforma//////////////////////////////////////////
	if (strlen($txt_arreglo_fac) > 0  or strlen($txt_arreglo_pro) > 0) {
		foreach ($query_pro as $pro => $descripcion) {
			$detraccion	= round($descripcion['detraccion'], 2);
			$suma = $suma + $detraccion;
		}
	}

	/////////////////////////////////Nota de Credito//////////////////////////////////////////
	if (strlen($txt_arreglo_cre) > 0) {
		$tipodoc_Proceso = 'C';
		$procedure = 'BF_ObtenerDatos_Cre';
		$codNotasCredito = $txt_arreglo_cre;
		$label = 'NC:';
		///Este query sera solo para Notas de credito
		$sql_pro_cre = " execute BF_ObtenerDatos_Cre 	" . $codNegocio . ",
														" . $codEmpresa . ",
														" . $codCliente . ",
														'" . str_replace('-', ',', $codNotasCredito) . "' ";
		$query_pro_cre  = $_SESSION['dbmssql']->getAll($sql_pro_cre);
		foreach ($query_pro_cre as $pro => $descripcion_cre) {
			$impxcobrar	= round($descripcion_cre['detraccion'], 2);
			$suma = $suma - $impxcobrar;
		}
	}

	////////////////////Nota de Debito/////////////////////////////////////////////
	if (strlen($txt_arreglo_deb) > 0) {
		$tipodoc_Proceso = 'D';
		$procedure = 'BF_ObtenerDatos_Debi';
		$codNotasDebito = $txt_arreglo_deb;
		$label = 'ND:';
		///Este query sera solo para Notas de credito
		$sql_pro_deb = " execute BF_ObtenerDatos_Debi 	" . $codNegocio . ",
														" . $codEmpresa . ",
														" . $codCliente . ",
														'" . str_replace('-', ',', $codNotasDebito) . "' ";
		$query_pro_deb  = $_SESSION['dbmssql']->getAll($sql_pro_deb);
		foreach ($query_pro_deb as $pro => $descripcion_deb) {
			$impxcobrar	= round($descripcion_deb['detraccion'], 2);
			$suma = $suma + $impxcobrar;
		}
	}

	///////////////////////////////// NOTA DE CREDITO DE PROFORMA //////////////////////////////////////////
	if (strlen($txt_arreglo_crep) > 0) {
		$tipodoc_Proceso = 'H';
		$procedure = 'BF_ObtenerDatos_Cre_Prof';
		$codNotasCredito = $txt_arreglo_crep;
		$label = 'NC:';
		///Este query sera solo para Notas de credito
		$sql_pro_cre = " execute BF_ObtenerDatos_Cre_Prof " . $codNegocio . ",
													" . $codEmpresa . ",
													" . $codCliente . ",
													'" . str_replace('-', ',', $codNotasCredito) . "' ";
		$query_pro_cre  = $_SESSION['dbmssql']->getAll($sql_pro_cre);
		foreach ($query_pro_cre as $pro => $descripcion_cre) {
			$impxcobrar	= round($descripcion_cre['detraccion'], 2);
			$suma = $suma - $impxcobrar;
		}
	}

	///////////////////////////////// NOTA DE DEBITO DE PROFORMA //////////////////////////////////////////
	if (strlen($txt_arreglo_debp) > 0) {
		$tipodoc_Proceso = 'I';
		$procedure = 'BF_ObtenerDatos_Deb_Prof';
		$codNotasDebito = $txt_arreglo_debp;
		$label = 'ND:';
		///Este query sera solo para Notas de credito
		$sql_pro_deb = " execute BF_ObtenerDatos_Deb_Prof " . $codNegocio . ",
													" . $codEmpresa . ",
													" . $codCliente . ",
													'" . str_replace('-', ',', $codNotasDebito) . "' ";
		$query_pro_deb  = $_SESSION['dbmssql']->getAll($sql_pro_deb);
		foreach ($query_pro_deb as $pro => $descripcion_deb) {
			$impxcobrar	= round($descripcion_deb['detraccion'], 2);
			$suma = $suma + $impxcobrar;
		}
	}

	echo $suma;
}

if ($_REQUEST['totalRetencion']) {
	//////////////////////////////Factura o Proforma//////////////////////////////////////////
	if (strlen($txt_arreglo_fac) > 0  or strlen($txt_arreglo_pro) > 0) {
		foreach ($query_pro as $pro => $descripcion) {
			$retencion	= round($descripcion['retencion'], 2);
			$suma = $suma + $retencion;
		}
	}

	////////////////////Nota de Debito/////////////////////////////////////////////
	if (strlen($txt_arreglo_deb) > 0) {
		$tipodoc_Proceso = 'D';
		$procedure = 'BF_ObtenerDatos_Debi';
		$codNotasDebito = $txt_arreglo_deb;
		$label = 'ND:';
		///Este query sera solo para Notas de credito
		$sql_pro_deb = " execute BF_ObtenerDatos_Debi 	" . $codNegocio . ",
														" . $codEmpresa . ",
														" . $codCliente . ",
														'" . str_replace('-', ',', $codNotasDebito) . "' ";
		$query_pro_deb  = $_SESSION['dbmssql']->getAll($sql_pro_deb);
		foreach ($query_pro_deb as $pro => $descripcion_deb) {
			$impxcobrar	= round($descripcion_deb['retencion'], 2);
			$suma = $suma + $impxcobrar;
		}
	}

	/////////////////////////////////Nota de Credito//////////////////////////////////////////
	if (strlen($txt_arreglo_cre) > 0) {
		$tipodoc_Proceso = 'C';
		$procedure = 'BF_ObtenerDatos_Cre';
		$codNotasCredito = $txt_arreglo_cre;
		$label = 'NC:';
		///Este query sera solo para Notas de credito
		$sql_pro_cre = " execute BF_ObtenerDatos_Cre 	" . $codNegocio . ",
														" . $codEmpresa . ",
														" . $codCliente . ",
														'" . str_replace('-', ',', $codNotasCredito) . "' ";
		$query_pro_cre = $_SESSION['dbmssql']->getAll($sql_pro_cre);
		foreach ($query_pro_cre as $pro => $descripcion_cre) {
			$impxcobrar	= round($descripcion_cre['retencion'], 2);
			$suma = $suma - $impxcobrar;
		}
	}

	///////////////////////////////// NOTA DE CREDITO DE PROFORMA //////////////////////////////////////////
	if (strlen($txt_arreglo_crep) > 0) {
		$tipodoc_Proceso = 'H';
		$procedure = 'BF_ObtenerDatos_Cre_Prof';
		$codNotasCredito = $txt_arreglo_crep;
		$label = 'NC:';
		///Este query sera solo para Notas de credito
		$sql_pro_cre = " execute BF_ObtenerDatos_Cre_Prof " . $codNegocio . ",
													" . $codEmpresa . ",
													" . $codCliente . ",
													'" . str_replace('-', ',', $codNotasCredito) . "' ";
		$query_pro_cre = $_SESSION['dbmssql']->getAll($sql_pro_cre);
		foreach ($query_pro_cre as $pro => $descripcion_cre) {
			$impxcobrar	= round($descripcion_cre['retencion'], 2);
			$suma = $suma - $impxcobrar;
		}
	}

	///////////////////////////////// NOTA DE DEBITO DE PROFORMA //////////////////////////////////////////
	if (strlen($txt_arreglo_debp) > 0) {
		$tipodoc_Proceso = 'I';
		$procedure = 'BF_ObtenerDatos_Deb_Prof';
		$codNotasDebito = $txt_arreglo_debp;
		$label = 'ND:';
		///Este query sera solo para Notas de credito
		$sql_pro_deb = " execute BF_ObtenerDatos_Deb_Prof " . $codNegocio . ",
													" . $codEmpresa . ",
													" . $codCliente . ",
													'" . str_replace('-', ',', $codNotasDebito) . "' ";
		$query_pro_deb = $_SESSION['dbmssql']->getAll($sql_pro_deb);
		foreach ($query_pro_deb as $pro => $descripcion_deb) {
			$impxcobrar	= round($descripcion_deb['retencion'], 2);
			$suma = $suma + $impxcobrar;
		}
	}

	echo $suma;
}


if ($_REQUEST['totalImporte']) {
	//////////////////////////////Factura o Proforma//////////////////////////////////////////
	if (strlen($txt_arreglo_fac) > 0  or strlen($txt_arreglo_pro) > 0) {
		foreach ($query_pro as $pro => $descripcion) {
			$impconigv	= round($descripcion['impconigv'], 2);
			$suma = $suma + $impconigv;
		}
	}

	/////////////////////////////////Nota de Credito//////////////////////////////////////////
	if (strlen($txt_arreglo_cre) > 0) {
		$tipodoc_Proceso = 'C';
		$procedure = 'BF_ObtenerDatos_Cre';
		$codNotasCredito = $txt_arreglo_cre;
		$label = 'NC:';
		///Este query sera solo para Notas de credito
		$sql_pro_cre = " execute BF_ObtenerDatos_Cre 	" . $codNegocio . ",
														" . $codEmpresa . ",
														" . $codCliente . ",
														'" . str_replace('-', ',', $codNotasCredito) . "' ";
		$query_pro_cre  = $_SESSION['dbmssql']->getAll($sql_pro_cre);
		foreach ($query_pro_cre as $pro => $descripcion_cre) {
			$impxcobrar	= round($descripcion_cre['impconigv'], 2);
			$suma = $suma - $impxcobrar;
		}
	}

	////////////////////Nota de Debito/////////////////////////////////////////////
	if (strlen($txt_arreglo_deb) > 0) {
		$tipodoc_Proceso = 'D';
		$procedure = 'BF_ObtenerDatos_Debi';
		$codNotasDebito = $txt_arreglo_deb;
		$label = 'ND:';
		///Este query sera solo para Notas de credito
		$sql_pro_deb = " execute BF_ObtenerDatos_Debi 	" . $codNegocio . ",
														" . $codEmpresa . ",
														" . $codCliente . ",
														'" . str_replace('-', ',', $codNotasDebito) . "' ";
		$query_pro_deb  = $_SESSION['dbmssql']->getAll($sql_pro_deb);
		foreach ($query_pro_deb as $pro => $descripcion_deb) {
			$impxcobrar	= round($descripcion_deb['impconigv'], 2);
			$suma = $suma + $impxcobrar;
		}
	}

	///////////////////////////////// NOTA DE CREDITO DE PROFORMA //////////////////////////////////////////
	if (strlen($txt_arreglo_crep) > 0) {
		$tipodoc_Proceso = 'H';
		$procedure = 'BF_ObtenerDatos_Cre_Prof';
		$codNotasCredito = $txt_arreglo_crep;
		$label = 'NC:';
		///Este query sera solo para Notas de credito
		$sql_pro_cre = " execute BF_ObtenerDatos_Cre_Prof " . $codNegocio . ",
													" . $codEmpresa . ",
													" . $codCliente . ",
													'" . str_replace('-', ',', $codNotasCredito) . "' ";
		$query_pro_cre = $_SESSION['dbmssql']->getAll($sql_pro_cre);
		foreach ($query_pro_cre as $pro => $descripcion_cre) {
			$impxcobrar	= round($descripcion_cre['impconigv'], 2);
			$suma = $suma - $impxcobrar;
		}
	}

	///////////////////////////////// NOTA DE DEBITO DE PROFORMA //////////////////////////////////////////
	if (strlen($txt_arreglo_debp) > 0) {
		$tipodoc_Proceso = 'I';
		$procedure = 'BF_ObtenerDatos_Deb_Prof';
		$codNotasDebito = $txt_arreglo_debp;
		$label = 'ND:';
		///Este query sera solo para Notas de credito
		$sql_pro_deb = " execute BF_ObtenerDatos_Deb_Prof " . $codNegocio . ",
													" . $codEmpresa . ",
													" . $codCliente . ",
													'" . str_replace('-', ',', $codNotasDebito) . "' ";
		$query_pro_deb = $_SESSION['dbmssql']->getAll($sql_pro_deb);
		foreach ($query_pro_deb as $pro => $descripcion_deb) {
			$impxcobrar	= round($descripcion_deb['impconigv'], 2);
			$suma = $suma + $impxcobrar;
		}
	}

	echo $suma;
}
