<?php
session_start();
require('../../../includes/dbmssql_cfg.php');

$codigoObra		=	trim($_REQUEST['codigoObra']);
$codigoOrden	=	trim($_REQUEST['codigoOrden']);

if ($_REQUEST['validar_SI_ORD_PAGO']) {
	$codigoObra	 	= $_REQUEST['codigoObra'];  //NEGOCIO
	$codigoOrden	= $_REQUEST['codigoOrden']; //PROFORMA

	//ORDEN DE PAGO
	////////Si es mayor a cero la resultante que le daremos sera = 1
	$sql_buscar_ent = "select	count(C.CodOrdPag) nro, convert(varchar,c.CodOrdPag)+':'+neg.NegCne documento_pago
					from	CABORDPAGO C  
					join	DETORDPAGO D on D.CodOrdPag = C.CodOrdPag and
									D.CodPagNeg = C.CodPagNeg and
									D.CodPagCli = C.CodPagCli
					join 	negocio neg on neg.NegCod = c.CodPagNeg												
					where	C.Estado not in ('C') and 
							D.TipoDoc in ('P') and c.CodPagNeg ='" . $codigoObra . "' and
							D.CodDoc  in ( 	select 	CodOrdProf 
											from 	CABORDPROF 
											where 	CodOrdProf='" . $codigoOrden . "' and 
													CodProfNeg='" . $codigoObra . "' and 
													Estado='P' )
					group by c.CodOrdPag,	neg.NegCne ";
	// echo $sql_buscar_ent;
	$dsl_buscar_ent = $_SESSION['dbmssql']->getAll($sql_buscar_ent);
	foreach ($dsl_buscar_ent as $val => $entidad_value) {
		$en_ordpago = (int)($entidad_value['nro']);
		$en_docpago = ($entidad_value['documento_pago']);
	}
	if (strlen($en_ordpago) == 0) {
		$en_ordpago = 0;
		$en_docpago = '-';
	}

	////NOTA DE CREDITO
	////////Si es mayor a cero la resultante que le daremos sera = 2
	$sql_buscar_nc = "select	count(c.CodOrdNotaCre)as nro, convert(varchar,c.CodOrdNotaCre)+':'+neg.NegCne+' ('+c.NotaCredito+')'as documento_credito
					from	CABNOTACREDITO c
					join	negocio neg on neg.NegCod = c.CodNotaNeg
					where	c.CodNotaNeg='" . $codigoObra . "' and c.estado in('A') and 
					c.CodNotaProf in( select 	CodOrdProf 
									from 	CABORDPROF 
									where 	CodOrdProf='" . $codigoOrden . "' and 
									CodProfNeg='" . $codigoObra . "' and 
									Estado='P' )
					group by c.CodOrdNotaCre, neg.NegCne, c.NotaCredito		";
	$dsl_buscar_nc = $_SESSION['dbmssql']->getAll($sql_buscar_nc);
	foreach ($dsl_buscar_nc as $val => $entidad_value) {
		$en_notacredito = (int)($entidad_value['nro']);
		$en_doc_credito = ($entidad_value['documento_credito']);
	}
	if (strlen($en_notacredito) == 0) {
		$en_notacredito = 0;
		$en_doc_credito = '-';
	}

	////NOTA DE DEBITO
	////////Si es mayor a cero la resultante que le daremos sera = 3
	$sql_buscar_nd = "select	count(c.CodOrdNotaDeb)as nro, convert(varchar,c.CodOrdNotaDeb)+':'+neg.NegCne+' ('+c.NotaDebito+')' as documento_debito 
					from	CABNOTADEBITO_PROF c
					join	negocio neg on neg.NegCod = c.CodNotaNeg 
					where	c.codnotaneg = '" . $codigoObra . "' and c.estado in('A') and 
					c.CodNotaProf in( select CodOrdProf 
									from 	CABORDPROF 
									where 	CodOrdProf='" . $codigoOrden . "' and 
									CodProfNeg='" . $codigoObra . "' and 
									Estado='P' )
					group by c.CodOrdNotaDeb, neg.NegCne, c.NotaDebito ";
	$dsl_buscar_nd = $_SESSION['dbmssql']->getAll($sql_buscar_nd);
	foreach ($dsl_buscar_nd as $val => $entidad_value) {
		$en_notadebito = (int)($entidad_value['nro']);
		$en_doc_debito = ($entidad_value['documento_debito']);
	}
	if (strlen($en_notadebito) == 0) {
		$en_notadebito = 0;
		$en_doc_debito = '-';
	}

	///Si ambos son mayor a cero, la suma resultante sera = 3 | pero si fuera 1 (esta en orden pago) 2 ( ya esta en nota cred) 4 ( ya esta en nota deb)
	if ($en_ordpago > 0)
		$valor_opago = 1;
	else
		$valor_opago = 0;

	if ($en_notacredito > 0)
		$valor_ncredito = 2;
	else
		$valor_ncredito = 0;

	if ($en_notadebito > 0)
		$valor_ndebito = 4;
	else
		$valor_ndebito = 0;

	//Armando la variable de los documentos:
	if ($en_doc_credito == '-')
		$doc_notas = $en_doc_debito;
	else
		$doc_notas = $en_doc_credito;

	$DOCUMENTO	=   $en_docpago  . '|' . $doc_notas;
	/////       =   XXXXXXXXXXX|-				caso: hay opago    y no hay ningun debito ni credito
	////		=	XXXXXXXXXXX|0CRE-929292   	caso: hay opago    y no hay debito si hay credito
	////		=	          -|0DEB-929292  	caso: no hay opago y no hay credito si hay debito

	$VALOR_FINAL = $valor_ncredito + $valor_opago + $valor_ndebito;
	echo $RESPUESTA = $VALOR_FINAL . '_' . $DOCUMENTO;
}

