<?php

Class ReporteStockTienda
{
	var $tipo;
	var $top;

function listado_stock_tienda($info_st)
{  
	$CodEmp		=trim($info_st['CodEmp']);
	$CodAlmacen =trim($info_st['CodAlmacen']);
	$Prod 		=trim($info_st['Prod']);
	$Part 		=trim($info_st['Part']);
	$lote 		=trim($info_st['lote']);
	$contenedor =trim($info_st['contenedor']);

	?>
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
		<tr>
	    	<th width="4%" height="20" align="center" valign="middle" background="images/bg_topbar.gif" class="smalltext"><strong>ITEM</strong></th>
      		<th width="7%" align="center" valign="middle" background="images/bg_topbar.gif" class="smalltext"><strong>PARTIDA</strong></th>
	    	<th width="20%" align="center" valign="middle" background="images/bg_topbar.gif" class="smalltext"><strong>PRODUCTO</strong></th>
	    	<th width="20%" align="center" valign="middle" background="images/bg_topbar.gif" class="smalltext"><strong>COLOR</strong></th>
		  	<th width="8%" align="center" valign="middle" background="images/bg_topbar.gif" class="smalltext"><strong>STOCK ROLLOS</strong></th>
      	<th width="8%" align="center" valign="middle" background="images/bg_topbar.gif" class="smalltext"><strong>STOCK KG</strong></th>
      	<th width="10%" align="center" valign="middle" background="images/bg_topbar.gif" class="smalltext"><strong>ALMACEN</strong></th>
      	<th width="10%" align="center" valign="middle" background="images/bg_topbar.gif" class="smalltext"><strong>EMPRESA</strong></th>
		 	</tr>

			<?php
			$suma_rollos = 0;
            $suma_kilos = 0;
			$suma=0;
						
			if($top1 <> "0" )	

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

			// //fecha ing
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
				//$fechareg 	= trim($val['fechareg']);
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
			<tr onMouseOver="color1(this,'#dee7ec')" onMouseOut="color2(this,'#ffffff');">		
				<td height="25" align="center" valign="middle" style="font-size: 9.5px; color: black;"><?=$i?></td>
				<td height="25" align="center" valign="middle" style="font-size: 9.5px; color: black;"><?=$partida?></td>
				<td height="25" align="center" valign="middle" style="font-size: 9.5px; color: black;"><?=$producto?></td>
				<td height="25" align="center" valign="middle" style="font-size: 9.5px; color: black;"><?=$color?></td>
				<td height="25" align="center" valign="middle" style="font-size: 9.5px; color: black;">
					<a style="font-size: 12px" href="#" onclick="ver_rollos_partida_tienda('<?=$partida?>','<?=$producto?>','<?=$codalmacen_f?>','<?=$codemp_f?>')"><?=$stock_rollos?></a></td>
				<td height="25" align="center" valign="middle" style="font-size: 9.5px; color: black;"><?=number_format((float)$stock_kg, 2, '.', '')?></td>
				<td height="25" align="center" valign="middle" style="font-size: 9.5px; color: black;"><?=$almacen?></td>
				<td height="25" align="center" valign="middle" style="font-size: 9.5px; color: black;"><?=$empresa?></td>
			</tr>
		<?php

			$i++;
			}//for detalle
		?> 
			<tr>
				<td height="25"></td>
			</tr>
			<tr>
				<td colspan="3"></td>
				<td height="25" align="right" valign="middle" style="font-size: 10px; font-weight: bold;">TOTAL GENERAL:</td>
				<td width="7%" align="center" valign="middle" style="font-size: 10px; font-weight: bold;"><?=$suma_rollos.' ROLLOS'?></td>
				<td width="7%" align="center" valign="middle" style="font-size: 10px; font-weight: bold;"><?=number_format((float)$suma_kilos,2, '.', '').' KG'?></td>
			</tr> 
		</table>

	</div>
	</td>
    </tr>
    </table>
    </td>
  </tr>
</table>
  <?  }	
    }	 


?>