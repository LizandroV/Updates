<?php
session_start();
require('../../../includes/dbmssql_cfg.php');
$i = $_REQUEST['contador'];
$block = "";
$neg = $_REQUEST['neg'];
if ($neg == '2') {
    $block = "disabled";
}
?>
<input name="puntero" type="hidden" value="" id="<?= $i ?>" />
<input name="txt_codproducto" type="hidden" value="" id="txt_codproducto<?= $i ?>" />
<input name="txt_subtipo" type="hidden" value="" id="txt_subtipo<?= $i ?>" />
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="smalltext">
    <tr onMouseOver="color1(this,'#dee7ec');" onMouseOut="color2(this,'#ffffff');">
        <td width="7%" height="28" align="center" valign="middle"><img src="images/b_deltbl.png" onclick="eliminar_filas_nd_prof('<?= $i; ?>');" alt="Eliminar Registro" width="16" height="16" border="0" align="absmiddle" style="cursor:pointer;" /></td>
        <td width="7%" align="center" valign="middle">
            <input name="txt_cantidad" onblur="calcular_nota_debito_prof('<?= $i ?>');" onKeyUp="numeros(this.id)" type="text" class="smalltext" size="4" id="txt_cantidad<?= $i ?>" />
        </td>
        <td width="9%" align="center" valign="middle">
            <select name="txt_und1" id="txt_und1<?= $i ?>" class="smalltext" style="width:60px;">
                <option value="0"></option>
                <?php
                $sql_niveles = "select distinct MedCod, MedAbrev from medida where medest='A' order by MedAbrev";
                $dsl_niveles = $_SESSION['dbmssql']->getAll($sql_niveles);
                foreach ($dsl_niveles as $id_dept => $all) {
                    $MedCod = trim($all['MedCod']);
                    $MedAbrev = rtrim($all['MedAbrev']);
                    echo "<option value='" . $MedCod . "'>" . $MedAbrev . "</option>";
                }
                ?>
            </select>
        </td>
        <td width="51%" align="center" valign="middle"><input name="txt_descripcion" type="text" id="txt_descripcion<?= $i ?>" size="90" /></td>
        <td width="13%" align="center" valign="middle">
            <input name="txt_punitario" type="text" class="smalltext" id="txt_punitario<?= $i ?>" style="text-align:right;" onblur="calcular_nota_debito_prof('<?= $i ?>');" size="4">
        </td>
        <td width="13%" align="center" valign="middle">
            <input name="txt_costo" type="text" class="smalltext" id="txt_costo<?= $i ?>" style="text-align:right;" value="0" size="8" readonly disabled>
        </td>
    </tr>
    <tr>
        <td colspan="16" bgcolor="#C0D8E0" height="2"></td>
    </tr>
</table>