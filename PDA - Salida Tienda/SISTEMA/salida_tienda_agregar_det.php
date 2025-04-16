<?php
session_start();
include "config.php";
$bd = "DESARROLLO";

$codbarras = trim($_REQUEST['codbarras']);
$codalmacen_origen = trim($_REQUEST['codalmacen_origen']);
$codemp_origen = trim($_REQUEST['codemp_origen']);
$correlativo = trim($_REQUEST['correlativo']);
$j = $correlativo;
$valida_items = 0;
$tOrden = 'V';

$sql_emp = "SELECT ct.codemp 
            FROM $bd.alm.det_ingresos_tienda dt 
            INNER JOIN $bd.alm.cab_ingresos_tienda ct ON dt.coding_tda = ct.coding_tda 
            WHERE ct.codalmacen = '$codalmacen_origen' 
            AND ct.codemp = '$codemp_origen' 
            AND dt.orden = '$tOrden' 
            AND dt.correl = '$codbarras' 
            AND dt.estado <> 'C' 
            AND ct.estado <> 'C' 
            AND dt.liqacab = '0'";
// echo $sql_emp;
// echo "<br>---------<br>";

$dsl_emp = db_fetch_all($sql_emp);

if (count($dsl_emp) > 0) {
  $emp_elegida = trim($dsl_emp[0]['codemp']);
} else {
  $emp_elegida = 0;
}

if ($codemp_origen > 0) {
  if ($codemp_origen <> $emp_elegida) {
    echo "El Código ingresado no pertenece al origen";
    exit;
  }
}

$sql_valida = "SELECT COUNT(dt.correl) AS cant
    FROM $bd.alm.det_ingresos_tienda dt
    INNER JOIN $bd.alm.cab_ingresos_tienda ct ON dt.coding_tda = ct.coding_tda
    WHERE ct.codalmacen = $codalmacen_origen
        AND ct.codemp = $codemp_origen
        AND dt.orden = '$tOrden'
        AND dt.correl = '$codbarras'
        AND dt.estado <> 'C'
        AND ct.estado <> 'C'
        AND dt.liqacab = '0'";

// echo $sql_valida;
// echo "<br>---------<br>";
$dsl_valida = db_fetch_all($sql_valida);

if (trim($dsl_valida[0]['cant']) == '0') {
  echo "El código de barra $codbarras no existe o ya fue usado en otra salida.";
  exit;
}

$sql_items = "SELECT di.voucher, di.descrip, di.descolor, di.correl, 
	di.cdgart, di.cdgcolor, di.grem as grem1, di.proceso as procesos,
	di.liqacab, di.numordped, di.orden, di.numot, di.coding_tda,  di.coddet_ingtda, 
	ci.codemp, ci.codalmacen, 
	
	sum(1) as cant_rollos, 
		
	(sum(di.kneto_recibido) - 

	isnull((select sum(isnull(dp.kneto,0)) from $bd.DES.PLIST_DET_TIENDA dp  
          inner join $bd.des.PLIST_CAB_TIENDA cp on dp.CodPL=cp.CodPL
          where dp.voucher=di.voucher and dp.cdgcolor=di.cdgcolor and dp.cdgart=di.cdgart  
          and dp.correl=di.correl 
          and cp.codalmacen_origen=ci.codalmacen and cp.codemp_origen=ci.codemp 
          and dp.coding_tda=di.coding_tda and dp.coddet_ingtda=di.coddet_ingtda 
          and cp.EstadoGeneral<>'1'),0) - 

          isnull((select sum(isnull(dst.kneto_salida,0)) from $bd.alm.det_salidas_tienda dst  
          inner join $bd.alm.cab_salidas_tienda cst on dst.codsal_tienda=cst.codsal_tienda 
          where dst.voucher=di.voucher and dst.cdgcolor=di.cdgcolor and dst.cdgart=di.cdgart 
          and dst.correl=di.correl 
          and cst.codalmacen_origen=ci.codalmacen and cst.codemp_origen=ci.codemp 
          and dst.coding_tda=di.coding_tda and dst.coddet_ingtda=di.coddet_ingtda 
          and dst.estado<>'C' and cst.Estado<>'C'),0) + 

          isnull((select sum(isnull(detdev.kneto,0)) from $bd.alm.det_devolucion_tienda detdev 
          inner join $bd.alm.cab_devolucion_tienda cabdev 
          on detdev.coddevol_tda=cabdev.coddevol_tda 
          where detdev.voucher=di.voucher and detdev.cdgcolor=di.cdgcolor and 
          detdev.cdgart=di.cdgart and detdev.correl=di.correl and 
          cabdev.codalmacen_destino=ci.codalmacen and cabdev.codemp_destino=ci.codemp 
          and detdev.coding_tda=di.coding_tda and detdev.coddet_ingtda=di.coddet_ingtda 
          and detdev.estado<>'C' and cabdev.Estado<>'C'),0) ) as stock_kg 

          from $bd.alm.det_ingresos_tienda di left join $bd.alm.cab_ingresos_tienda ci on 
          di.coding_tda=ci.coding_tda 
          where di.liqacab='0' and ci.codalmacen='$codalmacen_origen' and ci.codemp='$codemp_origen' and di.orden='$tOrden' 
          and di.correl='$codbarras' and di.estado<>'C' and ci.estado<>'C'     
          group by di.correl, di.voucher, di.descrip, di.kneto_recibido, 
          di.liqacab, di.cdgcolor, di.cdgart, ci.codalmacen, ci.codemp,
          di.descolor, di.grem, di.proceso, di.numordped, 
          di.orden, di.numot, di.coding_tda,  di.coddet_ingtda, ci.codemp 
          order by di.correl asc";

