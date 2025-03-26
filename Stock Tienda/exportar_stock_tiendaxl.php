<?php

header('Content-Type: application/vnd.ms-excel;');
session_start();
require('../includes/arch_cfg.php');
require('../includes/dbmssql_cfg.php');

$CodEmp     =trim($_REQUEST['CodEmp']);
$CodAlmacen =trim($_REQUEST['CodAlmacen']);
$Prod       =trim($_REQUEST['Prod']);
$Part       =trim($_REQUEST['Part']);
$lote       =trim($_REQUEST['lote']);
$contenedor =trim($_REQUEST['contenedor']);

$sql_fe = "select replace(replace(replace(LEFT(convert(varchar,getdate(),103),12)+''+right(getdate(),8),' ',''),':',''),'/','') as fecha  ";
$dsl_fe = $_SESSION['dbmssql']->getAll($sql_fe);
foreach ($dsl_fe as $v => $fec) {
    $num = $fec['fecha'];
}
header("Content-Disposition: attachment; filename=rep_stocktienda_$num.xls;charset=utf-8");
header("Pragma: no-cache");
header("Expires: 0");

?>
<html>
<head>
    <title>Reporte_Stock_Tienda</title>
    <meta http-equiv="Content-Type" content="attachment; charset=utf-8" />
