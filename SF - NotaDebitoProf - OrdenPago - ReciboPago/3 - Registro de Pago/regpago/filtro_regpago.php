<?php
session_start();
require('../../../includes/dbmssql_cfg.php');


if ($_REQUEST['buscarorden']) {

	$codNegocio	= $_REQUEST['codNegocio'];
	$tipoCobro	= $_REQUEST['tipoCobro'];

	$cmbus_anio	= $_REQUEST['cmbus_anio'];
	$cmbus_mes	= $_REQUEST['cmbus_mes'];


	switch ($tipoCobro) {
		case 'Q': {
				$sigla_cob = '-CHQ';
				break;
			};
		case 'L': {
				$sigla_cob = '-LTR';
				break;
			};
		case 'C': {
				$sigla_cob = '-CSH';
				break;
			};
	}

	$cadena = "";
	if ($codNegocio != '0' && $tipoCobro != '0') {
		//seleccionadno todas las ordenes de tal obra
		$sql_ordenes = "  select  CodOrdPag 
							from 	CabOrdPago
							where   estado not in ('C','E') and 
									CodPagNeg='" . $codNegocio . "' and 
									tipocob='" . $tipoCobro . "' and
									year(FecReg) = " . $cmbus_anio . " and
									month(FecReg) = " . $cmbus_mes . "									
							order by 1";
		$dsl_ordenes = $_SESSION['dbmssql']->getAll($sql_ordenes);
		foreach ($dsl_ordenes as $val => $ord) {
			$codigoOrden = trim($ord['CodOrdPag']);
			$sql_detalle = "select 	count(*) as num from DetOrdPago 
							where 	estado in ('I','T','P') and  
							 		CodPagNeg='" . $codNegocio . "' and 
									CodOrdPag='" . $codigoOrden . "' and 
									tipocob='" . $tipoCobro . "' ";
			$dsl_detalle = $_SESSION['dbmssql']->getAll($sql_detalle);
			foreach ($dsl_detalle as $v => $value) {
				$numero = $value['num'];
			}
			if ($numero > 0) {
				$cadena = $cadena . $codigoOrden . "|";
			}
			$ordenes_validas = implode(",", explode('|', $cadena));         /// 2,3,4,
		}
		$validas = substr($ordenes_validas, 0, strlen($ordenes_validas) - 1);  /// 2,3,4

		if (strlen($validas) > 0) {
			$sql_ver_orden = "select	ca.CodOrdPag, o.negcne Codif
							from	CabOrdPago ca, negocio o
							where	ca.CodPagNeg=o.negcod and ca.CodPagNeg='" . $codNegocio . "' and 
									ca.CodOrdPag in (" . $validas . ") and
									ca.estado not in ('C','E') and
									ca.TipoCob='" . $tipoCobro . "'									
									order by 1 ";
			$dsl_ver_orden = $_SESSION['dbmssql']->getAll($sql_ver_orden);
			echo "<select name=\"cmb_ordenes\" id=\"cmb_ordenes\" onChange=\"activar_veringreso_reg(this.value)\" class=\"smalltext\">";
			echo "<option value=\"0\">---------------------------------------------</option>";
			foreach ($dsl_ver_orden  as $val => $value) {
				$codorden		= trim($value['CodOrdPag']);
				$codificacion	= rtrim($value['Codif']);
				$siglas			= "O.P. N&ordm;&nbsp;";
				$serieOrden		= $siglas . (string)str_pad($codorden, 7, '0', STR_PAD_LEFT) . $sigla_cob . $codificacion;
				echo "<option value='" . $codorden . "'>" . $serieOrden . "</option>";
			}
			echo "</select>";
		} else {
			echo "<select name=\"cmb_ordenes\" id=\"cmb_ordenes\" style=\"width:200px;\">";
			echo "<option value=\"0\">---------------------------------------------</option>";
			echo "</select>";
		}
	} else {
		echo "<select name=\"cmb_ordenes\" id=\"cmb_ordenes\" style=\"width:200px;\">";
		echo "<option value=\"0\">---------------------------------------------</option>";
		echo "</select>";
	}
}

