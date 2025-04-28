<?php
session_start();
require('../../../includes/dbmssql_cfg.php');
$cod_los_documentos	= $_REQUEST['facts'];
$codNegocio		= $_REQUEST['codNegocio'];
$codEmpresa		= $_REQUEST['codEmpresa'];
$codCliente		= $_REQUEST['codCliente'];
$valorProceso	= $_REQUEST['valorProceso'];

/*DEBEMOS PROCESAR LOS ID DE LOS DOCUMENTOS DE LA VENTANITA.*/
// F_29, F_30, C_1, D_2, P_3, H_2

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

$i = 0;

if (strlen($txt_arreglo_fac) > 0  or strlen($txt_arreglo_pro) > 0) {
	if ($valorProceso == 'F') {
		$procedure = 'BF_ObtenerDatos_Fac';
		$abrev = 'fac';
		$codFacturas = $txt_arreglo_fac;
		$label = 'F:';
	}
	if ($valorProceso == 'P') {
		$procedure = 'BF_ObtenerDatos_Prof';
		$abrev = 'prof';
		$codFacturas = $txt_arreglo_pro;
		$label = 'P:';
	}

	///Este query sera solo para Facturas y Proformas.
	$sql_pro = " execute " . $procedure . " " . $codNegocio . ",
										" . $codEmpresa . ",
										" . $codCliente . ",
										'" . str_replace('-', ',', $codFacturas) . "' ";


	$query_pro  = $_SESSION['dbmssql']->getAll($sql_pro);
	foreach ($query_pro as $pro => $descripcion) {
		$codigo		= $descripcion['codigo'];
		$fisico		= $descripcion['fisico'];

		$doc		= $codigo . ' (' . $fisico . ')';

		$fecreg		= $descripcion['fecreg'];
		$impconigv	= round($descripcion['impconigv'], 2);
		$impxcobrar	= round($descripcion['impxcobrar'], 2);
		$detraccion	= round($descripcion['detraccion'], 2);

		$retencion	= round($descripcion['retencion'], 2);

		$tipmoneda	= $descripcion['tipmoneda'];
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

		$od_servicio = $descripcion['codservicio'];
		$cambio		 = $descripcion['cambio'];

		///////GUIA EXTERNA
		$sql_GuiaExt = "	select	cs.GuiaExt
						from	cabord" . $abrev . " cf, cabordserv cs
						where	cf.cod" . $abrev . "serv=cs.codordserv and 
								cf.Cod" . $abrev . "Neg=cs.CodServNeg and 
								cf.codord" . $abrev . "='" . $codigo . "' 
								and cf.cod" . $abrev . "neg='" . $codNegocio . "'  
								and cf.estado not in ('E','C')
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

		if ($valorProceso == 'F') {
			$disabled = '';
			if ($detraccion > 0)
				//////////$disabled='disabled';
				//1ero Mayo,2018 | Ahora se dejará activo el check pero habilitado el CheckBox.	Tambien en Generar.JS			
				$disabled = 'checked="checked"';
		}
		if ($valorProceso == 'P') {
			$disabled = 'disabled';
			///Julio 25 2016  para proforma no deberia haber retencion
			$disabled_ret = 'disabled';
		}

		/*AGO,2015  VENTA GRAL.  NO DETRAC NI RETEN       ------->>>>>FEB2021  VTAS DE IMPORTA se agregó   */
		$disabled_ret = '';
		if ($codNegocio		== 5   or   $codNegocio		== 9) {
			$disabled = 'disabled';
			$disabled_ret = 'disabled';
		}

		//////////SEptiembre 2016  : plano y tintorieria   PROFORMA, no detrac ni reten
		if (($codNegocio  == 3 && $valorProceso == 'P')   	or    ($codNegocio	== 4 && $valorProceso == 'P')) {
			$disabled = 'disabled';
			$disabled_ret = 'disabled';
		}

		////////Julio 2019 Stefany Habiliat VENTAS GRAL. Y FACTURA
		////////Febrero 2021  Habiliat VENTAS GRAL. IMPORTACION  Y FACTURA
		if (($codNegocio  == 5 && $valorProceso == 'F')  or		($codNegocio  == 9 && $valorProceso == 'F')) {
			$disabled_ret = '';
		}

		////// ABRIL 2021,SE ACORDO QUE LOS MISMOS USUARIO 
		////// ACTIVARIAN/DESACTIVARIAN LA DETRACCION y/o RETENCION AUTOMATICA
		$disabled = '';
		$disabled_ret = '';

?>
		<div id="divreg<?= $i ?>" style="width:100%;">
			<input name="cob" type="hidden" value="<?= $impxcobrar ?>" id="cob<?= $i ?>" />
			<input name="valor_proceso" type="hidden" value="<?= $valorProceso ?>" id="valor_proceso<?= $i ?>" />
			<input name="ret" type="hidden" value="<?= $retencion ?>" id="ret<?= $i ?>" />
			<input name="det<?= $i ?>" type="hidden" value="<?= $detraccion ?>" id="det<?= $i ?>" />
			<input name="cambio" type="hidden" value="<?= $cambio ?>" id="cambio<?= $i ?>" />
			<input name="puntero" type="hidden" value="" id="<?= $i ?>" />
			<input name="txt_moneda" type="hidden" value="<?= $tipmoneda ?>" id="txt_moneda<?= $i ?>" />
			<input name="txt_codigo" type="hidden" value="<?= $codigo ?>" id="txt_codigo<?= $i ?>" />
			<input name="txt_fechadoc" type="hidden" id="txt_fechadoc<?= $i ?>" style="text-align:right;" value="<?= $fecreg ?>" size="10">
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="smalltext">
				<tr onmouseover="color1(this,'#dee7ec');" onmouseout="color2(this,'#ffffff');">
					<td width="3%" height="28" align="center" valign="middle"><img src="images/b_deltbl.png" onclick="eliminar_filas_opago('<?= $i; ?>');" alt="Eliminar Registro" width="16" height="16" border="0" align="absmiddle" style="cursor:pointer;" /></td>
					<td width="9%" align="center" valign="middle"><?= $label ?> <?= $doc ?></td>
					<td width="9%" align="center" valign="middle"><?= $fecreg ?></td>
					<td width="17%" align="center" valign="middle"><input readonly="readonly" name="txt_total" type="text" id="txt_total<?= $i ?>" style="text-align:right;" value="<?= $impconigv ?>" size="10" /><label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="checkbox" <?= $disabled ?> id="checkbox<?= $i ?>" onchange="aplica('<?= $i ?>')" /></label>&nbsp;&nbsp;&nbsp;&nbsp;<label><input <?= $disabled_ret ?> type="checkbox" name="checkbox_ret" id="checkbox_ret<?= $i ?>" onchange="aplica_ret('<?= $i ?>')" /></label></td>
					<td width="17%" align="center" valign="middle"><input disabled="disabled" name="txt_cobrar" onblur="calcular_cob('<?= $i ?>');" type="text" id="txt_cobrar<?= $i ?>" style="text-align:right;" value="<?= $impxcobrar ?>" size="10" /></td>
					<td width="8%" align="center" valign="middle"><input name="txt_detrac" disabled="disabled" type="text" id="txt_detrac<?= $i ?>" onblur="calcular_det('<?= $i ?>');" style="text-align:right;" value="<?= $detraccion ?>" size="10" /></td>
					<td width="8%" align="center" valign="middle"><input name="txt_reten" disabled="disabled" type="text" id="txt_reten<?= $i ?>" onblur="calcular_ret('<?= $i ?>');" style="text-align:right;" value="<?= $retencion ?>" size="10" /></td>
					<td width="9%" align="center" valign="middle"><label><?= $moneda ?></label></td>
					<td width="9%" align="center" valign="middle"><?= $GuiaExt ?></td>
					<td width="11%" align="center" valign="middle"><?= $GUIAS_DESPACHOS ?></td>
				</tr>
				<tr>
					<td colspan="20" bgcolor="#C0D8E0" height="2"></td>
				</tr>
			</table>
		</div>
<?php

		$i++;
	}
}
?>



