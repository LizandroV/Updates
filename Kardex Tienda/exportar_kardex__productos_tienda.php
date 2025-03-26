<?php

header('Content-Type: application/vnd.ms-excel;');
session_start();
require('../includes/arch_cfg.php');
require('../includes/dbmssql_cfg.php');

$prod 		=trim($_REQUEST['partida']);//PARTIDA
$codalmacen =trim($_REQUEST['almacen']);
$codempresa =trim($_REQUEST['empresa']);
$codproducto =trim($_REQUEST['producto']);
$fecinicio 	=trim($_REQUEST['inicio']);
$fecfin 	=trim($_REQUEST['fin']);

$sql_fe = "select replace(replace(replace(LEFT(convert(varchar,getdate(),103),12)+''+right(getdate(),8),' ',''),':',''),'/','') as fecha  ";
$dsl_fe = $_SESSION['dbmssql']->getAll($sql_fe);
foreach ($dsl_fe as $v => $fec) {
    $num = $fec['fecha'];
}
header("Content-Disposition: attachment; filename=rep_kardex_productos_tienda_$num.xls;charset=utf-8");
header("Pragma: no-cache");
header("Expires: 0");

?>
<html>
<head>
    <title>Kardex_Productos_Tienda</title>
    <meta http-equiv="Content-Type" content="attachment; charset=utf-8" />
</head>
<body>
<table width="100%" border="1" cellpadding="0" cellspacing="1">
    <tr>
       <th colspan="11" height="30" style="text-align: center; vertical-align: middle;">
           <strong>REPORTE KARDEX DE PRODUCTOS TIENDA -  FECHA:<?php echo date('d/m/Y h:i:s');?></strong>
       </th> 
    </tr>

	<tr>
		<th rowspan="2" width="4%" height="20" style="text-align: center; vertical-align: middle; background: url('images/bg_topbar.gif');"><strong>ITEM</strong></th>
		<th rowspan="2" width="6%" style="text-align: center; vertical-align: middle; background: url('images/bg_topbar.gif');"><strong>FECHA ING</strong></th>
		<th rowspan="2" width="10%" style="text-align: center; vertical-align: middle; background: url('images/bg_topbar.gif');"><strong>PARTIDA</strong></th>
		<th rowspan="2" width="10%" style="text-align: center; vertical-align: middle; background: url('images/bg_topbar.gif');"><strong>PRODUCTO</strong></th>
		<th rowspan="2" width="10%" style="text-align: center; vertical-align: middle; background: url('images/bg_topbar.gif');"><strong>COLOR</strong></th>
		<th rowspan="2" width="10%" style="text-align: center; vertical-align: middle; background: url('images/bg_topbar.gif');"><strong>DOCUMENTO</strong></th>
		<th rowspan="2" width="10%" style="text-align: center; vertical-align: middle; background: url('images/bg_topbar.gif');"><strong>MOTIVO</strong></th>
		<th colspan = "2" width="8%" style="text-align: center; vertical-align: middle; background: url('images/bg_topbar.gif');"><strong>CANT INGRESO</strong></th>
		<th colspan = "2" width="8%" style="text-align: center; vertical-align: middle; background: url('images/bg_topbar.gif');"><strong>CANT SALIDA</strong></th>
	</tr>
	<tr> 
		<th width="8%" style="text-align: center; vertical-align: middle; background: url('images/bg_topbar.gif');"><strong>ROLLOS</strong></th>
		<th width="8%" style="text-align: center; vertical-align: middle; background: url('images/bg_topbar.gif');"><strong>KG</strong></th>
		<th width="8%" style="text-align: center; vertical-align: middle; background: url('images/bg_topbar.gif');"><strong>ROLLOS</strong></th>
		<th width="8%" style="text-align: center; vertical-align: middle; background: url('images/bg_topbar.gif');"><strong>KG</strong></th>
	</tr>
</table>