if ($_REQUEST['traer_ordenes_pago']) {
	$codNegocio	 = $_REQUEST['codNegocio'];
	$tipoCobro	 = $_REQUEST['tipoCobro'];
	$codigoPago = trim($_REQUEST['codigoPago']);

	if ($codNegocio != '0' && $tipoCobro != '0' && $codigoPago != '0') {

		$sql_ver_ord_ingr = "select	c.codregpag , '-'+o.negcne codif,		
									case 
									when  datepart(weekday , fecreg )= 1 then 'Lun'
									when  datepart(weekday , fecreg )= 2 then 'Mar'
									when  datepart(weekday , fecreg )= 3 then 'Mie'
									when  datepart(weekday , fecreg )= 4 then 'Jue'		
									when  datepart(weekday , fecreg )= 5 then 'Vie'		
									when  datepart(weekday , fecreg )= 6 then 'Sab' else 'Dom'
									end +
									' '+
									convert(varchar,day(fecreg))
									+ ', ' +  
									convert(varchar,month(fecreg))
									+'-'+
									convert(varchar,year(fecreg)) as Registro			
							from	cabregpago c, negocio o 
							where	c.codregneg=o.negcod and 
									c.codregneg='" . $codNegocio . "' and 
									c.codordpag='" . $codigoPago . "' and 
									c.tipocob='" . $tipoCobro . "' and
									estado not in ('C','E')";
		$dsl_ver_ord_ingr = $_SESSION['dbmssql']->getAll($sql_ver_ord_ingr);
		echo "<select name=\"cmb_ingresos\" class=\"smalltext\" id=\"cmb_ingresos\" onChange=\"activar_verorden_ingreso_reg(this.value)\">";
		echo "<option value=\"0\">-------------------------------------------------------</option>";
		foreach ($dsl_ver_ord_ingr as $val => $value) {
			$codregpag		= trim($value['codregpag']);
			$codificacion	= "R.P. N&ordm; " . (string)str_pad($codregpag, 7, '0', STR_PAD_LEFT) . $value['codif'];
			echo "<option value='" . $codregpag . "'>" . $codificacion . " | " . trim($value['Registro']) . "</option>";
		}
		echo "</select>";
	} else {
		echo "<select name=\"cmb_ingresos\" class=\"smalltext\" id=\"cmb_ingresos\" onChange=\"activar_verorden_ingreso_reg(this.value)\">";
		echo "<option value=\"0\">-----------------------------------</option>";
		echo "</select>";
	}
}

if ($_REQUEST['ComentPago']) {
	$codPago 	= trim($_REQUEST['codPago']);
	$codCobro 	= trim($_REQUEST['codCobro']);
	$codNegocio = trim($_REQUEST['codNegocio']);

	$sql_Comentario = "select 	Comentario
						from 	CABORDPAGO c
						where 	c.codordPag='" . $codPago . "' 
								and c.codPagneg='" . $codNegocio . "' 
								and TipoCob = '" . $codCobro . "'
								and Estado not in ('E','C','T') ";
	$dsl_Comentario = $_SESSION['dbmssql']->getAll($sql_Comentario);
	foreach ($dsl_Comentario as $val => $value) {
		$Comentario	= trim($value['Comentario']);
	}
	echo $Comentario;
}

if ($_REQUEST['datosCliente']) {
	$codPago 	= trim($_REQUEST['codPago']);
	$codCobro 	= trim($_REQUEST['codCobro']);
	$codNegocio = trim($_REQUEST['codNegocio']);

	$sql_datosCliente = "select 	p.cliraz
						from 	CABORDPAGO c, cliente p
						where 	c.CodPagCli=p.clicod and c.codordPag='" . $codPago . "' 
								and c.codPagneg='" . $codNegocio . "' 
								and TipoCob = '" . $codCobro . "'
								and Estado not in ('E','C'           ,'T') ";
	$dsl_datosCliente = $_SESSION['dbmssql']->getAll($sql_datosCliente);
	foreach ($dsl_datosCliente as $val => $value) {
		$cliraz	= trim($value['cliraz']);
	}
	echo $cliraz;
}



