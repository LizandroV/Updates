<?php
include('fpdf/fpdf.php');
require('../includes/arch_cfg.php');
require('../includes/dbmssql_cfg.php');

class PDF extends FPDF
{
	private $datos;
	private $detalles;
	private $det_total;

	function CabeceraPrincipal()
	{
		/*---------AGREGADO-------------*/
		$codNegocio		= base64_decode($_REQUEST['codNegocio']);
		$fech_ini		= trim($_REQUEST['fech_ini']);
		$fech_fin		= trim($_REQUEST['fech_fin']);
		$empcod			= base64_decode($_REQUEST['empcod']);
		$usuario		= base64_decode($_REQUEST['usuario']);
		$clicod			=	base64_decode($_REQUEST['clicod']);
		$OrdenProforma	= base64_decode($_REQUEST['OrdenProforma']);

		$sql_cabecera = "	select  n.negcne, negdes
						from	negocio n
						where	n.negcod='" . $codNegocio . "' ";
		$dsl_cabecera = $_SESSION['dbmssql']->getAll($sql_cabecera);
		foreach ($dsl_cabecera as $v => $reporte) {
			$negcne		=	trim($reporte['negdes']);
		}

		//Logo
		$this->Image('bxz.jpg', 18, 15, 23);
		$this->SetFont('Arial', '', 10);
		$this->Ln(12);
		$this->Cell(83);
		$this->Cell(15, 10, 'REPORTE DETALLADO DE PROFORMA', 0, 0, 'C');
		$this->Ln(14);
		$this->SetFont('Arial', '', 8);
		//Usuario
		$sql_obraUsuario = "select 	pe.perapepat+' '+pe.perapemat+', '+pe.pernom nombre 
							from 	personal pe 
							where 	percod='" . $usuario . "' ";
		$dsl_obraUsuario = $_SESSION['dbmssql']->getAll($sql_obraUsuario);
		foreach ($dsl_obraUsuario as $v => $user) {
			$nombreUsuario	=	trim($user['nombre']);
		}

		$this->Cell(10, 2, 'Negocio: ' . $negcne, 'C');
		$this->Cell(90);
		$this->Cell(89, 2, 'Usuario: ' . $nombreUsuario, 0, 0, 'R');

		/* fecha de impresion*/
		$sql_fec_print = "select	day(getdate()) dia, month(getdate()) mes, year(getdate()) anio, right(getdate(),8) hora	";
		$dsl_fec_print = $_SESSION['dbmssql']->getAll($sql_fec_print);
		foreach ($dsl_fec_print as  $kal => $info) {
			$dia_print	  = (string)str_pad(rtrim($info['dia']), 2, '0', STR_PAD_LEFT);
			$mes_print	  = (string)str_pad(rtrim($info['mes']), 2, '0', STR_PAD_LEFT);
			$anio_print	  = rtrim($info['anio']);
			$hora_print	  = trim($info['hora']);
			$impresoFec	  = $dia_print . '-' . $mes_print . '-' . $anio_print . ' ' . $hora_print;
		}

		$this->Ln(4);
		$this->Cell(30, 2, 'Fecha de Reporte: ' . $impresoFec, 0, 0, 'L');
		$this->Ln(4);
		$fe_ini	= trim($_REQUEST['fech_ini']);
		$fe_fin	= trim($_REQUEST['fech_fin']);
		if ($fe_ini != "" || $fe_fin != "") {
			$fecha = explode('/', $fe_ini);
			$fecha1 = explode('/', $fe_fin);
			$dia_ini = $fecha[0];
			$mes_ini = $fecha[1];
			$ano_ini = $fecha[2];
			$dia_fin = $fecha1[0];
			$mes_fin = $fecha1[1];
			$ano_fin = $fecha1[2];

			$fe_ini_rep = "$dia_ini-$mes_ini-$ano_ini";
			$fe_fin_rep = "$dia_fin-$mes_fin-$ano_fin";

			$this->Cell(30, 2, 'Rango de Reporte: ' . $fe_ini_rep . ' al ' . $fe_fin_rep, 0, 0, 'L');
		}

		$this->Ln(5);
	}

