<?php
header('Content-Type: application/vnd.ms-excel;');
session_start();
require('../includes/arch_cfg.php');
require('../includes/dbmssql_cfg.php');

$sql = "select replace(replace(replace(LEFT(convert(varchar,getdate(),103),12)+''+right(getdate(),8),' ',''),':',''),'/','') as fecha  ";
$dsl = $_SESSION['dbmssql']->getAll($sql);
foreach ($dsl as $v => $fec) {
	$num = $fec['fecha'];
}

header("Content-Disposition: attachment; filename=expo_prof_tot_$num.xls;charset=utf-8");
header("Pragma: no-cache");
header("Expires: 0");
?>
<html>

<head>
	<title>Exportacion_Proforma</title>
	<meta http-equiv="Content-Type" content="attachment; charset=utf-8" />
	<style type="text/css">
		.borde_xls {
			border: 1px solid #000;
		}

		.borde_botom_xls {
			border-bottom: 1px solid #000;
		}

		.borde_botom_separador {
			border-bottom: 1px dashed #000;
		}
	</style>
</head>

<body leftmargin="0" rightmargin="0" topmargin="0" bottommargin="0" style="width:700px">
	<?php
	$COD_NEGO = base64_decode(trim($_REQUEST['COD_NEGO']));
	$COD_EMP = base64_decode(trim($_REQUEST['COD_EMP']));

	$COD_CLI = base64_decode(trim($_REQUEST['COD_CLI']));
	$fech_ini = trim($_REQUEST['fech_ini']);
	$fech_fin = trim($_REQUEST['fech_fin']);

	$suma = 0;
	$sql_cabecera = "	select  n.negcne, n.negdes								
					from	negocio n
					where	n.negcod=" . $COD_NEGO . " ";
	$dsl_cab = $_SESSION['dbmssql']->getAll($sql_cabecera);
	foreach ($dsl_cab as $row => $fila) {
		$negdes = $fila['negdes'];
	}
	$Sql2 = " execute BF_Exportar_Proforma_Detallado  '" . $COD_NEGO . "','" . $COD_CLI . "','" . $COD_EMP . "','" . $fech_ini . "','" . $fech_fin . "'";
	?>
	<table width="999" cellpadding="0" cellspacing="0">
		<tr height="17">
			<td height="17" colspan="6"><strong>FLUJO DE PROFORMA DETALLADO | <?= $negdes ?></strong></td>
		</tr>
		<tr height="17">
			<td width="81" height="17">&nbsp;</td>
			<td width="77">&nbsp;</td>
			<td>&nbsp; </td>
			<td width="318">&nbsp;</td>
			<td width="85">&nbsp;</td>
			<td width="92">&nbsp;</td>
		</tr>
		<tr height="17">
			<td height="17" align="center" valign="middle"><strong>TIPO DOC</strong></td>
			<td align="center" valign="middle"><strong>CODIGO</strong></td>
			<td align="center" valign="middle"><strong>CLIENTE</strong></td>
			<td align="center" valign="middle"><strong>EMPRESA</strong></td>
			<td align="center" valign="middle"><strong>FECHA</strong></td>
			<td align="center" valign="middle"><strong>IMPORTE</strong></td>
		</tr>
		<?php
		$dsl_Sql2 = $_SESSION['dbmssql']->getAll($Sql2);
		foreach ($dsl_Sql2 as $row => $info) {
			if ($info['tipodoc'] == 'PROF') {
				$CODIGO 		= $info['codigo'];
				$importe1 		=	$info['importe1'];
				$IMPORTE_REAL	=	$importe1;
				$tipo_cobranza 	= $info['tipodoc'];
			}
			if ($info['tipodoc'] == 'DSCTO') {
				$CODIGO 		= $info['codigo'];
				$importe1 		=	$info['importe1'];
				$IMPORTE_REAL	=	(-1) * $importe1;
				$tipo_cobranza 	= $info['tipodoc'];
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

				$CODIGO = $codordpag . '-' . $info['codigo'] . '-' . $recibo;
				$tipo_cobranza 	= 'OP-' . $info['tipodoc'] . '-R';
			}
			if ($info['tipodoc'] == 'NC') {
				$CODIGO 		= $info['codigo'];
				$importe2 		= $info['importe2'];
				$IMPORTE_REAL	= (-1) * $importe2;
				$tipo_cobranza 	= $info['tipodoc'];
			}
			if ($info['tipodoc'] == 'ND') {
				$CODIGO 		= $info['codigo'];
				$importe2 		= $info['importe2'];
				$IMPORTE_REAL	= $importe2;
				$tipo_cobranza 	= $info['tipodoc'];
			}

		?>
			<tr height="17">
				<td height="17" align="center"><?= $tipo_cobranza ?></td>
				<td align="center"><?= $CODIGO ?></td>
				<td><?= $info['cliraz'] ?></td>
				<td><?= $info['empraz'] ?></td>
				<td align="center"><?= $info['fecha'] ?></td>
				<td align="right"><?= number_format($IMPORTE_REAL, 2) ?></td>
			</tr>
		<?php

			$suma = $suma + $IMPORTE_REAL;
		}
		?>
		<tr height="17">
			<td height="17" align="center">&nbsp;</td>
			<td align="center">&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td align="center">&nbsp;</td>
			<td align="right">&nbsp;</td>
		</tr>
		<tr height="17">
			<td height="17" align="center">&nbsp;</td>
			<td align="center">&nbsp;</td>
			<td>&nbsp;</td>
			<td colspan="2" align="right">Saldo al Momento</td>
			<td align="right"><?= number_format($suma, 2) ?></td>
		</tr>
		<tr height="17">
			<td height="17" align="center">&nbsp;</td>
			<td align="center">&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td align="center">&nbsp;</td>
			<td align="right">&nbsp;</td>
		</tr>
	</table>
</body>

</html>