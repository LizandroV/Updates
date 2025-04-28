<?php
header('Content-Type: text/xml; charset=utf-8'); //ISO-8859-1
session_start();
require('../../../includes/dbmssql_cfg.php');

$codNegocio	= $_REQUEST['codNegocio'];
$codCobro	= $_REQUEST['codCobro'];
$codRegPago	= $_REQUEST['codRegPago'];
$codPago	= $_REQUEST['codPago'];
$i = 0;

$sql_TIPODOC = "SELECT	DISTINCT TIPODOC 
				FROM	DETORDPAGO 
				WHERE	CODORDPAG = '" . $codPago . "' AND
						CODPAGNEG = '" . $codNegocio . "' AND 
						TIPOCOB = '" . $codCobro . "'  AND 
						ESTADO NOT IN ('E','C')
				ORDER BY TIPODOC DESC ";
$dsl_TIPODOC = $_SESSION['dbmssql']->getAll($sql_TIPODOC);
foreach ($dsl_TIPODOC as $v => $inf) {
	/////Estasse haran segun el NUMERO de TIPODOC de la ORDPAGO ( del detalle)


	$TIPODOC = $inf['TIPODOC'];


	if ($TIPODOC == 'F'  or  $TIPODOC == 'P') {
		if ($TIPODOC == 'F') {
			$sigla = 'FAC';
			$tabla_cab = 'CABORDFAC';
			$campo = 'FACTURA';
		}
		if ($TIPODOC == 'P') {
			$sigla = 'PROF';
			$tabla_cab = 'CABORDPROF';
			$campo = 'PROFORMA';
		}

		$sql_detalle_ing = "	select	CODDOC as CODIGO,
										(	SELECT	" . $campo . " 
											FROM	" . $tabla_cab . " 
											WHERE	CODORD" . $sigla . " = CODDOC AND 
													COD" . $sigla . "NEG = '" . $codNegocio . "' AND 
													ESTADO NOT IN ('E','C')
										) AS FISICO,
										(	SELECT	TipMoneda 
											FROM	" . $tabla_cab . " 
											WHERE	CODORD" . $sigla . "=CODDOC AND 
													COD" . $sigla . "NEG='" . $codNegocio . "' AND 
													ESTADO NOT IN ('E','C')
										) AS TipMoneda,
										FECDOC, MONTDOC, CANTCOBR, IMPRECIB, CONTROL, CODORDPAG										
										,( 
											select 	n.tipodoc 
											from 	DETORDPAGO n 
											where 	n.CodOrdPag = '" . $codPago . "' and 
													n.CodPagNeg = '" . $codNegocio . "' and 
													n.CodDoc= d.CODDOC		
										) as TIPODETDOC								
										
								from	detregpago d
								where	CODORDPAG = '" . $codPago . "' AND 
										CODREGNEG = '" . $codNegocio . "' AND 
										CODREGPAG = '" . $codRegPago . "' AND
										TIPOCOB='" . $codCobro . "' AND
										ESTADO NOT IN ('E','C')	 AND
										( 
											select 	n.tipodoc 
											from 	DETORDPAGO n 
											where 	n.CodOrdPag = '" . $codPago . "' and 
													n.CodPagNeg = '" . $codNegocio . "' and 
													n.CodDoc= d.CODDOC		
										) = '$TIPODOC'	
								order by fecreg desc";
	}

	if ($TIPODOC == 'C' || $TIPODOC == 'D' || $TIPODOC == 'H' || $TIPODOC == 'I') {
		if ($TIPODOC == 'C') {
			$sigla = 'NOTACRE';
			$tabla_cab = 'CABNOTACREDITO';
			$campo = 'NOTACREDITO';
			$cab_doc = 'CABORDFAC';
			$cod_cab = 'CODORDFAC';
			$nota_doc = 'CODNOTAFAC';
			$nego = 'CODFACNEG';
		}

		if ($TIPODOC == 'D') {
			$sigla = 'NOTADEB';
			$tabla_cab = 'CABNOTADEBITO';
			$campo = 'NOTADEBITO';
			$cab_doc = 'CABORDFAC';
			$cod_cab = 'CODORDFAC';
			$nota_doc = 'CODNOTAFAC';
			$nego = 'CODFACNEG';
		}

		if ($TIPODOC == 'H') {
			$sigla = 'NOTACRE';
			$tabla_cab = 'CABNOTACREDITO_PROF';
			$campo = 'NOTACREDITO';
			$cab_doc = 'CABORDPROF';
			$cod_cab = 'CODORDPROF';
			$nota_doc = 'CODNOTAPROF';
			$nego = 'CODPROFNEG';
		}

		if ($TIPODOC == 'I') {
			$sigla = 'NOTADEB';
			$tabla_cab = 'CABNOTADEBITO_PROF';
			$campo = 'NOTADEBITO';
			$cab_doc = 'CABORDPROF';
			$cod_cab = 'CODORDPROF';
			$nota_doc = 'CODNOTAPROF';
			$nego = 'CODPROFNEG';
		}

		$sql_detalle_ing = "	select	CODDOC as CODIGO,
										(	SELECT	" . $campo . " 
											FROM	" . $tabla_cab . " 
											WHERE	CODORD" . $sigla . " = CODDOC AND 
													CODNOTANEG = '" . $codNegocio . "' AND 
													ESTADO NOT IN ('E','C')
										) AS FISICO,										
										(														
											SELECT 	TipMoneda 
											FROM 	" . $cab_doc . " 
											WHERE 	" . $cod_cab . " in ( 
																	SELECT 	" . $nota_doc . " 
																	FROM 	" . $tabla_cab . " 
																	WHERE 	CODORD" . $sigla . "=CODDOC AND 
																			CODNOTANEG='" . $codNegocio . "' AND 
																			ESTADO NOT IN ('E','C') 
													 			)	
													AND " . $nego . "='" . $codNegocio . "' AND 
													ESTADO NOT IN('E','C')
										) AS TipMoneda,
										FECDOC, MONTDOC, CANTCOBR, IMPRECIB, CONTROL, CODORDPAG										
										,( 
											select 	n.tipodoc 
											from 	DETORDPAGO n 
											where 	n.CodOrdPag = '" . $codPago . "' and 
													n.CodPagNeg = '" . $codNegocio . "' and 
													n.CodDoc= d.CODDOC		
										) as TIPODETDOC										
								from	detregpago d
								where	CODORDPAG = '" . $codPago . "' AND 
										CODREGNEG = '" . $codNegocio . "' AND 
										CODREGPAG = '" . $codRegPago . "' AND
										TIPOCOB='" . $codCobro . "' AND
										ESTADO NOT IN ('E','C')  AND
										( 
											select 	n.tipodoc 
											from 	DETORDPAGO n 
											where 	n.CodOrdPag = '" . $codPago . "' and 
													n.CodPagNeg = '" . $codNegocio . "' and 
													n.CodDoc= d.CODDOC		
										) = '$TIPODOC'
								order by fecreg desc";
	}
	$dsl_detalle_ing = $_SESSION['dbmssql']->getAll($sql_detalle_ing);
	foreach ($dsl_detalle_ing as $v => $descripcion) {
		$codigo		= $descripcion['CODIGO'];
		$fisico		= $descripcion['FISICO'];
		$doc		= $codigo . ' (' . $fisico . ')';
		$fecreg		= $descripcion['FECDOC'];
		$impconigv	= round($descripcion['MONTDOC'], 2);
		$impxcobrar	= round($descripcion['CANTCOBR'], 2);
		$control	= $descripcion['CONTROL'];
		$ingresado	= $descripcion['IMPRECIB'];
		$tipmoneda	= $descripcion['TipMoneda'];
		$codpag		= $descripcion['CODORDPAG'];
		$tipodetdoc	= $descripcion['TIPODETDOC'];

		switch ($tipodetdoc) {
			case 'C': {
					$label = '<strong>(N. CRED.)</strong>';
					$caption = 'NC:';
					break;
				}
			case 'D': {
					$label = '<strong>(N. DEB.)</strong>';
					$caption = 'ND:';
					break;
				}
			case 'F': {
					$label = '<strong>(FACTURA)</strong>';
					$caption = 'F:';
					break;
				}
			case 'P': {
					$label = '<strong>(PROFORMA)</strong>';
					$caption = 'P:';
					break;
				}
			case 'H': {
					$label = '<strong>(N. CRED. PROF.)</strong>';
					$caption = 'NC:';
					break;
				}
			case 'I': {
					$label = '<strong>(N. DEB. PROF.)</strong>';
					$caption = 'ND:';
					break;
				}
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

		$nro = $i + 1;
?>
		<div id="divreg<?= $i ?>" style="width:100%;">
			<input name="update" type="hidden" value="" id="update" />
			<input name="puntero" type="hidden" value="<?= $codigo ?>" id="<?= $i ?>" />
			<input name="coddetpag" type="hidden" value="<?= $codpag ?>" id="coddetpag<?= $i ?>" />
			<input name="ord_control" type="hidden" value="<?= $control ?>" id="ord_control<?= $i ?>" />
			<input name="estado" type="hidden" value="" id="estado<?= $i ?>" />
			<input name="ing_txt_moneda" type="hidden" value="<?= $tipmoneda ?>" id="ing_txt_moneda<?= $i ?>" />
			<input name="ing_txt_codigo" type="hidden" value="<?= $codigo ?>" id="ing_txt_codigo<?= $i ?>" />
			<input name="ing_txt_fechadoc" type="hidden" id="ing_txt_fechadoc<?= $i ?>" style="text-align:right;" value="<?= $fecreg ?>" size="10">
			<input name="cod_tipodoc_detalle" type="hidden" value="<?= $tipodetdoc ?>" id="cod_tipodoc_detalle<?= $i ?>" />
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="smalltext">
				<tr onMouseOver="color1(this,'#dee7ec');" onMouseOut="color2(this,'#ffffff');">
					<td width="3%" height="28" align="center" valign="middle"><?= (string)str_pad($nro, 3, '0', STR_PAD_LEFT); ?></td>
					<td width="10%" align="center" valign="middle"><?= $caption ?> <?= $doc ?></td>
					<td width="10%" align="center" valign="middle"><?= $fecreg ?></td>
					<td width="13%" align="center" valign="middle"><input readonly="readonly" name="ing_txt_total" type="text" id="ing_txt_total<?= $i ?>" style="text-align:right;" value="<?= $impconigv ?>" size="10" /></td>
					<td width="19%" align="center" valign="middle"><input readonly="readonly" name="ing_txt_cobrar" onblur="calcular_cob('<?= $i ?>');" type="text" id="ing_txt_cobrar<?= $i ?>" style="text-align:right;" value="<?= $impxcobrar ?>" size="10"></td>
					<td width="22%" align="center" valign="middle">
						<input name="ing_control" type="text" id="ing_control<?= $i ?>" readonly="readonly" style="text-align:right;" value="<?= $control ?>" size="10">
						<?= $moneda ?>
					</td>
					<td width="23%" align="center" valign="middle"><label>
							<!--<input name="marca" type="checkbox" id="marca< ?=$i?>" class="smalltext" tabindex="-1"  value="" onclick="ing_max_cant_regpago('< ?=$i?>',this.checked);"/>--></label>
						<input name="ing_recibido" type="text" class="smalltext" onfocus="select_input_regpago('<?= $i ?>')" onkeyup="numeros(this.id);control_ingreso_regpago('<?= $i ?>');" disabled value="<?= $ingresado ?>" id="ing_recibido<?= $i ?>" style="text-align:right;" size="4" onblur="suma_ingreso_regpago();">&nbsp;&nbsp;<?php echo $label; ?>
					</td>
				</tr>
				<tr>
					<td colspan="19" bgcolor="#C0D8E0" height="2"></td>
				</tr>
			</table>
		</div>
	<? $i++;
	} ?>

<?  }  ?>