	function depurar($texto_malo)
	{
		$texto_malo = str_replace("Ñ", "�", $texto_malo);
		$texto_malo = str_replace("á", "�", $texto_malo);
		$texto_malo = str_replace("é", "�", $texto_malo);
		$texto_malo = str_replace("í", "�", $texto_malo);
		$texto_malo = str_replace("ó", "�", $texto_malo);
		$texto_malo = str_replace("ú", "�", $texto_malo);
		$texto_malo = str_replace("�", "", $texto_malo);
		$texto_malo = str_replace("Ó", "�", $texto_malo);
		$texto_malo = str_replace("ñ", "�", $texto_malo);
		return $texto_malo;
	}

	//Pie de p�gina
	function Footer()
	{
		$codNegocio = base64_decode($_REQUEST['codNegocio']);
		$sql_cabecera = "	select	e.empdir, e.empruc, e.empraz 
						from	empresa e,  cabordprof f 
						where	e.empcod=f.codprofemp and f.codordprof='" . $codigoFac . "' ";
		$dsl_cabecera = $_SESSION['dbmssql']->getAll($sql_cabecera);
		foreach ($dsl_cabecera as $v => $reporte) {
			$empdir = $this->depurar(trim($reporte['empdir']));
		}

		$this->SetY(-10);
		$this->SetFont('Arial', 'I', 7);
	}

	function imprimer_cabeceras($header)
	{
		$w = array(20, 20, 55, 55, 15, 26);
		$this->SetTextColor(0);
		$this->SetFillColor(204, 204, 204);
		for ($i = 0; $i <= 5; $i++) {
			$this->Cell($w[$i], 4, $header[$i], 1, 0, 'C', 1);
		}
		$this->ln();
		$this->ln();
	}