if ($_REQUEST['factura_fisica']) {
	$sql_Factura = "select c.Proforma, cast(c.Fecreg as date)as fecha, e.EmpRaz, cli.CliRaz, c.TipMoneda,  m.simmon simbolo_factura,
		(select x.SimMon from MONEDA x
		where x.LetMon <> m.LetMon and x.Estado='A')as opuesto, c.TipCambio
		from CABORDPROF c 
		left join EMPRESA e on c.CodProfEmp=e.EmpCod 
		left join CLIENTE cli on c.CodProfCli=cli.CliCod 
		left join MONEDA m on c.TipMoneda=m.letmon
		where c.CodOrdProf='" . $codigoOrden . "' and c.CodProfNeg='" . $codigoObra . "' ";
	$dsl_Factura = $_SESSION['dbmssql']->getAll($sql_Factura);
	foreach ($dsl_Factura as $valor => $val) {
		$Factura = trim($val['Proforma']);
		$fecha	= trim($val['fecha']);
		$empresa = trim($val['EmpRaz']);
		$cliente = trim($val['CliRaz']);

		$TipMoneda = trim($val['TipMoneda']) . '-' . trim($val['simbolo_factura']) . '-' . trim($val['opuesto']);
		$TipCambio = trim($val['TipCambio']);

		$datos = array(
			"prof_fecha" 	=> $Factura . ' | ' . $fecha,
			"empraz"	 	=> $empresa,
			"tipo_almacen"	=> '0',
			"cliente"		=> $cliente,
			"moneda"		=> $TipMoneda,
			"tipo_cambio"	=> $TipCambio
		);
	}

	//Devolvemos el array pasado a JSON como objeto
	echo json_encode($datos, JSON_FORCE_OBJECT);
}

if ($_REQUEST['upd_nota_debito']) {
	$sql_Factura = "SELECT E.EmpRaz, CLI.CliRaz, C.FechaNota, C.NotaDebito, C.Comentario, 
		C.tipo_almacen, C.SubTotal, C.MontTotal, C.MontCambio, C.MotCod, C.CodNotaProf  from CABNOTADEBITO_PROF C 
		left join CABORDPROF F on F.CodOrdProf=C.CodNotaProf and F.CodProfNeg=C.CodNotaNeg and F.CodProfEmp=C.CodNotaEmp 
		left join EMPRESA E on E.EmpCod=f.CodProfEmp
		left join CLIENTE CLI on CLI.CliCod=f.CodProfCli 
		where C.CodOrdNotaDeb='$codigoOrden' and C.CodNotaNeg='$codigoObra' ";

	$dsl_Factura = $_SESSION['dbmssql']->getAll($sql_Factura);
	foreach ($dsl_Factura as $valor => $val) {
		$empresa 			= trim($val['EmpRaz']);
		$cliente 			= trim($val['CliRaz']);
		$fecha_nd			= trim($val['FechaNota']);
		$numero_nota 		= trim($val['NotaDebito']);
		$comentario 		= trim($val['Comentario']);
		$tipo_almacen 		= trim($val['tipo_almacen']);
		$subtotal 			= trim($val['SubTotal']);
		$total 				= trim($val['MontTotal']);
		$monto_al_cambio 	= trim($val['MontCambio']);

		$motivo 			= trim($val['MotCod']);
		$codNotaProf 		= trim($val['CodNotaProf']);


		//Obtener datos factura_fisica
		$sql_datos = "SELECT C.Proforma, CONVERT(VARCHAR(10),CAST(C.Fecreg as date),103)as fecha, 
						C.TipMoneda, (select X.SimMon from MONEDA X where X.LetMon <> M.LetMon and X.Estado='A')
						as opuesto, M.simmon as simbolo_factura, C.TipCambio
						FROM CABORDPROF C
						LEFT JOIN MONEDA M on M.letmon = C.TipMoneda 
						WHERE CodOrdProf='$codNotaProf' and CodProfNeg='$codigoObra' ";
		$dsql_datos = $_SESSION['dbmssql']->getAll($sql_datos);
		foreach ($dsql_datos as $valor => $val) {
			$proforma 	= trim($val['Proforma']);
			$fecha_pf 	= trim($val['fecha']);
			$TipMoneda 	= trim($val['TipMoneda']) . '-' . trim($val['simbolo_factura']) . '-' . trim($val['opuesto']);
			$TipCambio 	= trim($val['TipCambio']);
		}

		$datos = array(
			"empraz"	 		=> $empresa,
			"cliente"			=> $cliente,
			"fecha_nd"			=> $fecha_nd,
			"numero_nota"		=> $numero_nota,
			"comentario"		=> $comentario,
			"tipo_almacen"		=> $tipo_almacen,
			"subtotal"			=> $subtotal,
			"total"				=> $total,
			"monto_al_cambio"	=> $monto_al_cambio,
			"motivo"			=> $motivo,
			"prof_fecha" 		=> $proforma . ' | ' . $fecha_pf . ' | Cod. Proforma: ' . $codNotaProf,
			"moneda"			=> $TipMoneda,
			"tipo_cambio"		=> $TipCambio
		);
	}

	//Devolvemos el array pasado a JSON como objeto
	echo json_encode($datos, JSON_FORCE_OBJECT);
}

if ($_REQUEST['delete_orden']) {
	$usuario = $_REQUEST['usuario'];
	$sql_del_orden =	" execute BF_EliminarNotaDebitoProf " . $codigoOrden . " , " . $codigoObra . " , " . $usuario;
	$_SESSION['dbmssql']->query($sql_del_orden);
}
