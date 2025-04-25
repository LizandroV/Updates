<?php
//header('Content-Type: text/xml; charset=ISO-8859-1');
session_start();
require('../../../includes/dbmssql_cfg.php');

if ($_REQUEST['genera_nota_debito']) {
    $codigoNegocio        = $_REQUEST['codigoObra'];
    $codigoOrdenServicio = $_REQUEST['codigoOrden'];

    $sql_NuevoDescto = "select max(CodOrdNotaDeb)as codUltimo 
			from CABNOTADEBITO_PROF 
			where CodNotaNeg='" . $codigoNegocio . "' ";
    $dsl_NuevoDescto = $_SESSION['dbmssql']->getAll($sql_NuevoDescto);
    foreach ($dsl_NuevoDescto as $val => $RegDscto) {
        $resultado = $RegDscto['codUltimo'];
    }

    if (is_null($resultado))
        $NewCodigoDet = '1';
    else
        $NewCodigoDet = $resultado + 1;

    echo $NewCodigoDet;
}

if ($_REQUEST['buscarorden']) {
    $codigoNegocio = $_REQUEST['codigoNegocio'];
    $cmbus_anio = $_REQUEST['cmbus_anio'];
    $cmbus_mes = $_REQUEST['cmbus_mes'];
    $cadena = "";
    if ($codigoNegocio != '0') {
        $sql_ordenes = "select c.CodOrdNotaDeb, '-'+o.negcne as Codif
						from CABNOTADEBITO_PROF c, negocio o
						where c.estado in('A') and c.codnotaneg=o.negcod and 
						c.codnotaneg='" . $codigoNegocio . "' and
						year(c.fecreg)=" . $cmbus_anio . " and
						month(c.fecreg)=" . $cmbus_mes . " 
						order by 1";

        // echo $sql_ordenes;
        $dsl_ver_orden = $_SESSION['dbmssql']->getAll($sql_ordenes);
        echo "<select name=\"cmbordenes\" id=\"cmbordenes\" onChange=\"activar_verorden_nd_prof(this.value)\" class=\"smalltext\">";
        echo "<option value=\"0\">-----------------------------------------------------</option>";
        foreach ($dsl_ver_orden  as $val => $value) {
            $codorden     = trim($value['CodOrdNotaDeb']);
            $codificacion = rtrim($value['Codif']);
            $codificacion = str_replace("�", "&ordm", $codificacion);
            $siglas         = "N.D. N&ordm;&nbsp;";
            $serieOrden     = $siglas . (string)str_pad($codorden, 7, '0', STR_PAD_LEFT) . $codificacion;
            echo "<option value='" . $codorden . "'>" . $serieOrden . "</option>";
        }
        echo "</select>";
    } else {
        echo "<select name=\"cmbordenes\" id=\"cmbordenes\"  style=\"width:200px;\">";
        echo "<option value=\"0\">-------------------------------------------------------</option>";
        echo "</select>";
    }
}
