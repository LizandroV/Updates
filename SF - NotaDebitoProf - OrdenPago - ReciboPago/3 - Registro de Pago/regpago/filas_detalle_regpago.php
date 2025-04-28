<?php
header('Content-Type: text/xml; charset=utf-8'); //ISO-8859-1
session_start();
require('../../../includes/dbmssql_cfg.php');
$codNegocio	= $_REQUEST['codNegocio'];
$codCobro	= $_REQUEST['codCobro'];
$codPago	= $_REQUEST['codPago'];

$i = 0;
$sql_pro = " execute BF_DatosRegistraPago " . $codNegocio . ",'" . $codCobro . "'," . $codPago . ",'RPAG' ";
$query_pro  = $_SESSION['dbmssql']->getAll($sql_pro);
foreach ($query_pro as $pro => $descripcion) {
	$codigo		= $descripcion['CODIGO'];
	$fisico		= $descripcion['FISICO'];
	$doc		= $codigo . ' (' . $fisico . ')';
	$fecreg		= $descripcion['FECDOC'];
	$impconigv	= round($descripcion['MONTDOC'], 2);
	$impxcobrar	= round($descripcion['MONTCOB'], 2);
	$control	= $descripcion['CONTROL'];
	$tipmoneda	= $descripcion['TIPMON'];
	$codpag		= $descripcion['CODPAG'];
	$tipodoc	= $descripcion['doc'];

	$TIPODOCDETALLE	= $descripcion['TIPODOCDETALLE'];

	switch ($TIPODOCDETALLE) {
		case 'F': {
				$color = '#dee7ec';
				$label = '(FACTURA)';
				$caption = 'F:';
				break;
			};
		case 'P': {
				$color = '#dee7ec';
				$label = '(PROFORMA)';
				$caption = 'P:';
				break;
			};
		case 'C': {
				$color = '#CEF6CE';
				$label = '(N. DE CREDITO)';
				$caption = 'NC:';
				break;
			};
		case 'D': {
				$color = '#F5ECCE';
				$label = '(N. DE DEBITO)';
				$caption = 'ND:';
				break;
			};
		case 'H': {
				$color = '#CEF6CE';
				$label = '(N. DE CREDITO PROFORMA)';
				$caption = 'NC:';
				break;
			};
		case 'I': {
				$color = '#F5ECCE';
				$label = '(N. DE DEBITO PROFORMA)';
				$caption = 'ND:';
				break;
			};
	}

	$control_ing = $impxcobrar - $control;

	switch ($tipodoc) {
		case 'F': {
				$abrev = 'fac';
				break;
			};
		case 'P': {
				$abrev = 'prof';
				break;
			};
	}

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
	$od_servicio = $descripcion['CODSERVICIO'];

	$nro = $i + 1;
?>
	<div id="divreg<?= $i ?>" style="width:100%;">
		<input name="puntero" type="hidden" value="" id="<?= $i ?>" />
		<input name="estado" type="hidden" value="" id="estado<?= $i ?>" />
		<input name="coddetpag" type="hidden" value="<?= $codpag ?>" id="coddetpag<?= $i ?>" />
		<input name="ord_control" type="hidden" value="<?= $control ?>" id="ord_control<?= $i ?>" />
		<input name="ing_txt_moneda" type="hidden" value="<?= $tipmoneda ?>" id="ing_txt_moneda<?= $i ?>" />
		<input name="ing_txt_codigo" type="hidden" value="<?= $codigo ?>" id="ing_txt_codigo<?= $i ?>" />
		<input name="ing_txt_fechadoc" type="hidden" id="ing_txt_fechadoc<?= $i ?>" style="text-align:right;" value="<?= $fecreg ?>" size="10">
		<input name="cod_tipodoc_detalle" type="hidden" value="<?= $TIPODOCDETALLE ?>" id="cod_tipodoc_detalle<?= $i ?>" />

		<table width="100%" border="0" cellpadding="0" cellspacing="0" class="smalltext">
			<tr onMouseOver="color1(this,'<?= $color ?>');" onMouseOut="color2(this,'#ffffff');">
				<td width="3%" height="28" align="center" valign="middle"><?= (string)str_pad($nro, 3, '0', STR_PAD_LEFT); ?></td>
				<td width="10%" align="center" valign="middle"><?= $caption ?> <?= $doc ?></td>
				<td width="10%" align="center" valign="middle"><?= $fecreg ?></td>
				<td width="13%" align="center" valign="middle"><input readonly="readonly" name="ing_txt_total" type="text" id="ing_txt_total<?= $i ?>" style="text-align:right;" value="<?= $impconigv ?>" size="10"></td>
				<td width="19%" align="center" valign="middle"><input readonly="readonly" name="ing_txt_cobrar" onblur="calcular_cob('<?= $i ?>');" type="text" id="ing_txt_cobrar<?= $i ?>" style="text-align:right;" value="<?= $impxcobrar ?>" size="10"></td>
				<td width="22%" align="center" valign="middle">
					<input name="ing_control" type="text" id="ing_control<?= $i ?>" readonly="readonly" style="text-align:right;" value="<?= $control_ing ?>" size="10">
					<?= $moneda ?>
				</td>
				<td width="16%" align="center" valign="middle"><label>
						<input name="marca" type="checkbox" id="marca<?= $i ?>" class="smalltext" tabindex="-1" value="" onclick="ing_max_cant_regpago('<?= $i ?>',this.checked);" /></label>
					<input name="ing_recibido" type="text" class="smalltext" onfocus="select_input_regpago('<?= $i ?>')" onkeyup="numeros(this.id);control_ingreso_regpago('<?= $i ?>');" id="ing_recibido<?= $i ?>" value="0" style="text-align:right;" size="4" onblur="suma_ingreso_regpago(); control_saldofm('<?= $i ?>');">
				</td>
				<td width="7%" align="center" valign="middle"><?= $label ?></td>
			</tr>
			<tr>
				<td colspan="20" bgcolor="#C0D8E0" height="2"></td>
			</tr>
		</table>
		<div id="divreg<?= $i ?>" style="width:100%;"></div>
	<? $i++;
}
if ($i == 0) echo '<center><strong>NO HAY DETALLE A MOSTRAR O YA SE PAGO ESTA ORDEN DE PAGO</strong></center>';
	?>