<table width="100%" border="0" cellpadding="0" cellspacing="0" class="borderTabla"  align="center">
  <tr>
    <td valign="top">
    <table width="100%" border="0" cellpadding="0" cellspacing="0" align="center">
    <tr>
    <td valign="top">
    <?php 
            $i=1;
            if($top1 == "" )$top1 = 999999;
    ?>    
        <div id="listOrds" style="width:100%;overflow:auto">

        <table width="100%" border="1" cellpadding="0" cellspacing="1" class="borderTabla" style="align-content: center;">
        <!-- <tr>
            <th width="8%" align="center" valign="middle" background="images/bg_topbar.gif" class="smalltext"><strong>LOTE</strong></th>
            <th width="8%" align="center" valign="middle" background="images/bg_topbar.gif" class="smalltext"><strong>CONTENEDOR</strong></th>
            <th width="7%" align="center" valign="middle" background="images/bg_topbar.gif" class="smalltext"><strong>ALMACEN</strong></th>
            <th width="9%" align="center" valign="middle" background="images/bg_topbar.gif" class="smalltext"><strong>EMPRESA</strong></th>
        </tr> -->
            <?php
			$suma_rollos_ing = 0;
            $suma_kilos_ing = 0;
			$suma_rollos_sal = 0;
            $suma_kilos_sal = 0;
			
            if($top1 <> "0")  

            $sql_kardex=" select producto, tipomov, traslado, 
			sum(rollo_ingreso)as rollo_ingreso, 
			sum(pesokg_ingreso) as peso_ingreso, 
			sum(rollo_salida)as rollo_salida, 
			sum(pesokg_salida)as peso_salida, 
			convert(date, fechareg)as fechareg, 
			convert(nvarchar(5),fechareg,108) as hora, 
			voucher, cdgart, cdgcolor, descolor, 
			almacen_origen, almacen_destino 
			from alm.temp_kardex_tienda ";
			
			$filtros = [];

			// Agregar condiciones solo si tienen valor
			if (!empty($codproducto)) {
				$filtros[] = "producto like '%$codproducto%' ";
			}
			
			if (!empty($fecinicio)) {
				$filtros[] = "convert(date,fechareg) >= '$fecinicio' ";
			}
			
			if (!empty($fecfin)) {
				$filtros[] = "convert(date,fechareg) <= '$fecfin' ";
			}
			
			if (!empty($codempresa) && $codempresa != 0) {
				$filtros[] = "cod_emp = $codempresa ";
			}
			
			// Si hay filtros, agregamos WHERE y unimos con AND
			if (!empty($filtros)) {
				$sql_kardex .= " WHERE " . implode(" AND ", $filtros);
			}


            $sql_kardex .= "group by producto, tipomov, 
			traslado, convert(date, fechareg), 
			convert(nvarchar(5),fechareg,108), 
			voucher, cdgart, cdgcolor, descolor, 
			almacen_origen, almacen_destino 
			order by fechareg, hora asc ";

			//echo $sql_kardex;
			$dsl_kardex=$_SESSION['dbmssql']->getAll($sql_kardex);
			foreach ($dsl_kardex as $vad => $kar) 
			{
				$producto_k 	= $kar['producto'];
				$tipomov_k 		= $kar['tipomov'];
				$traslado_k 	= $kar['traslado'];
				$rollo_ingreso_k= $kar['rollo_ingreso'];
				$peso_ingreso_k = $kar['peso_ingreso'];
				$rollo_salida_k	= $kar['rollo_salida'];
				$peso_salida_k	= $kar['peso_salida'];
				$fechareg_k 	= $kar['fechareg'];
				$partida_k 		= $kar['voucher'];
				$cdgart_k  		= $kar['cdgart'];
				$cdgcolor_k  	= $kar['cdgcolor'];
				$descolor_k 	= $kar['descolor'];
				$alm_origen_k 	= $kar['almacen_origen'];
				$alm_destino_k	= $kar['almacen_destino'];

				$suma_rollos_ing += (float) $rollo_ingreso_k;
                $suma_kilos_ing  += (float) $peso_ingreso_k;
				$suma_rollos_sal += (float) $rollo_salida_k;
                $suma_kilos_sal  += (float) $peso_salida_k;

				// if(is_null($rollo_ingreso_k) || $rollo_ingreso_k=="" || $rollo_ingreso_k==" " )
				// {
				// 	$canting = "";
				// }else{
				// 	$canting = $rollo_ingreso_k.' '."ROLLOS".' | '.number_format((float)$peso_ingreso_k, 2, '.', '').' '."KG";
				// }

				// if(is_null($rollo_salida_k) || $rollo_salida_k=="" || $rollo_salida_k==" " )
				// {
				// 	$cantsal = "";
				// }else{
				// 	$cantsal = $rollo_salida_k.' '."ROLLOS".' | '.number_format((float)$peso_salida_k, 2, '.', '').' '."KG";
				// }

				if(is_null($alm_destino_k)){$aorigen="";}else{$aorigen=$alm_origen_k;}
				if(is_null($alm_destino_k)){$adestino="";}else{$adestino=$alm_destino_k;}

				if(is_null($alm_destino_k) || $alm_destino_k=="" || $alm_destino_k==" " )
				{
					$trasladoCom=$traslado_k;
				}else{
					$trasladoCom=$traslado_k.' '.$aorigen.' >> '.$adestino;
				}

				if ($peso_ingreso_k != 0){
					$peso_ingreso_k = number_format((float)$peso_ingreso_k, 2, '.', '');
				}else{
					$peso_ingreso_k = '';
				}

				if ($peso_salida_k != 0){
					$peso_salida_k = number_format((float)$peso_salida_k, 2, '.', '');
				}else{
					$peso_salida_k = '';
				}

				$fecreg=date("d-m-Y", strtotime($fechareg_k));

		?>
			<tr onMouseOver="color1(this,'#dee7ec')" onMouseOut="color2(this,'#ffffff');">		
				<td height="23" align="center" valign="middle" style="font-size: 14px; color: black;"><?=$i?></td>
				<td height="23" align="center" valign="middle" style="font-size: 14px; color: black;"><?=$fecreg?></td>
				<td height="23" align="center" valign="middle" style="font-size: 14px; color: black;"><?=$partida_k?></td>
				<td height="23" align="center" valign="middle" style="font-size: 14px; color: black;"><?=$producto_k?></td>
				<td height="23" align="center" valign="middle" style="font-size: 14px; color: black;"><?=$descolor_k?></td>
				<td height="23" align="center" valign="middle" style="font-size: 14px; color: black;"><?=$tipomov_k?></td>
				<td height="23" align="center" valign="middle" style="font-size: 14px; color: black;"><?=$trasladoCom?></td>
				<td height="23" align="center" valign="middle" style="font-size: 14px; color: black;"><?=$rollo_ingreso_k?></td>
				<td height="23" align="center" valign="middle" style="font-size: 14px; color: black; mso-number-format:'0.00';"><?=$peso_ingreso_k?></td>
				<td height="23" align="center" valign="middle" style="font-size: 14px; color: black;"><?=$rollo_salida_k?></td>
				<td height="23" align="center" valign="middle" style="font-size: 14px; color: black; mso-number-format:'0.00';"><?=$peso_salida_k?></td>
			</tr>
		<?php

			$i++;

		}//for salidas
        ?> 
            <tr>
                <td height="25" colspan="11"></td>
            </tr>
			<tr>
				<td height="25" colspan="7"align="right" valign="middle" style="vertical-align: middle; font-size: 14px; font-weight: bold;">TOTAL GENERAL:&nbsp;&nbsp;</td>
				<td width="7%" align="center" valign="middle" style="text-align: center; vertical-align: middle; font-size: 13px;"><?=$suma_rollos_ing?></td>
				<td width="7%" align="center" valign="middle" style="text-align: center; vertical-align: middle; font-size: 13px; mso-number-format:'0.00';"><?=number_format((float)$suma_kilos_ing,2, '.', '')?></td>
				<td width="7%" align="center" valign="middle" style="text-align: center; vertical-align: middle; font-size: 13px;"><?=$suma_rollos_sal?></td>
				<td width="7%" align="center" valign="middle" style="text-align: center; vertical-align: middle; font-size: 13px; mso-number-format:'0.00';"><?=number_format((float)$suma_kilos_sal,2, '.', '')?></td>
			</tr>
        </table>

    </div>
    </td>
    </tr>
    </table>
    </td>
  </tr>
</table>
</body>
</html>