<?php

///Parametros para las filas de NOTA de CREDITO 
if (strlen($txt_arreglo_cre) > 0) {
	$tipodoc_Proceso = 'C';
	$procedure = 'BF_ObtenerDatos_Cred';
	$codNotasCredito = $txt_arreglo_cre;
	$label = 'NC:';

	///Este query sera solo para Notas de credito
	$sql_pro = " execute BF_ObtenerDatos_Cre 	" . $codNegocio . ",
												" . $codEmpresa . ",
												" . $codCliente . ",
												'" . str_replace('-', ',', $codNotasCredito) . "' ";


	$query_pro  = $_SESSION['dbmssql']->getAll($sql_pro);
	foreach ($query_pro as $pro => $descripcion) {


		$codigo		= $descripcion['codigo'];
		$fisico		= $descripcion['fisico'];

		$doc		= $codigo . ' (' . $fisico . ')';

		$fecreg		= $descripcion['fecreg'];
		$impconigv	= round($descripcion['impconigv'], 2);
		$impxcobrar	= round($descripcion['impxcobrar'], 2);
		$detraccion	= round($descripcion['detraccion'], 2);

		$retencion	= round($descripcion['retencion'], 2);

		$tipmoneda	= $descripcion['tipmoneda'];
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

		$od_servicio = $descripcion['codservicio'];
		$cambio		 = $descripcion['cambio'];


		// Si es NOTA de CREDITO.  se devuelven BLOQUEADOS
		//13/07/2023 cambio solicitado por augusto- notas de credito seran hbailitadas para detraccion
		//$disabled = 'disabled';		
		$disabled = '';
		//
		//$disabled_ret = 'disabled';
		$disabled_ret = '';


?>
		<div id="divreg<?= $i ?>" style="width:100%;">
			<input name="cob" type="hidden" value="<?= $impxcobrar ?>" id="cob<?= $i ?>" />
			<input name="valor_proceso" type="hidden" value="<?= $tipodoc_Proceso ?>" id="valor_proceso<?= $i ?>" />
			<input name="det" type="hidden" value="<?= $detraccion ?>" id="det<?= $i ?>" />
			<input name="ret" type="hidden" value="<?= $retencion ?>" id="ret<?= $i ?>" />
			<input name="cambio" type="hidden" value="<?= $cambio ?>" id="cambio<?= $i ?>" />
			<input name="puntero" type="hidden" value="" id="<?= $i ?>" />
			<input name="txt_moneda" type="hidden" value="<?= $tipmoneda ?>" id="txt_moneda<?= $i ?>" />
			<input name="txt_codigo" type="hidden" value="<?= $codigo ?>" id="txt_codigo<?= $i ?>" />
			<input name="txt_fechadoc" type="hidden" id="txt_fechadoc<?= $i ?>" style="text-align:right;" value="<?= $fecreg ?>" size="10">
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="smalltext">
				<tr onmouseover="color1(this,'#CEF6CE');" onmouseout="color2(this,'#ffffff');">
					<td width="3%" height="28" align="center" valign="middle"><img src="images/b_deltbl.png" onclick="eliminar_filas_opago('<?= $i; ?>');" alt="Eliminar Registro" width="16" height="16" border="0" align="absmiddle" style="cursor:pointer;" /></td>
					<td width="9%" align="center" valign="middle"><?= $label ?> <?= $doc ?></td>
					<td width="9%" align="center" valign="middle"><?= $fecreg ?></td>
					<td width="17%" align="center" valign="middle"><input readonly="readonly" name="txt_total" type="text" id="txt_total<?= $i ?>" style="text-align:right;" value="<?= $impconigv ?>" size="10" /><label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="checkbox" <?= $disabled ?> id="checkbox<?= $i ?>" onchange="aplica('<?= $i ?>')" /></label>&nbsp;&nbsp;&nbsp;&nbsp;<label><input <?= $disabled_ret ?> type="checkbox" name="checkbox_ret" id="checkbox_ret<?= $i ?>" onchange="aplica_ret('<?= $i ?>')" /></label></td>
					<td width="17%" align="center" valign="middle"><input disabled="disabled" name="txt_cobrar" onblur="calcular_cob('<?= $i ?>');" type="text" id="txt_cobrar<?= $i ?>" style="text-align:right;" value="<?= $impxcobrar ?>" size="10" /></td>
					<td width="8%" align="center" valign="middle"><input name="txt_detrac" disabled="disabled" type="text" id="txt_detrac<?= $i ?>" onblur="calcular_det('<?= $i ?>');" style="text-align:right;" value="<?= $detraccion ?>" size="10" /></td>
					<td width="8%" align="center" valign="middle"><input name="txt_reten" disabled="disabled" type="text" id="txt_reten<?= $i ?>" onblur="calcular_ret('<?= $i ?>');" style="text-align:right;" value="<?= $retencion ?>" size="10" /></td>
					<td width="9%" align="center" valign="middle"><label><?= $moneda ?></label></td>
					<td width="9%" align="center" valign="middle"><strong>NOTA</strong></td>
					<td width="11%" align="center" valign="middle"><strong>CREDITO</strong></td>
				</tr>
				<tr>
					<td colspan="20" bgcolor="#C0D8E0" height="2"></td>
				</tr>
			</table>
		</div>

<?php

		$i++;
	}
}
?>