if ($_REQUEST['datosEmpresa']) {
	$codPago 	= trim($_REQUEST['codPago']);
	$codCobro 	= trim($_REQUEST['codCobro']);
	$codNegocio = trim($_REQUEST['codNegocio']);

	$sql_datosEmpresa = "select 	p.empraz
						from 	CABORDPAGO c, empresa p
						where 	c.CodPagEmp=p.empcod and c.codordPag='" . $codPago . "' 
								and c.codPagneg='" . $codNegocio . "' 
								and TipoCob = '" . $codCobro . "'
								and Estado not in ('E','C'           ,'T') ";
	$dsl_datosEmpresa = $_SESSION['dbmssql']->getAll($sql_datosEmpresa);
	foreach ($dsl_datosEmpresa as $val => $value) {
		$empraz	= trim($value['empraz']);
	}
	echo $empraz;
}

if ($_REQUEST['codcli']) {
	$codPago 	= trim($_REQUEST['codPago']);
	$codCobro 	= trim($_REQUEST['codCobro']);
	$codNegocio = trim($_REQUEST['codNegocio']);

	$sql_codcli = "select c.CodPagCli from CABORDPAGO c  
				where c.codordPag='" . $codPago . "' and 
				c.codPagneg='" . $codNegocio . "' and 
				c.TipoCob='" . $codCobro . "' and 
				c.Estado not in('E','C','T') ";
	$dsl_codcli = $_SESSION['dbmssql']->getAll($sql_codcli);
	foreach ($dsl_codcli as $val => $cli) {
		$codigo_cli = trim($cli['CodPagCli']);
	}
	echo $codigo_cli;
}

if ($_REQUEST['bancocli']) {
	$codPago 	= trim($_REQUEST['codPago']);
	$codCobro 	= trim($_REQUEST['codCobro']);
	$codNegocio = trim($_REQUEST['codNegocio']);

	$sql_bancli = "select c.BanCli from CABORDPAGO c 
				where c.codordPag='" . $codPago . "' and 
				c.codPagneg='" . $codNegocio . "' and 
				c.TipoCob='" . $codCobro . "' and 
				c.Estado not in ('E','C','T') ";
	$dsl_bancli = $_SESSION['dbmssql']->getAll($sql_bancli);
	foreach ($dsl_bancli as $val => $ban) {
		$cod_bancli = trim($ban['BanCli']);
	}
	echo $cod_bancli;
}


if ($_REQUEST['datosNumCheque']) {
	$codPago 	= trim($_REQUEST['codPago']);
	$codCobro 	= trim($_REQUEST['codCobro']);
	$codNegocio = trim($_REQUEST['codNegocio']);

	$sql_datosNumCheque = "	select 	c.NumCheque
							from 	CABORDPAGO c
							where 	c.codordPag ='" . $codPago . "' 
									and c.codPagneg ='" . $codNegocio . "'
									and TipoCob = '" . $codCobro . "'
									and Estado not in ('E','C'           ,'T') ";  //
	$dsl_datosNumCheque = $_SESSION['dbmssql']->getAll($sql_datosNumCheque);
	foreach ($dsl_datosNumCheque as $val => $value) {
		$NumCheque	= trim($value['NumCheque']);
	}
	echo $NumCheque;
}


if ($_REQUEST['datosFecVtoLetra']) {
	$codPago 	= trim($_REQUEST['codPago']);
	$codCobro 	= trim($_REQUEST['codCobro']);
	$codNegocio = trim($_REQUEST['codNegocio']);

	$sql_datosFecVtoLet = "	select 	c.FecVtoLet
							from 	CABORDPAGO c
							where 	c.codordPag ='" . $codPago . "' 
									and c.codPagneg ='" . $codNegocio . "'
									and TipoCob = '" . $codCobro . "'
									and Estado not in ('E','C'           ,'T') ";  //
	$dsl_datosFecVtoLet = $_SESSION['dbmssql']->getAll($sql_datosFecVtoLet);
	foreach ($dsl_datosFecVtoLet as $val => $value) {
		$FecVtoLet	= trim($value['FecVtoLet']);
	}
	if (strlen($FecVtoLet) == 0) $FecVtoLet = "No es Letra";
	echo $FecVtoLet;
}