</head>
<body>
<table width="100%" border="1" cellpadding="0" cellspacing="1" style="align-content: center;">
    <tr>
       <th colspan="8" height="30"><strong>REPORTE DE STOCK TIENDA -  FECHA:<? echo $fecha=date('d/m/Y h:i:s');?></strong></th> 
    </tr>

   <tr>
    <th width="4%" height="25" align="center" valign="middle" background="images/bg_topbar.gif" class="smalltext"><strong>ITEM</strong></td>

    <th width="5%" height="25" align="center" valign="middle" background="images/bg_topbar.gif" class="smalltext"><strong>PARTIDA</strong></th>

    <th width="20%" height="25" align="center" valign="middle" background="images/bg_topbar.gif" class="smalltext"><strong>PRODUCTO</strong></th>

    <th width="20%" height="25" align="center" valign="middle" background="images/bg_topbar.gif" class="smalltext"><strong>COLOR</strong></th>

    <th width="8%" height="25" align="center" valign="middle" background="images/bg_topbar.gif" class="smalltext"><strong>STOCK ROLLOS</strong></td>

    <th width="8%" height="25" align="center" valign="middle" background="images/bg_topbar.gif" class="smalltext"><strong>STOCK KG</strong></th>

    <th width="10%" height="25" align="center" valign="middle" background="images/bg_topbar.gif" class="smalltext"><strong>ALMACEN</strong></th>

    <th width="10%" height="25" align="center" valign="middle" background="images/bg_topbar.gif" class="smalltext"><strong>EMPRESA</strong></td>
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

        <table width="100%" border="0" cellpadding="0" cellspacing="1" class="borderTabla" style="align-content: center;">
        <!-- <tr>
            <th width="8%" align="center" valign="middle" background="images/bg_topbar.gif" class="smalltext"><strong>LOTE</strong></th>
            <th width="8%" align="center" valign="middle" background="images/bg_topbar.gif" class="smalltext"><strong>CONTENEDOR</strong></th>
            <th width="7%" align="center" valign="middle" background="images/bg_topbar.gif" class="smalltext"><strong>ALMACEN</strong></th>
            <th width="9%" align="center" valign="middle" background="images/bg_topbar.gif" class="smalltext"><strong>EMPRESA</strong></th>
        </tr> -->
            <?php

            $suma_rollos = 0;
            $suma_kilos = 0;
                        
            if($top1 <> "0")   

            $sql=" select di.voucher, di.descrip, di.descolor,  

			(select count(dit.coding_tda) from alm.det_ingresos_tienda dit
			inner join alm.cab_ingresos_tienda cit on dit.coding_tda=cit.coding_tda 
			where cit.codalmacen=ci.codalmacen and cit.codemp=ci.codemp 
			and dit.voucher=di.voucher and dit.cdgcolor=di.cdgcolor 
			and dit.cdgart=di.cdgart and dit.estado<>'C' 
			and cit.Estado<>'C' and dit.liqacab=0)as stock_rollos,
				
			(sum(isnull(di.kneto_recibido,0)) - 
			
			isnull((select sum(isnull(dpa.kneto,0)) from DES.PLIST_DET_TIENDA dpa  
					inner join des.PLIST_CAB_TIENDA cpa on dpa.CodPL=cpa.CodPL 
					where dpa.voucher=di.voucher and dpa.cdgcolor=di.cdgcolor 
					and dpa.cdgart=di.cdgart  
					and cpa.codalmacen_origen=ci.codalmacen and cpa.codemp_origen=ci.codemp 
					and cpa.EstadoGeneral<>'1'),0) - 
					
			isnull((select sum(isnull(dsta.kneto_salida,0)) from alm.det_salidas_tienda dsta  
					inner join alm.cab_salidas_tienda csta on dsta.codsal_tienda=csta.codsal_tienda 
					where dsta.voucher=di.voucher and dsta.cdgcolor=di.cdgcolor 
					and dsta.cdgart=di.cdgart and csta.codalmacen_origen=ci.codalmacen 
					and csta.codemp_origen=ci.codemp and dsta.estado<>'C' and csta.Estado<>'C'),0) + 

			isnull((select sum(isnull(detdev.kneto,0)) from alm.det_devolucion_tienda detdev 
					inner join alm.cab_devolucion_tienda cabdev 
					on detdev.coddevol_tda=cabdev.coddevol_tda 
					where detdev.voucher=di.voucher and detdev.cdgcolor=di.cdgcolor 
					and detdev.cdgart=di.cdgart  and cabdev.codalmacen_destino=ci.codalmacen 
					and cabdev.codemp_destino=ci.codemp  
					and detdev.estado<>'C' and cabdev.Estado<>'C'),0) ) as stock_kg, 
			
			di.cdgcolor, di.cdgart, ci.codalmacen, alm.Almacen, ci.codemp, emp.EmpRaz  
			from alm.det_ingresos_tienda di 
			inner join alm.cab_ingresos_tienda ci on di.coding_tda=ci.coding_tda 
			left join im.ALMACEN alm on ci.codalmacen=alm.CodAlmacen 
			left join EMPRESA emp on ci.codemp=emp.EmpCod 
			where di.estado<>'C' and ci.estado<>'C' ";

			//empresa
			if($CodEmp!=0){$sql.=" and ci.codemp=$CodEmp ";}else{$sql.="";}

			//producto
			if($Prod!=""){$sql.=" and di.descrip like'%".$Prod."%' ";}else{$sql.="";}

			//partida
			if($Part!=""){$sql.=" and di.voucher='".$Part."' ";}else{$sql.="";}

			//color
			if($contenedor!=""){$sql.=" and di.descolor like '%".$contenedor."%' ";}else{$sql.="";}
			
			//almacen
			if($CodAlmacen!=0){$sql.=" and ci.codalmacen=$CodAlmacen ";}else{$sql.="";}

            // //fecha ini
            // if($fecha_ini!=""){$sql.=" and convert(date,di.fechareg) >='".$fecha_ini."' ";}else{$sql.="";}

            // //fecha fin
            // if($fecha_fin!=""){$sql.=" and convert(date,di.fechareg) <='".$fecha_fin."' ";}else{$sql.="";}
			
			$sql.= " GROUP BY di.voucher, di.descrip, di.cdgcolor, di.cdgart, ci.codalmacen, di.descolor, 
			alm.Almacen, emp.EmpRaz, ci.codemp  
			
			HAVING (sum(isnull(di.kneto_recibido,0)) - 
			
			isnull((select sum(isnull(dpa.kneto,0)) from DES.PLIST_DET_TIENDA dpa  
			inner join des.PLIST_CAB_TIENDA cpa on dpa.CodPL=cpa.CodPL 
			where dpa.voucher=di.voucher and dpa.cdgcolor=di.cdgcolor 
			and dpa.cdgart=di.cdgart  
			and cpa.codalmacen_origen=ci.codalmacen and cpa.codemp_origen=ci.codemp 
			and cpa.EstadoGeneral<>'1'),0) - 
					
			isnull((select sum(isnull(dsta.kneto_salida,0)) from alm.det_salidas_tienda dsta  
			inner join alm.cab_salidas_tienda csta on dsta.codsal_tienda=csta.codsal_tienda 
			where dsta.voucher=di.voucher and dsta.cdgcolor=di.cdgcolor 
			and dsta.cdgart=di.cdgart and csta.codalmacen_origen=ci.codalmacen 
			and csta.codemp_origen=ci.codemp and dsta.estado<>'C' and csta.Estado<>'C'),0) + 
			
			isnull((select sum(isnull(detdev.kneto,0)) from alm.det_devolucion_tienda detdev 
			inner join alm.cab_devolucion_tienda cabdev 
			on detdev.coddevol_tda=cabdev.coddevol_tda 
			where detdev.voucher=di.voucher and detdev.cdgcolor=di.cdgcolor 
			and detdev.cdgart=di.cdgart  and cabdev.codalmacen_destino=ci.codalmacen 
			and cabdev.codemp_destino=ci.codemp  
			and detdev.estado<>'C' and cabdev.Estado<>'C'),0) )>0 ";

			$sql.=" ORDER BY di.voucher desc "; 
			//echo $sql;
        
            $query_sql  = $_SESSION['dbmssql']->getAll($sql);
            foreach($query_sql as $item => $val)
            {
                $partida 		= trim($val['voucher']);
				$producto		= trim($val['descrip']);
				$color 			= trim($val['descolor']);
				$stock_rollos 	= trim($val['stock_rollos']);
				$stock_kg 		= trim($val['stock_kg']);
				$almacen 		= trim($val['Almacen']);
				$empresa 		= trim($val['EmpRaz']);
				$codalmacen_f 	= trim($val['codalmacen']);
				$codemp_f 	  	= trim($val['codemp']);

                $suma_rollos += (float) $stock_rollos;
                $suma_kilos  += (float) $stock_kg;
                    
        ?>
        <!--<table width="100%" border="0" cellpadding="0" cellspacing="0" class="borderTabla1_" style="border-collapse: collapse; table-layout: fixed;">-->
            <tr onMouseOver="color1(this,'#dee7ec')" onMouseOut="color2(this,'#ffffff');">      
                <td height="25" align="center" valign="middle" style="font-size: 12px; color: black;"><?=$i?></td>
                <td height="25" align="center" valign="middle" style="font-size: 12px; color: black;"><?=$partida?></td>
                <td height="25" align="center" valign="middle" style="font-size: 12px; color: black;"><?=$producto?></td>
                <td height="25" align="center" valign="middle" style="font-size: 12px; color: black;"><?=$color?></td>
                <td height="25" align="center" valign="middle" style="font-size: 12px; color: black;"><?=$stock_rollos?></td>
                <td height="25" align="center" valign="middle" style="font-size: 12px; color: black; mso-number-format:'0.00';"><?= number_format((float)$stock_kg, 2, '.', '') ?></td>
                <td height="25" align="center" valign="middle" style="font-size: 12px; color: black;"><?=$almacen?></td>
                <td height="25" align="center" valign="middle" style="font-size: 12px; color: black;"><?=$empresa?></td>
            </tr>
        <?php

            $i++;
            }//for cabecera
        ?> 
            <tr>
                <td height="25"></td>
            </tr>
            <tr>
                <td colspan="4"></td>
                <td height="25" colspan="1"align="right" valign="middle" style="font-size: 13px; font-weight: bold;">TOTAL ROLLOS :</td>
                <td width="7%" align="center" valign="middle" style="font-size: 13px;"><?=number_format($suma_rollos,2)?></td>
                <td width="7%"></td>
                <td width="7%"></td>
                <td colspan="3"></td>
            </tr>
            <tr>
                <td colspan="4"></td>
                <td height="25" colspan="1"align="right" valign="middle" style="font-size: 13px; font-weight: bold;">TOTAL KILOS :</td>
                <td width="7%" align="center" valign="middle" style="font-size: 13px; mso-number-format:'0.00';"><?=number_format((float)$suma_kilos,2, '.', '')?></td>
                <td width="7%"></td>
                <td width="7%"></td>
                <td colspan="3"></td>
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