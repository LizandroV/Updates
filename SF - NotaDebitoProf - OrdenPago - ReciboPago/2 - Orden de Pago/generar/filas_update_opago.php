<?php
header('Content-Type: text/xml; charset=utf-8'); //ISO-8859-1
session_start();
require('../../../includes/dbmssql_cfg.php');

$codNegocio		= $_REQUEST['codNegocio'];
$codCobro		= $_REQUEST['codCobro'];
$codPago		= $_REQUEST['codPago'];

$i = 0;

$sql_pro = " execute BF_DevolverDatos " . $codNegocio . ",'" . $codCobro . "'," . $codPago . " ";
$query_pro  = $_SESSION['dbmssql']->getAll($sql_pro);
foreach ($query_pro as $pro => $descripcion) {
	$codigo		= $descripcion['CODIGO'];
	$fisico		= $descripcion['FISICO'];
	$doc		= $codigo . ' (' . $fisico . ')';
	$fecreg		= $descripcion['FECDOC'];
	$impconigv	= round($descripcion['MONTDOC'], 2);
	$impxcobrar	= round($descripcion['MONTCOB'], 2);
	$detraccion	= round($descripcion['MONTDET'], 2);
	$retencion	= round($descripcion['MONTRET'], 2);
	$tipmoneda	= $descripcion['TIPMON'];
	$codpag		= $descripcion['CODPAG'];
	$tipodoc	= $descripcion['doc'];   //// tipo doc del detalle ( factu, profo, credito ,,, debito
	$cambio		= $descripcion['TIPCAMBIO'];
	$readonly = '';
	$checked = '';
	$checked_ret = '';

	$od_servicio = $descripcion['CODSERVICIO'];

	switch ($tipmoneda) {
		case 'S': {
				$moneda = 'SOLES';
				break;
			};
		case 'D': {
				$moneda = 'DOLARES';
				break;
			};
	}

	if ($tipodoc == 'F' ||  $tipodoc == 'P') {
		$color = '#dee7ec';

		if ($tipodoc == 'F') {
			$abrev = 'fac';
			if ($detraccion > 0) {
				$readonly = 'readonly';
				$checked = 'checked';
			}
			if ($retencion > 0) {
				$disabled_ret = 'readonly';
				$checked_ret = 'checked';
			}
		}

		if ($tipodoc == 'P') {
			$abrev = 'prof';
			$readonly = 'readonly';
			$checked = 'checked';
			$checked_ret = 'checked';
		}

		///////GUIA EXTERNA
		$sql_GuiaExt = "	select	cs.GuiaExt
							from	cabord" . $abrev . " cf, cabordserv cs  
							where	cf.cod" . $abrev . "serv=cs.codordserv and cf.Cod" . $abrev . "Neg=cs.CodServNeg and 
									cf.codord" . $abrev . "='" . $codigo . "' 
									and cf.cod" . $abrev . "neg='" . $codNegocio . "'  and cf.estado not in ('E','C')
									and cs.estado not in ('E','C') ";
		$dsl_GuiaExt = $_SESSION['dbmssql']->getAll($sql_GuiaExt);
		foreach ($dsl_GuiaExt as $val => $value) {
			$GuiaExt = trim($value['GuiaExt']);
		}
		if ($GuiaExt == "") $GuiaExt = "No Definido";

		///Guias despachos
		$con2 = " ";
		$sql_GuiaDespacho = " select 	GuiaDespacho 
								from 	CabOrdDesp 	
								where 	CodOrdServ='" . $od_servicio . "' and CodDespNeg='" . $codNegocio . "' and 
										estado not in ('E','C') 
								order by CodOrdDesp desc";
		$dsl_GuiaDespacho = $_SESSION['dbmssql']->getAll($sql_GuiaDespacho);
		foreach ($dsl_GuiaDespacho as $val => $value) {
			$codorddesp	= trim($value['GuiaDespacho']);
			$con2 = trim($codorddesp . "-" . $con2);
		}
		$GUIAS_DESPACHOS = substr($con2, 0, strlen($con2) - 1);
	}
	/////////////////////////////////////////////

	$aplica = 'aplica';

	//NOTA DE CREDITO PROFORMA
	if ($tipodoc == 'C' ||  $tipodoc == 'D' || $tipodoc == 'H' || $tipodoc == 'I') {
		if ($tipodoc == 'C') {
			$GuiaExt = '<strong>NOTA DE</strong>';
			$GUIAS_DESPACHOS = '<strong>CREDITO</strong>';
			$color = '#CEF6CE';
		}

		if ($tipodoc == 'D') {
			$GuiaExt = '<strong>NOTA DE</strong>';
			$GUIAS_DESPACHOS = '<strong>DEBITO</strong>';
			$color = '#F5ECCE';
			$aplica = 'aplica_solo_debito';   //Nombre de la funcion js agregada solo para DEBITO, Junio 2016
		}

		if ($tipodoc == 'H') {
			$GuiaExt = '<strong>NOTA DE</strong>';
			$GUIAS_DESPACHOS = '<strong>CREDITO PROFORMA</strong>';
			$color = '#CEF6CE';
		}

		if ($tipodoc == 'I') {
			$GuiaExt = '<strong>NOTA DE</strong>';
			$GUIAS_DESPACHOS = '<strong>DEBITO PROFORMA</strong>';
			$color = '#CEF6CE';
		}
	}

	$valorProceso = $tipodoc;

?>
	<div id="divreg<?= $i ?>" style="width:100%;">
		<input name="valor_proceso" type="hidden" value="<?= $valorProceso ?>" id="valor_proceso<?= $i ?>" />
		<input name="cob" type="hidden" value="<?= $impxcobrar ?>" id="cob<?= $i ?>" />
		<input name="det" type="hidden" value="<?= $detraccion ?>" id="det<?= $i ?>" />
		<input name="ret" type="hidden" value="<?= $retencion ?>" id="ret<?= $i ?>" />
		<input name="cambio" type="hidden" value="<?= $cambio ?>" id="cambio<?= $i ?>" />
		<input id="tipodoc<?= $i ?>" type="hidden" value="<?= $tipodoc ?>" />
		<input name="puntero" type="hidden" value="<?= $codpag ?>" id="<?= $i ?>" />
		<input name="txt_moneda" type="hidden" value="<?= $tipmoneda ?>" id="txt_moneda<?= $i ?>" />
		<input name="txt_codigo" type="hidden" value="<?= $codigo ?>" id="txt_codigo<?= $i ?>" />
		<input name="txt_fechadoc" type="hidden" id="txt_fechadoc<?= $i ?>" style="text-align:right;" value="<?= $fecreg ?>" size="10">
		<table width="100%" border="0" cellpadding="0" cellspacing="0" class="smalltext">
			<tr onMouseOver="color1(this,'<?= $color ?>');" onMouseOut="color2(this,'#ffffff');">
				<td width="3%" height="28" align="center" valign="middle"><!--<img src="images/b_deltbl.png" onclick="eliminar_filas_opago('< ?=$i;?>');" alt="Eliminar Registro" width="16" height="16" border="0" align="absmiddle" style="cursor:pointer;"/>--></td>
				<td width="8%" align="center" valign="middle"><?= $doc ?></td>
				<td width="9%" align="center" valign="middle"><?= $fecreg ?></td>
				<td width="18%" align="center" valign="middle"><input readonly="readonly" name="txt_total" type="text" id="txt_total<?= $i ?>" style="text-align:right;" value="<?= $impconigv ?>" size="10" /><label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name="checkbox" type="checkbox" disabled id="checkbox<?= $i ?>" onchange="<?php echo $aplica; ?>('<?= $i ?>')" <?= $checked ?> /> &nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="checkbox_ret" disabled id="checkbox_ret<?= $i ?>" onchange="aplica_ret('<?= $i ?>')" <?= $checked_ret ?> /></label></td>
				<td width="17%" align="center" valign="middle"><input <?= $readonly ?> name="txt_cobrar" onblur="calcular_cob('<?= $i ?>');" type="text" id="txt_cobrar<?= $i ?>" style="text-align:right;" disabled="disabled" value="<?= $impxcobrar ?>" size="10"></td>
				<td width="8%" align="center" valign="middle">
					<input name="txt_detrac" disabled type="text" id="txt_detrac<?= $i ?>" onblur="calcular_det('<?= $i ?>');" style="text-align:right;" value="<?= $detraccion ?>" size="10">
				</td>
				<td width="8%" align="center" valign="middle"><input name="txt_reten" disabled type="text" id="txt_reten<?= $i ?>" onblur="calcular_ret('<?= $i ?>');" style="text-align:right;" value="<?= $retencion ?>" size="10"></td>
				<td width="8%" align="center" valign="middle"><label><?= $moneda ?></label></td>
				<td width="10%" align="center" valign="middle"><?= $GuiaExt ?></td>
				<td width="11%" align="center" valign="middle"><?= $GUIAS_DESPACHOS ?></td>
			</tr>
			<tr>
				<td colspan="20" bgcolor="#C0D8E0" height="2"></td>
			</tr>
		</table>
	</div>
<?
	$i++;
}
?>