<?php

///Parametros para las filas de NOTA de debito 
if (strlen($txt_arreglo_deb) > 0) {
	$tipodoc_Proceso = 'D';
	$procedure = 'BF_ObtenerDatos_Debi';
	$codNotasDebito = $txt_arreglo_deb;
	$label = 'ND:';

	///Este query sera solo para Notas de credito
	$sql_pro = " execute BF_ObtenerDatos_Debi 	" . $codNegocio . ",
												" . $codEmpresa . ",
												" . $codCliente . ",
												'" . str_replace('-', ',', $codNotasDebito) . "' ";
	$query_pro  = $_SESSION['dbmssql']->getAll($sql_pro);
	foreach ($query_pro as $pro => $descripcion) {
		$codigo		= $descripcion['codigo'];
		$fisico		= $descripcion['fisico'];

		$doc		= $codigo . ' (' . $fisico . ')';

		$fecreg		= $descripcion['fecreg'];
		$impconigv	= round($descripcion['impconigv'], 2);
		$impxcobrar	= round($descripcion['impxcobrar'], 2);
		$detraccion	= round($descripcion['detraccion'], 2);

		$retencion	= round($descripcion['retencion'], 2);

		$tipmoneda	= $descripcion['tipmoneda'];
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

		$od_servicio = $descripcion['codservicio'];
		$cambio		 = $descripcion['cambio'];

		// si es retencion para DEBITO   ACTIVADOS
		//
		$disabled = '';	///disabled	
		//
		$disabled_ret = ''; ///disabled

		/*aplica_solo_debito()   era antes aplica() se cambio para solo debito, Junio 2016
		Este cambio afecta a a filas_update_opagos.php la funcion del html se setea con php, dependiendo
		del Proceso( fact, cred o Debi)
		*/

?>
		<div id="divreg<?= $i ?>" style="width:100%;">
			<input name="cob" type="hidden" value="<?= $impxcobrar ?>" id="cob<?= $i ?>" />
			<input name="valor_proceso" type="hidden" value="<?= $tipodoc_Proceso ?>" id="valor_proceso<?= $i ?>" />
			<input name="det" type="hidden" value="<?= $detraccion ?>" id="det<?= $i ?>" />
			<input name="ret" type="hidden" value="<?= $retencion ?>" id="ret<?= $i ?>" />
			<input name="cambio" type="hidden" value="<?= $cambio ?>" id="cambio<?= $i ?>" />
			<input name="puntero" type="hidden" value="" id="<?= $i ?>" />
			<input name="txt_moneda" type="hidden" value="<?= $tipmoneda ?>" id="txt_moneda<?= $i ?>" />
			<input name="txt_codigo" type="hidden" value="<?= $codigo ?>" id="txt_codigo<?= $i ?>" />
			<input name="txt_fechadoc" type="hidden" id="txt_fechadoc<?= $i ?>" style="text-align:right;" value="<?= $fecreg ?>" size="10">
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="smalltext">
				<tr onmouseover="color1(this,'#F5ECCE');" onmouseout="color2(this,'#ffffff');">
					<td width="3%" height="28" align="center" valign="middle"><img src="images/b_deltbl.png" onclick="eliminar_filas_opago('<?= $i; ?>');" alt="Eliminar Registro" width="16" height="16" border="0" align="absmiddle" style="cursor:pointer;" /></td>
					<td width="9%" align="center" valign="middle"><?= $label ?> <?= $doc ?></td>
					<td width="9%" align="center" valign="middle"><?= $fecreg ?></td>
					<td width="17%" align="center" valign="middle"><input readonly="readonly" name="txt_total" type="text" id="txt_total<?= $i ?>" style="text-align:right;" value="<?= $impconigv ?>" size="10" /><label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="checkbox" <?= $disabled ?> id="checkbox<?= $i ?>" onchange="aplica_solo_debito('<?= $i ?>')" /></label>&nbsp;&nbsp;&nbsp;&nbsp;<label><input <?= $disabled_ret ?> type="checkbox" name="checkbox_ret" id="checkbox_ret<?= $i ?>" onchange="aplica_ret('<?= $i ?>')" /></label></td>
					<td width="17%" align="center" valign="middle"><input disabled="disabled" name="txt_cobrar" onblur="calcular_cob('<?= $i ?>');" type="text" id="txt_cobrar<?= $i ?>" style="text-align:right;" value="<?= $impxcobrar ?>" size="10" /></td>
					<td width="8%" align="center" valign="middle"><input name="txt_detrac" disabled="disabled" type="text" id="txt_detrac<?= $i ?>" onblur="calcular_det('<?= $i ?>');" style="text-align:right;" value="<?= $detraccion ?>" size="10" /></td>
					<td width="8%" align="center" valign="middle"><input name="txt_reten" disabled="disabled" type="text" id="txt_reten<?= $i ?>" onblur="calcular_ret('<?= $i ?>');" style="text-align:right;" value="<?= $retencion ?>" size="10" /></td>
					<td width="9%" align="center" valign="middle"><label><?= $moneda ?></label></td>
					<td width="9%" align="center" valign="middle"><strong>NOTA</strong></td>
					<td width="11%" align="center" valign="middle"><strong>DEBITO</strong></td>
				</tr>
				<tr>
					<td colspan="20" bgcolor="#C0D8E0" height="2"></td>
				</tr>
			</table>
		</div>

<?php

		$i++;
	}
}

