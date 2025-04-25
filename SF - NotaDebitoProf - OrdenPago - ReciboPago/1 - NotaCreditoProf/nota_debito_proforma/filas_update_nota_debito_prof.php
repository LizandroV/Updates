<?php
session_start();
require('../../../includes/dbmssql_cfg.php');

$codigoObra = $_REQUEST['codigoObra'];
$codigoOrden = $_REQUEST['codigoOrden'];
$i = 0;

$sql_detalle = "SELECT	d.CodDetNotaDeb, d.Cantidad, d.MedCod, d.Glosa, d.Punitario, d.Monto 
				FROM DETNOTADEBITO_PROF d
				LEFT JOIN CABNOTADEBITO_PROF c on c.CodOrdNotaDeb=d.CodOrdNotaDeb and c.CodNotaProf = d.CodNotaProf and c.CodNotaNeg = d.CodNotaNeg
				LEFT JOIN MEDIDA me on me.MedCod=d.MedCod
                WHERE c.Estado='A' and d.Estado='A' and c.CodOrdNotaDeb='" . $codigoOrden . "' and c.CodNotaNeg='" . $codigoObra . "' 
                ORDER BY d.CodOrdNotaDeb asc ";
$dsl_detalle = $_SESSION['dbmssql']->getAll($sql_detalle);
foreach ($dsl_detalle as $v => $value) {
	$CodDetNotaDeb		= trim($value['CodDetNotaDeb']);
	$Cantidad			= trim($value['Cantidad']);
	$MedCod				= trim($value['MedCod']);
	$Glosa				= trim($value['Glosa']);
	$Punitario			= trim($value['Punitario']);
	$Monto				= trim($value['Monto']);

?>
	<div id="divreg<?= $CodDetNotaDeb ?>" style="width:100%;">
		<input name="puntero" type="hidden" value="<?= $CodDetNotaDeb ?>" id="<?= $CodDetNotaDeb ?>" />
		<table width="100%" border="0" cellpadding="0" cellspacing="0" class="smalltext">
			<tr onMouseOver="color1(this,'#dee7ec');" onMouseOut="color2(this,'#ffffff');">
				<td width="7%" height="28" align="center" valign="middle"><img src="images/b_deltbl.png" alt="Eliminar Registro" width="16" height="16" border="0" align="absmiddle" style="cursor:pointer;" /></td><!--onclick="eliminar_filas_updservicio('<?= $CodDetNotaDeb; ?>');"-->
				<td width="7%" align="center" valign="middle">
					<input name="txt_cantidad" onblur="calcular_nota_debito_prof('<?= $CodDetNotaDeb ?>');" onKeyUp="numeros(this.id)" type="text" class="smalltext" disabled size="4" id="txt_cantidad<?= $CodDetNotaDeb ?>" value="<?= $Cantidad ?>" />
				</td>
				<td width="9%" align="center" valign="middle">
					<select name="txt_und1" id="txt_und1<?= $CodDetNotaDeb ?>" disabled class="smalltext" style="width:50px;">
						<option value="0"></option>
						<?php
						$sql_niveles   = "select distinct MedCod, MedAbrev from medida where medest='A' order by MedAbrev";
						$dsl_niveles = $_SESSION['dbmssql']->getAll($sql_niveles);
						foreach ($dsl_niveles as $id_dept => $all) {
							$MedCod_look 	= trim($all['MedCod']);
							$MedAbrev_look	= rtrim($all['MedAbrev']);
							if ($MedCod_look == $MedCod) {
								$sel = 'selected';
							} else {
								$sel = '';
							}
							echo "<option " . $sel . " value='" . $MedCod_look . "'>" . $MedAbrev_look . "</option>";
						}
						?>
					</select>
				</td>
				<td width="51%" align="center" valign="middle">
					<input name="txt_descripcion" type="text" id="txt_descripcion<?= $CodDetNotaDeb ?>" disabled value="<?= $Glosa ?>" size="90">
					&nbsp;
				</td>
				<td width="13%" align="center" valign="middle">
					<input name="txt_punitario" type="text" id="txt_punitario<?= $CodDetNotaDeb ?>" disabled style="text-align:right;" value="<?= $Punitario ?>" size="4" onblur="calcular_nota_debito_prof('<?= $CodDetNotaDeb ?>');">
				</td>
				<td width="13%" align="center" valign="middle"><input name="txt_costo" type="text" class="smalltext" id="txt_costo<?= $CodDetNotaDeb ?>" style="text-align:right;" size="8" readonly="readonly" value="<?= $Monto ?>" /></td>
			</tr>
			<tr>
				<td colspan="16" bgcolor="#C0D8E0" height="2"></td>
			</tr>
		</table>
	</div>
<?
	$i++;
}
?>
<div id="divreg<?= $CodDetNotaDeb ?>" style="width:100%;"></div>