	function calcular_valores()
	{
		$codNegocio		= base64_decode($_REQUEST['codNegocio']);
		$Empresacod		= base64_decode($_REQUEST['empcod']);
		$Clientecod		= base64_decode($_REQUEST['clicod']);
		$usuario		= base64_decode($_REQUEST['usuario']);
		$fech_ini		= trim($_REQUEST['fech_ini']);
		$fech_fin		= trim($_REQUEST['fech_fin']);

		if ($fech_ini != "" and $fech_fin != "") {
			$fecha = explode('/', $fech_ini);
			$fecha1 = explode('/', $fech_fin);

			$dia_ini = $fecha[0];
			$mes_ini = $fecha[1];
			$ano_ini = $fecha[2];
			$dia_fin = $fecha1[0];
			$mes_fin = $fecha1[1];
			$ano_fin = $fecha1[2];

			$f_ini = "$dia_ini-$mes_ini-$ano_ini";
			$f_fin = "$dia_fin-$mes_fin-$ano_fin 23:59:59";
		}

		$sql_det = " set dateformat dmy ";
		$sql_det .= "	select	p.codordprof as coddoc , p.fecreg as fecdoc,
								e.empraz, c.cliraz,
								p.tipmoneda, 'PROFORMA' as docu, 'OP' as clave, 'P' as sigla
						from	cabordprof p, empresa e, cliente c
						where	p.codprofemp=e.empcod and p.codprofcli=c.clicod and p.estado in ('P')
								and p.codprofneg = '" . $codNegocio . "' 
								and p.codprofcli = '" . $Clientecod . "' 
								and p.codprofemp = '" . $Empresacod . "' ";
		if ($fech_ini != "" and $fech_fin != "") {
			$sql_det  .= "			and convert(datetime,convert(varchar(10),p.Fecreg,103)) 
								between '" . $f_ini . "' and '" . $f_fin . "' ";
		}

		$sql_det  .= "	union ";
		$sql_det  .= "	select	ds.codorddscto as coddoc, ds.fecreg as fecdoc,
								e.empraz, c.cliraz,
								ds.tipmoneda, 'DESCUENTO' as docu, 'OD' as clave, ds.tipoDscto as sigla
						from	caborddscto ds, empresa e, cliente c
						where	ds.coddsctoemp=e.empcod and ds.coddsctocli=c.clicod and 
								ds.tipodoc in ('P') and ds.estado not in ('C','E')
								and ds.coddsctoneg = '" . $codNegocio . "'	
								and ds.coddsctocli = '" . $Clientecod . "'
								and ds.coddsctoemp = '" . $Empresacod . "' ";
		if ($fech_ini != "" and $fech_fin != "") {
			$sql_det  .= "			and convert(datetime,convert(varchar(10),ds.Fecreg,103)) 
								between '" . $f_ini . "' and '" . $f_fin . "' ";
		}

		$sql_det  .= "	union ";
		$sql_det  .= "	select	re.codregpag as coddoc, re.fecreg as fecdoc,
								e.empraz, c.cliraz,
								'' as tipmoneda, 'REG. PAGO' as docu, 'RP' as clave, re.tipoCob as sigla
						from	cabregpago re, empresa e, cliente c      
						where	re.codregemp=e.empcod and re.codregcli=c.clicod and 
								re.estado not in ('C','E')      and re.tipoCob ='C'
								and re.codregneg = '" . $codNegocio . "'
								and re.codregcli = '" . $Clientecod . "'
								and re.codregemp = '" . $Empresacod . "' ";
		if ($fech_ini != "" and $fech_fin != "") {
			$sql_det  .= "		and convert(datetime,convert(varchar(10),re.Fecreg,103)) 
							between '" . $f_ini . "' and '" . $f_fin . "' ";
		}
		// NOTA DE CREDITO PROFORMA
		$sql_det  .= "	union ";
		$sql_det  .= "	select	cp.CodOrdNotaCre as coddoc, cp.fecreg as fecdoc,
								e.empraz, c.cliraz,
								'' as tipmoneda, 'NC' as docu, 'NC' as clave, 'H' as sigla
						from	CABNOTACREDITO_PROF cp, empresa e, cliente c
						where	cp.CodNotaEmp=e.empcod and cp.CodNotaCli=c.clicod  and 
								cp.estado in('A') 
								and cp.CodNotaNeg = '" . $codNegocio . "'	";
		if ($Clientecod != '0') {
			$sql_det  .= "			and cp.CodNotaCli = '" . $Clientecod . "' ";
		}
		if ($Empresacod != '0') {
			$sql_det  .= "			and cp.CodNotaEmp = '" . $Empresacod . "' ";
		}

		$sql_det  .= "		and convert(datetime,convert(varchar(10),cp.Fecreg,103)) 
							between '" . $f_ini . "' and '" . $f_fin . "' ";
		// NOTA DE DEBITO PROFORMA
		$sql_det  .= "	union ";
		$sql_det  .= "	select	cp.CodOrdNotaDeb as coddoc, cp.fecreg as fecdoc,
						e.empraz, c.cliraz,
						'' as tipmoneda, 'ND' as docu, 'ND' as clave, 'I' as sigla
				from	CABNOTADEBITO_PROF cp, empresa e, cliente c
				where	cp.CodNotaEmp=e.empcod and cp.CodNotaCli=c.clicod  and 
						cp.estado in('A') 
						and cp.CodNotaNeg = '" . $codNegocio . "'	";
		if ($Clientecod != '0') {
			$sql_det  .= "			and cp.CodNotaCli = '" . $Clientecod . "' ";
		}
		if ($Empresacod != '0') {
			$sql_det  .= "			and cp.CodNotaEmp = '" . $Empresacod . "' ";
		}

		$sql_det  .= "		and convert(datetime,convert(varchar(10),cp.Fecreg,103)) 
					between '" . $f_ini . "' and '" . $f_fin . "' ";
		$sql_det  .= "		order by 2 ";

		// echo $sql_det;
		$query_orderC = $_SESSION['dbmssql']->getAll($sql_det);
		$detalles = array(array());

		$det_tota = array();
		$i = 0;
		$j = 0;
		$p = 0;
		$w	= array(20, 20, 55, 55, 15, 26);

		$SUMA_PROF = 0;
		$SUMA_REGPAG = 0;
		$SUMA_DSCTO = 0;
		$plus = 0;
		$contador = 0;
		$SUMA_CONTINUA = 0;

		foreach ($query_orderC as $pro => $valores) {
			$coddoc	= $valores['coddoc'];
			$sigla	= $valores['sigla'];
			switch ($sigla) {
				case 'P': {
						$abrev = "-PROF";
					}
					break;
				case 'D': {
						$abrev = "-DVOL";
					}
					break;
				case 'S': {
						$abrev = "-SLDO";
					}
					break;
				case 'O': {
						$abrev = "-OTRO";
					}
					break;
				case 'Q': {
						$abrev = "-CHQ";
					}
					break;
				case 'C': {
						$abrev = "-CSH";
					}
					break;
				case 'L': {
						$abrev = "-LTR";
					}
					break;
				case 'H': {
						$abrev = "-NC";
					}
					break;
				case 'I': {
						$abrev = "-ND";
					}
					break;
			}
			$Fecha		= $valores['fecdoc'];
			$empraz		= $valores['empraz'];
			$cliraz		= $valores['cliraz'];
			$tipmoneda	= $valores['tipmoneda'];
			switch ($tipmoneda) {
				case 'S': {
						$moneda = "SOLES";
					}
					break;
				case 'D': {
						$moneda = "DOLARES";
					}
					break;
			}
			$docu		= $valores['docu'];
			$clave		= $valores['clave'];

			$NewCodigoComp = (string)str_pad($coddoc, 7, '0', STR_PAD_LEFT);

			$this->SetFont('Arial', '', 6);
			$this->SetFillColor(224, 224, 224);

			$this->Cell(20, 4, $coddoc . $abrev, 'LRTB', 0, 'C', 1);
			$this->Cell(20, 4, $Fecha, 'LRTB', 0, 'C', 1);
			$this->Cell(110, 4, $cliraz, 'LRTB', 0, 'C', 1);
			$this->Cell(15, 4, $moneda, 'LRTB', 0, 'C', 1);
			$this->Cell(26, 4, $docu, 'LRTB', 0, 'C', 1);
			$this->ln();

			$this->SetFillColor(255, 255, 255);
			if ($clave == "OP") {
				$SUMA = 0;
				$this->Cell(55, 4, 'Producto', 'LTB', 0, 'C', 1);
				$this->Cell(50, 4, 'Especificacion', 'TB', 0, 'C', 1);
				$this->Cell(53, 4, 'Servicio', 'TB', 0, 'C', 1);
				$this->Cell(13, 4, 'Cantidad', 'LRTB', 0, 'C', 1);
				$this->Cell(10, 4, 'P. Unit', 'LRTB', 0, 'C', 1);
				$this->Cell(10, 4, 'Importe', 'LRTB', 0, 'C', 1);
				$this->SetFillColor(255, 255, 255);
				$this->ln();

				$sql  = "select	p.ProDes,	
								(	select	especificacion from detordserv 
									where	CodServPro=d.CodProfPro and 
											CodServNeg=d.codprofneg and
											CodOrdServ=d.CodProfServ and
											ServDes=d.ServDes and 
												estado not in ('C','E','N')
								) as especificacion, 
								(	select	servdes from detordserv 
									where	CodServPro=d.CodProfPro and 
											CodServNeg=d.codprofneg and
											CodOrdServ=d.CodProfServ and
											ServDes=d.ServDes and 
												estado not in ('C','E','N')
								) as servdes,
								d.cantrecep,
								(select MedAbrev from medida 
								where medcod=d.medida) as abrev_medida,
								d.preciounit, d.preciototal, c.CodProfServ
						from	cabordprof c, detordprof d, producto p
						where	c.codordprof=d.codordprof and c.codprofneg=d.codprofneg and 
								d.CodProfPro=p.ProCod and c.codordprof = '" . $coddoc . "' and 
								d.codprofneg = '" . $codNegocio . "' and c.estado ='P'";
				$query_detalle = $_SESSION['dbmssql']->getAll($sql);
				foreach ($query_detalle  as $idmat => $value) {
					$ProDes  		=  $this->depurar($value['ProDes']);
					$especificacion =  $this->depurar($value['especificacion']);
					$servdes  		=  $this->depurar($value['servdes']);
					$cantrecep    	=  $value['cantrecep'];
					$abrev_medida   =  $value['abrev_medida'];
					$preciounit 	=  $value['preciounit'];
					$preciototal 	=  $value['preciototal'];
					$CodProfServ 	=  $value['CodProfServ'];

					$SUMA = $SUMA + $preciototal;

					$this->Cell(55, 5, $ProDes, 'LTB', 0, 'L', 1);
					$this->Cell(53, 5, $especificacion, 'TB', 0, 'C', 1);
					$this->Cell(50, 5, $servdes, 'TB', 0, 'C', 1);
					$this->Cell(13, 5, $cantrecep . ' ' . $abrev_medida, 'LRTB', 0, 'C', 1);
					$this->Cell(10, 5, $preciounit, 'LRTB', 0, 'C', 1);
					$this->Cell(10, 5, $preciototal, 'LRTB', 0, 'C', 1);
					$this->ln();
				}
				$this->ln(2);

				$SUMA_PROF = $SUMA_PROF + $SUMA;

				///Gu�as despachos
				$con2 = " ";
				$sql_GuiaDespacho = " select 	CodOrdDesp 
									from 	CabOrdDesp 	
									where 	CodOrdServ = '" . $CodProfServ . "' and 
											CodDespNeg = '" . $codNegocio . "' and 
											estado not in ('E','C') 
									order by CodOrdDesp desc";
				$dsl_GuiaDespacho = $_SESSION['dbmssql']->getAll($sql_GuiaDespacho);
				foreach ($dsl_GuiaDespacho as $val => $value) {
					$codorddesp	= trim($value['CodOrdDesp']);
					$con2 = trim($codorddesp . "-" . $con2);
				}
				$CodOrdDesp = substr($con2, 0, strlen($con2) - 1);

				/////LOTE
				$sql_Lote = "	select 	Lote from CabOrdServ 
							where 	CodOrdServ='" . $CodProfServ . "' and 
									CodServNeg='" . $codNegocio . "'  and 
									estado not in ('E','C')";
				$dsl_Lote = $_SESSION['dbmssql']->getAll($sql_Lote);
				foreach ($dsl_Lote as $val => $value) {
					$Lote = trim($value['Lote']);
				}
				if ($Lote == 0) $Lote = "No Definido";

				$sql_Lote = "	select 	Lote from CabOrdServ 
							where 	CodOrdServ='" . $CodProfServ . "' and 
									CodServNeg='" . $codNegocio . "' and 						
									Estado  in ('T','P')";
				$dsl_Lote = $_SESSION['dbmssql']->getAll($sql_Lote);
				foreach ($dsl_Lote as $val => $inf) {
					$Lote = trim($inf['Lote']);
				}
				$sql_suma_ing = "  	SELECT  isnull(sum(cantidadrecep),0) as suma_ing
									FROM    DETORDDESP
									WHERE   CodDespNeg = '" . $codNegocio . "' and 
											CodOrdServ = '" . $CodProfServ . "' and
											Estado in ('I') ";
				$dsl_suma_ing = $_SESSION['dbmssql']->getAll($sql_suma_ing);
				foreach ($dsl_suma_ing as $val => $value) {
					$sum_ing	= trim($value['suma_ing']);
				}

				$SALDO = $Lote - $sum_ing;

				$this->Cell(77, 5, 'O.S.: ' . $CodProfServ, 'LT', 0, 'L', 1);
				$this->Cell(47, 5, 'Lote: ' . $Lote, 'T', 0, 'L', 1);
				$this->Cell(47, 5, 'Saldo: ' . $SALDO, 'T', 0, 'L', 1);
				$this->Cell(20, 5, 'Total S/.:' . round($SUMA, 2), 'RT', 0, 'C', 1);
				$this->ln();
				$this->Cell(77, 5, 'O.D.: ' . $CodOrdDesp, 'LB', 0, 'L', 1);
				$this->Cell(114, 5, '', 'BR', 0, 'L', 1);
				$this->ln(2);
				/////////////////////$SUMA_CONTINUA=$SUMA_CONTINUA+$SUMA;
			}
			if ($clave == "OD") {
				$this->Cell(158, 4, 'Producto', 'LRTB', 0, 'C', 1);
				$this->Cell(13, 4, 'Cantidad', 'LRTB', 0, 'C', 1);
				$this->Cell(10, 4, 'P. Unitario', 'LRTB', 0, 'C', 1);
				$this->Cell(10, 4, 'Importe', 'LRTB', 0, 'C', 1);
				$this->SetFillColor(255, 255, 255);
				$this->ln();

				$SUMA_OD = 0;
				$sql  = "select	p.prodes, d.cantidad, d.preciounit, d.importe
						 from	detorddscto d, producto p
						 where	d.coddsctoprod = p.procod and 
								d.coddsctoneg = '" . $codNegocio . "' and
								d.codorddscto = '" . $coddoc . "' and 
								d.estado not in ('C','E') ";
				$query  = $_SESSION['dbmssql']->getAll($sql);
				foreach ($query as $item => $value) {
					$prodes  	=  $value['prodes'];
					$cantidad 	=  $value['cantidad'];
					$preciounit =  $value['preciounit'];
					$importe    =  $value['importe'];

					$prodes	= $this->depurar($prodes);
					$SUMA_OD = $SUMA_OD + $importe;

					$this->Cell(158, 4, $prodes, 'LRTB', 0, 'C', 1);
					$this->Cell(13, 4, $cantidad, 'LRTB', 0, 'C', 1);
					$this->Cell(10, 4, $preciounit, 'LRTB', 0, 'C', 1);
					$this->Cell(10, 4, $importe, 'LRTB', 0, 'C', 1);
					$this->ln();
				}
				$this->ln(2);

				$sql_co  = "select 	comentario 
							from 	caborddscto 
							where 	codorddscto='" . $coddoc . "' and 
									coddsctoneg= '" . $codNegocio . "' and
									estado not in ('C','E') ";
				$query_co  = $_SESSION['dbmssql']->getAll($sql_co);
				foreach ($query_co as $item => $co) {
					$comentario_co  =  $co['comentario'];
				}

				$this->Cell(171, 5, 'Comentario: ' . $comentario_co, 'LRTB', 0, 'L', 1);
				$this->Cell(20, 5, 'Total S/.: ' . round($SUMA_OD, 2), 'LRTB', 0, 'L', 1);
				$this->ln();

				$SUMA_DSCTO = $SUMA_DSCTO + $SUMA_OD;
				///nuevo
				$SUMA_CONTINUA = $SUMA_CONTINUA - $SUMA_OD;
			}
			if ($clave == "RP") {
				$this->Cell(91, 4, 'Comentario', 'LRTB', 0, 'C', 1);
				$this->Cell(20, 4, 'Ord. de Pago', 'LRTB', 0, 'C', 1);
				$this->Cell(20, 4, 'Recibo', 'LRTB', 0, 'C', 1);
				$this->Cell(40, 4, 'Cobrador', 'LRTB', 0, 'C', 1);
				$this->Cell(20, 4, 'Importe', 'LRTB', 0, 'C', 1);
				$this->SetFillColor(255, 255, 255);
				$this->ln();

				$sql  = "select	re.comentario, re.codordpag, re.recibo, re.cobrador, re.SumAbon,re.TotalRecib
						 from	cabregpago re, empresa e, cliente c
						 where	re.codregemp = e.empcod and re.codregcli = c.clicod and 
								re.estado not in ('C','E') and    re.tipoCob ='C' and
								re.codregneg = '" . $codNegocio . "' and 
								re.codregpag = '" . $coddoc . "'";
				$query  = $_SESSION['dbmssql']->getAll($sql);
				foreach ($query as $item => $value) {
					$comentario =  $value['comentario'];
					$codordpag 	=  $value['codordpag'];
					$recibo 	=  $value['recibo'];
					$cobrador   =  $value['cobrador'];
					$SumAbon 	=  trim($value['SumAbon']);
					$TotalRecib	=  trim($value['TotalRecib']);

					$recib_reg_dinero = $TotalRecib;
					if ($TotalRecib == "0")
						$recib_reg_dinero = (float)$SumAbon;

					$comentario	= $this->depurar($comentario);
					$cobrador	= $this->depurar($cobrador);
					$this->Cell(91, 4, $comentario, 'LRTB', 0, 'L', 1);
					$this->Cell(20, 4, $codordpag, 'LRTB', 0, 'C', 1);
					$this->Cell(20, 4, $recibo, 'LRTB', 0, 'C', 1);
					$this->Cell(40, 4, $cobrador, 'LRTB', 0, 'C', 1);
					$this->Cell(20, 4, $recib_reg_dinero, 'LRTB', 0, 'C', 1);
					$this->ln();
				}

				$this->ln(2);
				$this->SetFont('Arial', 'B', 6);
				////despues de un registro de pago capturo su valor

				////////////////$SUMA_CONTINUA=$SUPER_SALDO;	
			}

			if ($clave == "NC") {
				$this->Cell(111, 4, 'Descripcion', 'LRTB', 0, 'C', 1);
				$this->Cell(20, 4, 'Cant', 'LRTB', 0, 'C', 1);
				$this->Cell(40, 4, 'P. Unitario', 'LRTB', 0, 'C', 1);
				$this->Cell(20, 4, 'Importe', 'LRTB', 0, 'C', 1);
				$this->SetFillColor(255, 255, 255);
				$this->ln();

				$sql  = "select	de.Glosa, de.Cantidad, m.MedAbrev as medida, de.Punitario, de.Monto 
                    from DETNOTACREDITO_PROF de left join MEDIDA m on de.MedCod=m.MedCod
                    where de.estado not in('C','E') and
                        de.CodNotaNeg='" . $codNegocio . "' and 
                        de.CodOrdNotaCre='" . $coddoc . "' ";
				$query  = $_SESSION['dbmssql']->getAll($sql);
				foreach ($query as $item => $value) {
					$Glosa 	  =  $value['Glosa'];
					$cantidad = $value['Cantidad'];
					$medida   = $value['medida'];
					$p_unit   = $value['Punitario'];
					$monto	  = trim($value['Monto']);
					$Glosa	  = $this->depurar($Glosa);

					$this->Cell(111, 4, $Glosa, 'LRTB', 0, 'L', 1);
					$this->Cell(20, 4, $cantidad . ' ' . $medida, 'LRTB', 0, 'C', 1);
					$this->Cell(40, 4, $p_unit, 'LRTB', 0, 'C', 1);
					$this->Cell(20, 4, $monto, 'LRTB', 0, 'C', 1);
					$this->ln();
				}

				$this->ln(2);
				$this->SetFont('Arial', 'B', 6);
				////despues de un registro de pago capturo su valor
			}

			// ✅ HECHO: NOTA DE DEBITO PROFORMA
			if ($clave == "ND") {
				$this->Cell(111, 4, 'Descripcion', 'LRTB', 0, 'C', 1);
				$this->Cell(20, 4, 'Cant', 'LRTB', 0, 'C', 1);
				$this->Cell(40, 4, 'P. Unitario', 'LRTB', 0, 'C', 1);
				$this->Cell(20, 4, 'Importe', 'LRTB', 0, 'C', 1);
				$this->SetFillColor(255, 255, 255);
				$this->ln();

				$sql  = "select	de.Glosa, de.Cantidad, m.MedAbrev as medida, de.Punitario, de.Monto 
                    from DETNOTADEBITO_PROF de left join MEDIDA m on de.MedCod=m.MedCod
                    where de.estado not in('C','E') and
                        de.CodNotaNeg='" . $codNegocio . "' and 
                        de.CodOrdNotaDeb='" . $coddoc . "' ";
				$query  = $_SESSION['dbmssql']->getAll($sql);
				foreach ($query as $item => $value) {
					$Glosa 	  =  $value['Glosa'];
					$cantidad = $value['Cantidad'];
					$medida   = $value['medida'];
					$p_unit   = $value['Punitario'];
					$monto	  = trim($value['Monto']);
					$Glosa	  = $this->depurar($Glosa);

					$this->Cell(111, 4, $Glosa, 'LRTB', 0, 'L', 1);
					$this->Cell(20, 4, $cantidad . ' ' . $medida, 'LRTB', 0, 'C', 1);
					$this->Cell(40, 4, $p_unit, 'LRTB', 0, 'C', 1);
					$this->Cell(20, 4, $monto, 'LRTB', 0, 'C', 1);
					$this->ln();
				}

				$this->ln(2);
				$this->SetFont('Arial', 'B', 6);
				////despues de un registro de pago capturo su valor
			}

			/*=============A G R E G A D O ( Junio 2014 ) ==========*/
			$COD_NEGO = $codNegocio; /////	=base64_decode($_REQUEST['codNegocio']);
			$COD_EMP  = $Empresacod; ///	=base64_decode($_REQUEST['empcod']);
			$COD_CLI  = $Clientecod; ////	=base64_decode($_REQUEST['clicod']);
			$fech_ini = '';
			$fech_fin = '';

			$suma_x = 0;
			$Sql2 = " execute BF_Exportar_Proforma_Detallado  '" . $COD_NEGO . "','" . $COD_CLI . "','" . $COD_EMP . "','" . $f_ini . "','" . $f_fin . "'";
			$dsl_Sql2 = $_SESSION['dbmssql']->getAll($Sql2);
			foreach ($dsl_Sql2 as $item => $info) //calculando el total de las cantidades
			{
				$tipodoc	= $info['tipodoc'];
				$codigo		= $info['codigo'];
				$cliraz		= $info['cliraz'];
				$empraz		= $info['empraz'];
				$fecha		= $info['fecha'];
				$importe1	= $info['importe1'];
				$importe2	= $info['importe2'];

				if ($info['tipodoc'] == 'PROF') {
					$CODIGO 		= 	$info['codigo'];
					$importe1 		=	$info['importe1'];
					$IMPORTE_REAL	=	$importe1;
					$tipo_cobranza 	= 	$info['tipodoc'];
				}
				if ($info['tipodoc'] == 'DSCTO') {
					$CODIGO 		= 	$info['codigo'];
					$importe1 		=	$info['importe1'];
					$IMPORTE_REAL	=	(-1) * $importe1;
					$tipo_cobranza 	= 	$info['tipodoc'];
				}
				if ($info['tipodoc'] == 'RP') {
					//Como hay dos tipos de pago.
					$importe1 =	$info['importe1'];
					$importe2 = $info['importe2'];
					$IMPORTE_REAL = (-1) * $importe1;

					if ($importe1 == 0)
						$IMPORTE_REAL = (-1) * $importe2;

					$sql_recibo = "	select 	recibo, codordpag 
									from 	cabregpago 
									where 	codregpag=" . $info['codigo'] . " and  
											tipoCob ='C' and 
											codregneg='" . $COD_NEGO . "'";
					$dsl_recib = $_SESSION['dbmssql']->getAll($sql_recibo);
					foreach ($dsl_recib as $row => $data) {
						$recibo 	= $data['recibo'];
						$codordpag 	= $data['codordpag'];
					}

					$codigo = $codordpag . '-' . $info['codigo'] . '-' . $recibo;
					$tipo_cobranza 	= 'OP-' . $info['tipodoc'] . '-R';
				}
				if ($info['tipodoc'] == 'NC') {
					$CODIGO 		= 	$info['codigo'];
					$importe2 		=	$info['importe2'];
					$IMPORTE_REAL	=	(-1) * $importe2;
					$tipo_cobranza 	= 	$info['tipodoc'];
				}
				if ($info['tipodoc'] == 'ND') {
					$CODIGO 		= 	$info['codigo'];
					$importe2 		=	$info['importe2'];
					$IMPORTE_REAL	=	$importe2;
					$tipo_cobranza 	= 	$info['tipodoc'];
				}
				$suma_x = $suma_x + $IMPORTE_REAL;
			}
			$SUMA_CONTINUA = $suma_x;

			/*==================================*/
			$this->ln(3);
			$this->Cell(191, 4, 'SALDO AL MOMENTO (S/.): ' . number_format($SUMA_CONTINUA, 2), 'T', 0, 'C', 1);
			$this->ln(1);
			$this->SetFont('Arial', '', 6);
		}
	}
}

$sql = "select replace(replace(replace(LEFT(convert(varchar,getdate(),103),12)+''+right(getdate(),8),' ',''),':',''),'/','') as fecha ";
$dsl = $_SESSION['dbmssql']->getAll($sql);
foreach ($dsl as $v => $fec) {
	$nombrefile = $fec['fecha'];
}

$header = array('COD. DOC.', 'FECHA DOC.', 'EMPRESA', 'CLIENTE', 'MONEDA', 'DOCUMENTO');
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->SetFont('Arial', '', 6);
$pdf->AddPage();
$pdf->CabeceraPrincipal();
$pdf->imprimer_cabeceras($header);
$pdf->calcular_valores();
//$pdf->imprimirtotales();
$pdf->Output('REPDETPRFM' . $nombrefile . '.pdf', 'D');
//$pdf->Output();