?>


<?php

///Parametros para las filas de NOTA DE CREDITO DE PROFORMA
if (strlen($txt_arreglo_crep) > 0) {
	$tipodoc_Proceso = 'H';
	$codNotasCredito = $txt_arreglo_crep;
	$label = 'NC:';

	///Este query sera solo para Notas de credito
	$sql_pro = " execute BF_ObtenerDatos_Cre_Prof " . $codNegocio . ",
											" . $codEmpresa . ",
											" . $codCliente . ",
											'" . str_replace('-', ',', $codNotasCredito) . "' ";
	$query_pro  = $_SESSION['dbmssql']->getAll($sql_pro);
	foreach ($query_pro as $pro => $descripcion) {
		$codigo		= $descripcion['codigo'];
		$fisico		= $descripcion['fisico'];
		$doc		= $codigo . ' (' . $fisico . ')';
		$fecreg		= $descripcion['fecreg'];
		$impconigv	= round($descripcion['impconigv'], 2);
		$impxcobrar	= round($descripcion['impxcobrar'], 2);
		$detraccion	= round($descripcion['detraccion'], 2);
		$retencion	= round($descripcion['retencion'], 2);
		$tipmoneda	= $descripcion['tipmoneda'];

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

		$od_servicio = $descripcion['codservicio'];
		$cambio		 = $descripcion['cambio'];

		// Si es NOTA de CREDITO.  se devuelven BLOQUEADOS
		//13/07/2023 cambio solicitado por augusto- notas de credito seran hbailitadas para detraccion
		//$disabled = 'disabled';		
		$disabled = '';
		//
		//$disabled_ret = 'disabled';
		$disabled_ret = '';
?>
		<div id="divreg<?= $i ?>" style="width:100%;">
			<input name="cob" type="hidden" value="<?= $impxcobrar ?>" id="cob<?= $i ?>" />
			<input name="valor_proceso" type="hidden" value="<?= $tipodoc_Proceso ?>" id="valor_proceso<?= $i ?>" />
			<input name="det" type="hidden" value="<?= $detraccion ?>" id="det<?= $i ?>" />
			<input name="ret" type="hidden" value="<?= $retencion ?>" id="ret<?= $i ?>" />
			<input name="cambio" type="hidden" value="<?= $cambio ?>" id="cambio<?= $i ?>" />
			<input name="puntero" type="hidden" value="" id="<?= $i ?>" />
			<input name="txt_moneda" type="hidden" value="<?= $tipmoneda ?>" id="txt_moneda<?= $i ?>" />
			<input name="txt_codigo" type="hidden" value="<?= $codigo ?>" id="txt_codigo<?= $i ?>" />
			<input name="txt_fechadoc" type="hidden" id="txt_fechadoc<?= $i ?>" style="text-align:right;" value="<?= $fecreg ?>" size="10">
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="smalltext">
				<tr onmouseover="color1(this,'#CEF6CE');" onmouseout="color2(this,'#ffffff');">
					<td width="3%" height="28" align="center" valign="middle"><img src="images/b_deltbl.png" onclick="eliminar_filas_opago('<?= $i; ?>');" alt="Eliminar Registro" width="16" height="16" border="0" align="absmiddle" style="cursor:pointer;" /></td>
					<td width="9%" align="center" valign="middle"><?= $label ?> <?= $doc ?></td>
					<td width="9%" align="center" valign="middle"><?= $fecreg ?></td>
					<td width="17%" align="center" valign="middle"><input readonly="readonly" name="txt_total" type="text" id="txt_total<?= $i ?>" style="text-align:right;" value="<?= $impconigv ?>" size="10" /><label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="checkbox" <?= $disabled ?> id="checkbox<?= $i ?>" onchange="aplica('<?= $i ?>')" /></label>&nbsp;&nbsp;&nbsp;&nbsp;<label><input <?= $disabled_ret ?> type="checkbox" name="checkbox_ret" id="checkbox_ret<?= $i ?>" onchange="aplica_ret('<?= $i ?>')" /></label></td>
					<td width="17%" align="center" valign="middle"><input disabled="disabled" name="txt_cobrar" onblur="calcular_cob('<?= $i ?>');" type="text" id="txt_cobrar<?= $i ?>" style="text-align:right;" value="<?= $impxcobrar ?>" size="10" /></td>
					<td width="8%" align="center" valign="middle"><input name="txt_detrac" disabled="disabled" type="text" id="txt_detrac<?= $i ?>" onblur="calcular_det('<?= $i ?>');" style="text-align:right;" value="<?= $detraccion ?>" size="10" /></td>
					<td width="8%" align="center" valign="middle"><input name="txt_reten" disabled="disabled" type="text" id="txt_reten<?= $i ?>" onblur="calcular_ret('<?= $i ?>');" style="text-align:right;" value="<?= $retencion ?>" size="10" /></td>
					<td width="9%" align="center" valign="middle"><label><?= $moneda ?></label></td>
					<td width="9%" align="center" valign="middle"><strong>NOTA</strong></td>
					<td width="11%" align="center" valign="middle"><strong>CREDITO PROFORMA</strong></td>
				</tr>
				<tr>
					<td colspan="20" bgcolor="#C0D8E0" height="2"></td>
				</tr>
			</table>
		</div>
<?php

		$i++;
	}
}
?>