if ($_REQUEST['datosBancoCli']) {
	$codPago 	= trim($_REQUEST['codPago']);
	$codCobro 	= trim($_REQUEST['codCobro']);
	$codNegocio = trim($_REQUEST['codNegocio']);

	$sql_datosBanCli = "	select 	c.BanCli
							from 	CABORDPAGO c
							where 	c.codordPag = '" . $codPago . "' 
									and c.codPagneg ='" . $codNegocio . "'
									and TipoCob = '" . $codCobro . "'
									and Estado not in ('E','C'           ,'T') ";
	$dsl_datosBanCli = $_SESSION['dbmssql']->getAll($sql_datosBanCli);
	foreach ($dsl_datosBanCli as $val => $value) {
		$BanCliD	= trim($value['BanCli']);
	}




	$t = "SELECT [BanCod], replace([BanNom],'BANCO','')  as BanNom
  		FROM [XOUT].[dbo].[BANCO]
		WHERE BanCod= " . $BanCliD . "";
	$dsl_t = $_SESSION['dbmssql']->getAll($t);
	foreach ($dsl_t as $val => $inf) {
		$BanCli	= trim($inf['BanNom']);
	}

	if (strlen($BanCliD) == 0) $BanCli = "No Indicado";
	echo $BanCli;







	if (strlen($BanCli) == 0) $BanCli = "No Indicado";
	echo $BanCli;
}


if ($_REQUEST['datosBancoEmp']) {
	$codPago 	= trim($_REQUEST['codPago']);
	$codCobro 	= trim($_REQUEST['codCobro']);
	$codNegocio = trim($_REQUEST['codNegocio']);

	$sql_datosBanEmp = "	select 	c.BanEmp, c.CodPagEmp
							from 	CABORDPAGO c
							where 	c.codordPag = '" . $codPago . "' 
									and c.codPagneg = '" . $codNegocio . "'
									and TipoCob = '" . $codCobro . "'
									and Estado not in ('E','C'           ,'T') ";  //
	$dsl_datosBanEmp = $_SESSION['dbmssql']->getAll($sql_datosBanEmp);
	foreach ($dsl_datosBanEmp as $val => $value) {
		$BanEmp		= trim($value['BanEmp']);
		$CodPagEmp	= trim($value['CodPagEmp']);
	}


	$sql_bancoEmp = "select	CodCue, BanSig, Tipo, CueBan
					 from 	cuenta c ,banco b
					 where 	b.bancod=c.BanCod and 
					 		c.EmpCod='" . $CodPagEmp . "' and
							b.bancod not in (2) and
							c.codcue='" . $BanEmp . "' ";
	$query_ban  = $_SESSION['dbmssql']->getAll($sql_bancoEmp);
	foreach ($query_ban as $pro => $des) {
		$codigo	= $des['CodCue'];
		$Tipo	= $des['Tipo'];
		$BanSig	= $des['BanSig'];
		switch ($Tipo) {
			case 'S': {
					$moneda = 'S/';
					break;
				};
			case 'D': {
					$moneda = '$';
					break;
				};
		}
		$descripcion	= $BanSig . ' | ' . $moneda . ' | ' . $des['CueBan'];
	}

	if (strlen($descripcion) == 0) $descripcion = "No Indicado";
	if ($descripcion == " | $ | ") $descripcion = "No Indicado";
	if ($descripcion == " | S/ | ") $descripcion = "No Indicado";
	echo $descripcion;
}