// echo $sql_items;
// echo "<br>---------<br>";
$i = $correlativo;

$dsl_items = db_fetch_all($sql_items);
foreach ($dsl_items as $id_ite => $all) {
  $codbarra   = trim($all["correl"]);
  $cdgart     = trim($all["cdgart"]);
  $producto   = trim($all["descrip"]);
  $cant_rollos = trim($all["cant_rollos"]);
  $peso_kg    = trim($all["stock_kg"]);
  $partida    = trim($all["voucher"]);
  $color      = trim($all["descolor"]);
  $codemp     = trim($all["codemp"]);
  $cdgcolor   = trim($all["cdgcolor"]);
  $grem1      = trim($all["grem1"]);
  $procesos   = trim($all["procesos"]);
  $codalmacen = trim($all["codalmacen"]);
  $coddet_ingtda = trim($all["coddet_ingtda"]);
  $coding_tda = trim($all["coding_tda"]);
  $numordped  = trim($all["numordped"]);
  $numot      = trim($all["numot"]);
?>
  <tr id="divreg<?= $i ?>">
    <input name="puntero" type="hidden" id="<?= $i ?>" />
    <td data-label='Item'><?= $i + 1 ?>
    </td>
    <td data-label='Cod Prod'><?= $cdgart ?>
      <input name="txt_cdgart" type="hidden" id="txt_cdgart<?= $i ?>" value="<?= $cdgart ?>" />
    </td>
    <td data-label='Cod Barra'><?= $codbarra ?>
      <input name="txt_codbarra" type="hidden" id="txt_codbarra<?= $i ?>" value="<?= $codbarra ?>" />
    </td>
    <td data-label='Partida'><?= $partida ?>
      <input name="txt_partida" type="hidden" id="txt_partida<?= $i ?>" value="<?= $partida ?>" />
    </td>
    <td data-label='Producto'><?= $producto ?>
      <input name="txt_desprod" type="hidden" id="txt_desprod<?= $i ?>" value="<?= $producto ?>" />
    </td>
    <td data-label='Proceso'><?= $procesos ?>
      <input name="txt_procesos" type="hidden" id="txt_procesos<?= $i ?>" value="<?= $procesos ?>" />
    </td>
    <td data-label='Color'><?= $color ?>
      <input name="txt_descolor" type="hidden" id="txt_descolor<?= $i ?>" value="<?= $color ?>" />
    </td>
    <td data-label='Cant Rollos'><?= $cant_rollos ?>
      <input name="txt_rollos" type="hidden" id="txt_rollos<?= $i ?>" value="<?= $cant_rollos ?>" />
    </td>
    <td data-label='Peso KG'><?= $peso_kg ?>
      <input name="txt_peso" type="hidden" disabled id="txt_peso<?= $i ?>" onBlur="calcular_pesos_stda()" value="<?= $peso_kg ?>" />

      <input name="txt_codemp" type="hidden" id="txt_codemp<?= $i ?>" value="<?= $codemp ?>" />
      <input name="txt_numordped" type="hidden" id="txt_numordped<?= $i ?>" value="<?= $numordped ?>" />
      <input name="txt_numot" type="hidden" id="txt_numot<?= $i ?>" value="<?= $numot ?>" />
      <input name="txt_codalmacen" type="hidden" id="txt_codalmacen<?= $i ?>" value="<?= $codalmacen ?>" />
      <input name="txt_cdgcolor" type="hidden" id="txt_cdgcolor<?= $i ?>" value="<?= $cdgcolor ?>" />
      <input name="txt_grem" type="hidden" id="txt_grem<?= $i ?>" value="<?= $grem1 ?>" />
      <input name="txt_coddet_ingtda" type="hidden" id="txt_coddet_ingtda<?= $i ?>" value="<?= $coddet_ingtda ?>" />
      <input name="txt_coding_tda" type="hidden" id="txt_coding_tda<?= $i ?>" value="<?= $coding_tda ?>" />
    </td>

    <td id="btnEliminar">
      <input
        type="button"
        class="btn2 btn-eliminar"
        value="🗑&nbsp;QUITAR"
        onclick="eliminar_fila_salida_tienda('<?= $i ?>');" />
    </td>
  </tr>

<?php
  $i++;
}
?>