<?php

///Parametros para las filas de NOTA DE DEBITO DE PROFORMA
if (strlen($txt_arreglo_debp) > 0) {
	$tipodoc_Proceso = 'I';
	$codNotasDebito = $txt_arreglo_debp;
	$label = 'ND:';

	///Este query sera solo para Notas de credito
	$sql_pro = " execute BF_ObtenerDatos_Deb_Prof " . $codNegocio . ",
											" . $codEmpresa . ",
											" . $codCliente . ",
											'" . str_replace('-', ',', $codNotasDebito) . "' ";
	$query_pro  = $_SESSION['dbmssql']->getAll($sql_pro);
	foreach ($query_pro as $pro => $descripcion) {
		$codigo		= $descripcion['codigo'];
		$fisico		= $descripcion['fisico'];
		$doc		= $codigo . ' (' . $fisico . ')';
		$fecreg		= $descripcion['fecreg'];
		$impconigv	= round($descripcion['impconigv'], 2);
		$impxcobrar	= round($descripcion['impxcobrar'], 2);
		$detraccion	= round($descripcion['detraccion'], 2);
		$retencion	= round($descripcion['retencion'], 2);
		$tipmoneda	= $descripcion['tipmoneda'];

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

		$od_servicio = $descripcion['codservicio'];
		$cambio		 = $descripcion['cambio'];

		// Si es NOTA de CREDITO.  se devuelven BLOQUEADOS
		//13/07/2023 cambio solicitado por augusto- notas de credito seran hbailitadas para detraccion
		//$disabled = 'disabled';		
		$disabled = '';
		//
		//$disabled_ret = 'disabled';
		$disabled_ret = '';
?>
		<div id="divreg<?= $i ?>" style="width:100%;">
			<input name="cob" type="hidden" value="<?= $impxcobrar ?>" id="cob<?= $i ?>" />
			<input name="valor_proceso" type="hidden" value="<?= $tipodoc_Proceso ?>" id="valor_proceso<?= $i ?>" />
			<input name="det" type="hidden" value="<?= $detraccion ?>" id="det<?= $i ?>" />
			<input name="ret" type="hidden" value="<?= $retencion ?>" id="ret<?= $i ?>" />
			<input name="cambio" type="hidden" value="<?= $cambio ?>" id="cambio<?= $i ?>" />
			<input name="puntero" type="hidden" value="" id="<?= $i ?>" />
			<input name="txt_moneda" type="hidden" value="<?= $tipmoneda ?>" id="txt_moneda<?= $i ?>" />
			<input name="txt_codigo" type="hidden" value="<?= $codigo ?>" id="txt_codigo<?= $i ?>" />
			<input name="txt_fechadoc" type="hidden" id="txt_fechadoc<?= $i ?>" style="text-align:right;" value="<?= $fecreg ?>" size="10">
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="smalltext">
				<tr onmouseover="color1(this,'#CEF6CE');" onmouseout="color2(this,'#ffffff');">
					<td width="3%" height="28" align="center" valign="middle"><img src="images/b_deltbl.png" onclick="eliminar_filas_opago('<?= $i; ?>');" alt="Eliminar Registro" width="16" height="16" border="0" align="absmiddle" style="cursor:pointer;" /></td>
					<td width="9%" align="center" valign="middle"><?= $label ?> <?= $doc ?></td>
					<td width="9%" align="center" valign="middle"><?= $fecreg ?></td>
					<td width="17%" align="center" valign="middle"><input readonly="readonly" name="txt_total" type="text" id="txt_total<?= $i ?>" style="text-align:right;" value="<?= $impconigv ?>" size="10" /><label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="checkbox" <?= $disabled ?> id="checkbox<?= $i ?>" onchange="aplica('<?= $i ?>')" /></label>&nbsp;&nbsp;&nbsp;&nbsp;<label><input <?= $disabled_ret ?> type="checkbox" name="checkbox_ret" id="checkbox_ret<?= $i ?>" onchange="aplica_ret('<?= $i ?>')" /></label></td>
					<td width="17%" align="center" valign="middle"><input disabled="disabled" name="txt_cobrar" onblur="calcular_cob('<?= $i ?>');" type="text" id="txt_cobrar<?= $i ?>" style="text-align:right;" value="<?= $impxcobrar ?>" size="10" /></td>
					<td width="8%" align="center" valign="middle"><input name="txt_detrac" disabled="disabled" type="text" id="txt_detrac<?= $i ?>" onblur="calcular_det('<?= $i ?>');" style="text-align:right;" value="<?= $detraccion ?>" size="10" /></td>
					<td width="8%" align="center" valign="middle"><input name="txt_reten" disabled="disabled" type="text" id="txt_reten<?= $i ?>" onblur="calcular_ret('<?= $i ?>');" style="text-align:right;" value="<?= $retencion ?>" size="10" /></td>
					<td width="9%" align="center" valign="middle"><label><?= $moneda ?></label></td>
					<td width="9%" align="center" valign="middle"><strong>NOTA</strong></td>
					<td width="11%" align="center" valign="middle"><strong>DEBITO PROFORMA</strong></td>
				</tr>
				<tr>
					<td colspan="20" bgcolor="#C0D8E0" height="2"></td>
				</tr>
			</table>
		</div>
<?php

		$i++;
	}
}
?>