if ($_REQUEST['datosBancoDet']) {
	$codPago 	= trim($_REQUEST['codPago']);
	$codCobro 	= trim($_REQUEST['codCobro']);
	$codNegocio = trim($_REQUEST['codNegocio']);

	$sql_datosBanDet = "	select 	c.BanDet, c.CodPagEmp
							from 	CABORDPAGO c
							where 	c.codordPag = '" . $codPago . "' 
									and c.codPagneg = '" . $codNegocio . "'
									and TipoCob = '" . $codCobro . "'
									and Estado not in ('E','C'           ,'T') ";  //
	$dsl_datosBanDet = $_SESSION['dbmssql']->getAll($sql_datosBanDet);
	foreach ($dsl_datosBanDet as $val => $value) {
		$BanDet		= trim($value['BanDet']);
		$CodPagEmp	= trim($value['CodPagEmp']);
	}


	$sql_bancoDet = "select	CodCue, BanSig, Tipo, CueBan
					 from 	cuenta c ,banco b
					 where 	b.bancod=c.BanCod and 
					 		c.EmpCod='" . $CodPagEmp . "' and
							b.bancod in (2) and
							c.codcue='" . $BanDet . "'   ";
	$query_ban  = $_SESSION['dbmssql']->getAll($sql_bancoDet);
	foreach ($query_ban as $pro => $des) {
		$codigo	= $des['CodCue'];
		$Tipo	= $des['Tipo'];
		$BanSig	= $des['BanSig'];
		switch ($Tipo) {
			case 'S': {
					$moneda = 'S/';
					break;
				};
			case 'D': {
					$moneda = '$';
					break;
				};
		}
		$descripcion	= $BanSig . ' | ' . $moneda . ' | ' . $des['CueBan'];
	}

	if (strlen($descripcion) == 0) $descripcion = "No Indicado";
	if ($descripcion == " | $ | ") $descripcion = "No Indicado";
	if ($descripcion == " | S/ | ") $descripcion = "No Indicado";
	echo $descripcion;
}


if ($_REQUEST['TotCob']) {
	$codPago 	= trim($_REQUEST['codPago']);
	$codCobro 	= trim($_REQUEST['codCobro']);
	$codNegocio = trim($_REQUEST['codNegocio']);

	$suma = 0.0;
	$sql_pro = " execute BF_DatosRegistraPago " . $codNegocio . ",'" . $codCobro . "'," . $codPago . ",'RPAG' ";
	$query_pro  = $_SESSION['dbmssql']->getAll($sql_pro);
	foreach ($query_pro as $pro => $descripcion) {
		$MONTCOB 		= $descripcion['MONTCOB'];
		$CONTROL 		= $descripcion['CONTROL'];
		$TIPODOCDETALLE	= $descripcion['TIPODOCDETALLE'];

		if ($TIPODOCDETALLE == 'C' || $TIPODOCDETALLE == 'H') {
			$MONTCOB = $MONTCOB * (-1);		///CREDITO
		}
		if ($TIPODOCDETALLE == 'D' || $TIPODOCDETALLE == 'I') {
			$MONTCOB = $MONTCOB * (1);		///DEBITO
		}

		$resto = $MONTCOB - $CONTROL;
		$suma = $suma + $resto;
	}
	echo $suma;
}


if ($_REQUEST['SaldoEC']) {


	/////PENDIENTE
	$codPago 	= trim($_REQUEST['codPago']);
	$codCobro 	= trim($_REQUEST['codCobro']);
	$codNegocio = trim($_REQUEST['codNegocio']);

	$suma = 0.0;
	$sql_pro = " execute BF_DatosRegistraPago " . $codNegocio . ",'" . $codCobro . "'," . $codPago . ",'RPAG' ";
	$query_pro  = $_SESSION['dbmssql']->getAll($sql_pro);
	foreach ($query_pro as $pro => $descripcion) {
		$MONTCOB = $descripcion['MONTCOB'];
		$CONTROL = $descripcion['CONTROL'];




		$TIPODOCDETALLE	= $descripcion['TIPODOCDETALLE'];
		if ($TIPODOCDETALLE == 'C') {
			$MONTCOB = $MONTCOB * (-1);		///CREDITO
		}
		if ($TIPODOCDETALLE == 'D') {
			$MONTCOB = $MONTCOB * (1);		///DEBITO
		}



		$resto = $MONTCOB - $CONTROL;
		$suma = $suma + $resto;
	}
	echo $suma;
	///echo 'pendiente';
}


if ($_REQUEST['contar']) {

	$txt_operacion 	= trim($_REQUEST['txt_operacion']);

	$sql_pro = " select COUNT(*) as contar from CABREGPAGO where rtrim(ltrim(NumOper))= '" . $txt_operacion . "' and Estado!='C' "; // quitando espacias en blanco derecha izquiera
	$query_pro  = $_SESSION['dbmssql']->getAll($sql_pro);
	foreach ($query_pro as $pro => $descripcion) {

		echo $contar 		= $descripcion['contar'];
	}
}
