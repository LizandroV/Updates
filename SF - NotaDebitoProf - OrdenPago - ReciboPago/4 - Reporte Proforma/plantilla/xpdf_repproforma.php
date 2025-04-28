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
		/*-----------------------------------------------AGREGADO-----------------------------------------*/
		$codNegocio		= base64_decode($_REQUEST['codNegocio']);
		$fech_ini		= trim($_REQUEST['fech_ini']);
		$fech_fin		= trim($_REQUEST['fech_fin']);
		$empcod			= base64_decode($_REQUEST['empcod']);
		$usuario		= base64_decode($_REQUEST['usuario']);
		$clicod			= base64_decode($_REQUEST['clicod']);
		$OrdenProforma	= base64_decode($_REQUEST['OrdenProforma']);
		$gproforma		= base64_decode($_REQUEST['gproforma']);

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
		$this->Cell(15, 10, 'REPORTE DE PROFORMA TOTALIZADO', 0, 0, 'C');
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
			$empdir		=	$this->depurar(trim($reporte['empdir']));
		}

		$this->SetY(-10);
		$this->SetFont('Arial', 'I', 7);
		//$this->Cell(140,0,'Direccion: JR. SAN CRISTOBAL NRO. 1644 C.C. YUYI LIMA - LIMA - LA VICTORIA',0,0,'R');
	}

	function imprimer_cabeceras($header)
	{
		$w = array(18, 20, 75, 35, 20, 20);
		$this->SetTextColor(0);
		$this->SetFillColor(204, 204, 204);
		for ($i = 0; $i <= 5; $i++) {
			$this->Cell($w[$i], 3, $header[$i], 1, 0, 'C', 1);
		}
	}

	function calcular_valores()
	{
		$COD_NEGO		= base64_decode($_REQUEST['codNegocio']);
		$fech_ini		= trim($_REQUEST['fech_ini']);
		$fech_fin		= trim($_REQUEST['fech_fin']);
		$COD_EMP		= base64_decode($_REQUEST['empcod']);
		$usuario		= base64_decode($_REQUEST['usuario']);
		$COD_CLI		= base64_decode($_REQUEST['clicod']);
		$OrdenProforma	= base64_decode($_REQUEST['OrdenProforma']);
		$gproforma		= trim(base64_decode($_REQUEST['gproforma']));

		if ($fech_ini != "" and $fech_fin != "") {
			$fecha = explode('/', $fech_ini);
			$fecha1 = explode('/', $fech_fin);
			$dia_ini = $fecha[0];
			$mes_ini = $fecha[1];
			$ano_ini = $fecha[2];
			$dia_fin = $fecha1[0];
			$mes_fin = $fecha1[1];
			$ano_fin = $fecha1[2];
			$fech_ini = "$dia_ini-$mes_ini-$ano_ini";
			$fech_fin = "$dia_fin-$mes_fin-$ano_fin 23:59:59";
		}

		$suma = 0;
		$sql_cabecera = "	select  n.negcne, n.negdes								
						from	negocio n
						where	n.negcod=" . $COD_NEGO . " ";
		$dsl_cab = $_SESSION['dbmssql']->getAll($sql_cabecera);
		foreach ($dsl_cab as $row => $fila) {
			$negdes = $fila['negdes'];
		}
		$Sql2 = " execute BF_Exportar_Proforma_Detallado  '" . $COD_NEGO . "','" . $COD_CLI . "','" . $COD_EMP . "','" . $fech_ini . "','" . $fech_fin . "'";
		///echo $CONSULTA;

		//Colores, ancho de l�nea y fuente en negrita
		$this->SetFillColor(204, 204, 204);
		$this->SetTextColor(0);
		$this->SetDrawColor(10, 10, 10);
		$this->SetLineWidth(.2);
		//Cabecera
		$w = array(18, 20, 75, 35, 20, 20);
		for ($i = 0; $i < count($header); $i++)
			$this->Cell($w[$i], 3, $header[$i], 1, 0, 'C', 1);
		$this->Ln();

		//Restauraci�n de colores y fuentes
		$this->SetFillColor(255, 255, 255);
		$this->SetTextColor(0);
		$this->SetFont('Arial', '', 7);
		//Datos
		$fill = 0;
		// maximo de Filas //
		$max = 40;
		//$max=35;
		$i = $j = $suma = 0;

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

				/////if(strlen(trim($importe1))==0)
				if (($importe1) == 0)
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

			////////ESCRIBIENDO LAS FILAS/////////								
			$this->SetFont('Arial', '', 7);
			if ($i == $max) {
				$this->SetFillColor(204, 204, 204);
				$this->SetTextColor(0);
				$this->SetDrawColor(10, 10, 10);
				$this->SetLineWidth(.3);

				$w = array(18, 20, 75, 35, 20, 20);
				for ($i = 0; $i < count($header); $i++)
					$this->Cell($w[$i], 3, $header[$i], 1, 0, 'C', 1);
				$this->Ln();
				$i = 0;
			}

			$this->SetFont('Arial', '', 6);
			$this->Cell($w[0], 3, $tipo_cobranza, 'TLRB', 0, 'C', 2);
			$this->Cell($w[1], 3, $codigo, 'TLRB', 0, 'C', 2);
			$this->Cell($w[2], 3, $cliraz, 'TLRB', 0, 'C', 2);
			$this->Cell($w[3], 3, $empraz, 'TLRB', 0, 'C', 2);
			$this->Cell($w[4], 3, $fecha, 'TLRB', 0, 'C', 2);
			$this->Cell($w[5], 3, number_format($IMPORTE_REAL, 2), 'TLRB', 0, 'C', 2);
			$this->ln();

			$suma = $suma + $IMPORTE_REAL;
		}


		$this->ln();
		$this->Cell(140);
		$this->Cell(40, 0, 'SALDO AL MOMENTO:  ' . number_format($suma, 2), 0, 0, 'R');
	}
}
$sql = "select replace(replace(replace(LEFT(convert(varchar,getdate(),103),12)+''+right(getdate(),8),' ',''),':',''),'/','') as fecha  ";
$dsl = $_SESSION['dbmssql']->getAll($sql);
foreach ($dsl as $v => $fec) {
	$nombrefile = $fec['fecha'];
}

$header = array('TIPO DOC', 'CODIGO', 'CLIENTE', 'EMPRESA', 'FECHA', 'IMPORTE');
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->SetFont('Arial', '', 6);
$pdf->AddPage();
$pdf->CabeceraPrincipal();
$pdf->imprimer_cabeceras($header);
$pdf->calcular_valores();
//$pdf->imprimirtotales();
$pdf->Output('REPTOTPRFM' . $nombrefile . '.pdf', 'D');
//$pdf->Output();
