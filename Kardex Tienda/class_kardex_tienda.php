<?php

Class KardexTienda
{
	var $tipo;
	var $top;

function listado_kardex_tienda($info_st)
{  
		$prod 		=trim($info_st['prod']);//PARTIDA
		$codalmacen =trim($info_st['codalmacen']);
		$codempresa =trim($info_st['codempresa']);
		$codproducto =trim($info_st['codproducto']);
		$fecinicio 	=trim($info_st['fecinicio']);
		$fecfin 	=trim($info_st['fecfin']);

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

			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="borderTabla" style="align-content: center; ">
			<tr>
				<th width="4%" height="20" align="center" valign="middle" background="images/bg_topbar.gif" class="smalltext"><strong>ITEM</strong></th>
				<th width="6%" align="center" valign="middle" background="images/bg_topbar.gif" class="smalltext"><strong>FECHA ING</strong></th>
				<th width="10%" align="center" valign="middle" background="images/bg_topbar.gif" class="smalltext"><strong>PARTIDA</strong></th>
				<th width="10%" align="center" valign="middle" background="images/bg_topbar.gif" class="smalltext"><strong>PRODUCTO</strong></th>
				<th width="10%" align="center" valign="middle" background="images/bg_topbar.gif" class="smalltext"><strong>COLOR</strong></th>
				<th width="10%" align="center" valign="middle" background="images/bg_topbar.gif" class="smalltext"><strong>DOCUMENTO</strong></th>
				<th width="10%" align="center" valign="middle" background="images/bg_topbar.gif" class="smalltext"><strong>MOTIVO</strong></th>
				<th width="8%" align="center" valign="middle" background="images/bg_topbar.gif" class="smalltext"><strong>CANT INGRESO</strong></th>
				<th width="8%" align="center" valign="middle" background="images/bg_topbar.gif" class="smalltext"><strong>CANT SALIDA</strong></th>
		 </tr>
			<?php
			//INGRESO TIENDA
			$suma_rollos_ing = 0;
            $suma_kilos_ing = 0;
			$suma_rollos_sal = 0;
            $suma_kilos_sal = 0;

			$sql_ingresos=" 
			truncate table alm.temp_kardex_tienda; 

			select cab.coding_tda, det.descrip as producto, tm.nom_mov as tipomov, IIF(cab.cod_traslado>0,mot.descrip,tm.nom_mov) 
			as traslado, sum(det.cant_rollos) as cant_rollos, sum(det.kneto_recibido)as peso_kg, det.fechareg, det.cdgart, 
			det.voucher, det.cdgcolor, det.descolor, cab.codemp, emp.EmpRaz from alm.cab_ingresos_tienda cab
			left join EMPRESA emp on emp.EmpCod = cab.codemp
			inner join alm.det_ingresos_tienda det on cab.coding_tda=det.coding_tda 
			left join alm.tipomovimiento tm on cab.codtipomov=tm.codtipo 
			left join alm.motivo_traslado mot on cab.cod_traslado=mot.codtraslado  
			where det.estado<>'C' and cab.estado<>'C' and isnull(det.coddevol_tienda,0)=0 "; 	
			
			if($prod!=""){$sql_prod =" and det.voucher='".$prod."' ";}else{$sql_prod =" ";}

			if($codalmacen!=0){$sql_ingresos.=" and cab.codalmacen=$codalmacen ".' '.$sql_prod;}

			$sql_ingresos.=" group by cab.coding_tda, det.descrip, 
			tm.nom_mov, cab.cod_traslado, mot.descrip, tm.nom_mov, 
			det.fechareg, det.cdgart, det.voucher, det.cdgcolor, det.descolor, cab.codemp, emp.EmpRaz ";
			
			//echo $sql_ingresos;	

			$dsl_ingresos=$_SESSION['dbmssql']->getAll($sql_ingresos);	
			foreach($dsl_ingresos as $item => $val)
			{
				$producto = $val['producto'];
				$tipomov 	= $val['tipomov'];
				$traslado = $val['traslado'];
				$rollos_ingreso = $val['cant_rollos'];
				$pesokg_ingreso	= $val['peso_kg'];
				$fechareg = $val['fechareg'];
				$cdgart 	= $val['cdgart'];
				$partida 	= $val['voucher'];
				$cdgcolor = $val['cdgcolor'];
				$descolor = $val['descolor'];
				$cod_emp 	= $val['codemp'];
				$emp_raz 	= $val['EmpRaz'];

				$sql_insert_ing="insert into alm.temp_kardex_tienda(producto,	tipomov, traslado, 
					rollo_ingreso, pesokg_ingreso, fechareg, voucher, cdgart, cdgcolor, descolor, cod_emp, emp_razon) 
					values( 
					'".$producto."', 
					'".$tipomov."', 
					'".$traslado."', 
					".$rollos_ingreso.", 
					".$pesokg_ingreso.", 
					'".$fechareg."', 
					'".$partida."', 
					'".$cdgart."', 
					'".$cdgcolor."', 
					'".$descolor."',
					'".$cod_emp."',
					'".$emp_raz."' )";
				$_SESSION['dbmssql']->query($sql_insert_ing);	
			}

			//SALIDA - TRASLADO TIENDA		
			$sql_salidas="select cab.codsal_tienda, det.descrip as producto, tm.nom_mov as tipomov, IIF(cab.cod_traslado>0,mot.descrip,tm.nom_mov) as traslado, 
			sum(det.cant_rollos_salida) as cant_rollos, sum(det.kneto_salida)as peso_kg, det.fechareg, det.cdgart, det.voucher, det.cdgcolor, det.descolor, 
			alm.Almacen as almacen_origen, alm2.Almacen as almacen_destino, cab.codemp_origen, emp.EmpRaz
			from alm.cab_salidas_tienda cab 
			left join EMPRESA emp on emp.EmpCod = cab.codemp_origen
			inner join alm.det_salidas_tienda det on cab.codsal_tienda=det.codsal_tienda 
			left join alm.tipomovimiento tm on cab.codtipomov=tm.codtipo 
			left join alm.motivo_traslado mot on cab.cod_traslado=mot.codtraslado 
			left join im.ALMACEN alm on cab.codalmacen_origen=alm.CodAlmacen 
			left join im.ALMACEN alm2 on cab.codalmacen_destino=alm2.CodAlmacen 
			where det.estado<>'C' and cab.estado<>'C' ";

			if($prod!=""){$sql_prods =" and det.voucher='".$prod."' ";}else{$sql_prods =" ";}

			if($codalmacen!=0){$sql_salidas.=" and cab.codalmacen_origen=$codalmacen ".' '.$sql_prods;}
			
			$sql_salidas.=" group by cab.codsal_tienda, det.descrip, 
			tm.nom_mov, cab.cod_traslado, mot.descrip, tm.nom_mov, 
			det.fechareg, det.cdgart, det.voucher, det.cdgcolor, det.descolor, 
			alm.Almacen, alm2.Almacen, cab.codemp_origen, emp.EmpRaz ";

			//echo $sql_salidas;

			$dsl_salidas=$_SESSION['dbmssql']->getAll($sql_salidas);
			foreach ($dsl_salidas as $key => $sal) 
			{
						$producto_sal= $sal['producto'];
						$tipomov_sal = $sal['tipomov'];
						$traslado_sal= $sal['traslado'];
						$rollos_sal  = $sal['cant_rollos'];
						$pesokg_sal	 = $sal['peso_kg'];
						$fechareg_sal= $sal['fechareg'];
						$cdgart_sal  = $sal['cdgart'];
						$partida_sal = $sal['voucher'];
						$cdgcolor_sal= $sal['cdgcolor'];
						$descolor_sal= $sal['descolor'];
						$almacen_origen_sal = $sal['almacen_origen'];
						$almacen_destino_sal= $sal['almacen_destino'];
						$cod_emp_sal = $sal['codemp_origen'];
						$emp_raz_sal = $sal['EmpRaz'];

						$sql_insert_sal="insert into alm.temp_kardex_tienda(producto, 
						tipomov, traslado, rollo_salida, pesokg_salida, fechareg, voucher, cdgart, 
						cdgcolor, descolor, almacen_origen, almacen_destino, cod_emp, emp_razon) 
						values( 
						'".$producto_sal."', 
						'".$tipomov_sal."', 
						'".$traslado_sal."', 
						".$rollos_sal.", 
						".$pesokg_sal.", 
						'".$fechareg_sal."', 
						'".$partida_sal."', 
						'".$cdgart_sal."', 
						'".$cdgcolor_sal."', 
						'".$descolor_sal."',
						'".$almacen_origen_sal."',
						'".$almacen_destino_sal."',
						'".$cod_emp_sal."',
						'".$emp_raz_sal."' )";
				$_SESSION['dbmssql']->query($sql_insert_sal);	
			}


			// SALIDA - VENTA PACKING LIST
			$sql_packing=" select cab.CodPL, det.descrip as producto, 
			'SALIDA DE ALMACEN' as tipomov, 'VENTA' as traslado, 
			sum(1) as cant_rollos, 
			sum(det.kneto)as peso_kg, 
			det.fechareg, det.cdgart, det.voucher, det.cdgcolor, det.descolor,  
			alm.Almacen as almacen_origen, cab.codemp_origen, emp.EmpRaz
			from des.PLIST_CAB_TIENDA cab 
			left join EMPRESA emp on emp.EmpCod = cab.codemp_origen
			inner join des.PLIST_DET_TIENDA det on cab.CodPL=det.CodPL   
			left join im.ALMACEN alm on cab.codalmacen_origen=alm.CodAlmacen 
			where cab.EstadoGeneral<>'1' ";	

			if($prod!=""){$sql_prod_p =" and det.voucher='".$prod."' ";}else{$sql_prod_p =" ";}

			if($codalmacen!=0){$sql_packing.=" and cab.codalmacen_origen=$codalmacen ".' '.$sql_prod_p;}
			
			$sql_packing.=" group by cab.CodPL, det.descrip,  
			det.fechareg, det.cdgart, det.voucher, 
			det.cdgcolor, det.descolor, alm.Almacen, cab.codemp_origen, emp.EmpRaz ";

			//echo $sql_packing;

			$dsl_packing=$_SESSION['dbmssql']->getAll($sql_packing);
			foreach ($dsl_packing as $key => $pac) 
			{
				$producto_pac 		= $pac['producto'];
				$tipomov_pac 		= $pac['tipomov'];
				$traslado_pac		= $pac['traslado'];
				$rollos_pac 		= $pac['cant_rollos'];
				$pesokg_pac	 		= $pac['peso_kg'];
				$fechareg_pac		= $pac['fechareg'];
				$cdgart_pac  		= $pac['cdgart'];
				$partida_pac 		= $pac['voucher'];
				$cdgcolor_pac		= $pac['cdgcolor'];
				$descolor_pac		= $pac['descolor'];
				$almacen_origen_pac = $pac['almacen_origen'];
				$almacen_destino_pac= "";
				$cod_emp_pac 		= $pac['codemp_origen'];
				$emp_raz_pac 		= $pac['EmpRaz'];

				$sql_insert_sal2="insert into alm.temp_kardex_tienda(producto, 
				tipomov, traslado, rollo_salida, pesokg_salida, fechareg, voucher, cdgart, 
				cdgcolor, descolor, almacen_origen, almacen_destino, cod_emp, emp_razon) 
				values( 
				'".$producto_pac."', 
				'".$tipomov_pac."', 
				'".$traslado_pac."', 
				".$rollos_pac.", 
				".$pesokg_pac.", 
				'".$fechareg_pac."', 
				'".$partida_pac."', 
				'".$cdgart_pac."', 
				'".$cdgcolor_pac."', 
				'".$descolor_pac."',
				'".$almacen_origen_pac."',
				'".$almacen_destino_pac."',
				'".$cod_emp_pac."',
				'".$emp_raz_pac."' )";
				$_SESSION['dbmssql']->query($sql_insert_sal2);	
			}

			//INGRESO TIENDA  - DEVOLUCION
			$sql_devol="  
			select cab.coddevol_tda, det.descrip as producto, 'INGRESO DE ALMACEN' as tipomov, 
			mot.descrip as traslado, 
			sum(det.cant_rollos) as cant_rollos, sum(det.kneto)as peso_kg, 
			det.fechareg, det.cdgart, det.voucher, det.cdgcolor, det.descolor , cab.codemp_destino, emp.EmpRaz 
			from alm.cab_devolucion_tienda cab 
			left join EMPRESA emp on emp.EmpCod = cab.codemp_destino 
			inner join alm.det_devolucion_tienda det on cab.coddevol_tda=det.coddevol_tda  
			left join alm.motivo_traslado mot on cab.cod_traslado=mot.codtraslado 
			where det.estado<>'C' and cab.estado<>'C' "; 	
			
			if($prod!=""){$sql_prod_d =" and det.voucher='".$prod."' ";}else{$sql_prod_d =" ";}

			if($codalmacen!=0){$sql_devol.=" and cab.codalmacen_destino=$codalmacen ".' '.$sql_prod_d;}

			$sql_devol.=" group by cab.coddevol_tda, det.descrip, cab.cod_traslado, 
			mot.descrip, det.fechareg, det.cdgart, det.voucher, 
			det.cdgcolor, det.descolor, cab.codemp_destino, emp.EmpRaz ";
			
			//echo $sql_devol;	

			$dsl_devol=$_SESSION['dbmssql']->getAll($sql_devol);	
			foreach($dsl_devol as $item => $dev)
			{
				$producto_dev 	= $dev['producto'];
				$tipomov_dev 	= $dev['tipomov'];
				$traslado_dev 	= $dev['traslado'];
				$rollos_ingreso_dev = $dev['cant_rollos'];
				$pesokg_ingreso_dev	= $dev['peso_kg'];
				$fechareg_dev 	= $dev['fechareg'];
				$cdgart_dev 	= $dev['cdgart'];
				$partida_dev 	= $dev['voucher'];
				$cdgcolor_dev 	= $dev['cdgcolor'];
				$descolor_dev 	= $dev['descolor'];
				$cod_emp_dev 	= $dev['codemp_destino'];
				$emp_raz_dev 	= $dev['EmpRaz'];

				$sql_insert_ing="insert into alm.temp_kardex_tienda(producto,	tipomov, traslado, 
					rollo_ingreso, pesokg_ingreso, fechareg, voucher, cdgart, cdgcolor, descolor, cod_emp, emp_razon) 
					values( 
					'".$producto_dev."', 
					'".$tipomov_dev."', 
					'".$traslado_dev."', 
					".$rollos_ingreso_dev.", 
					".$pesokg_ingreso_dev.", 
					'".$fechareg_dev."', 
					'".$partida_dev."', 
					'".$cdgart_dev."', 
					'".$cdgcolor_dev."', 
					'".$descolor_dev."',
					'".$cod_emp_dev."',
					'".$emp_raz_dev."' )";
				$_SESSION['dbmssql']->query($sql_insert_ing);	
			}

			/// KARDEX - INGRESO Y SALIDA UNIFICADO
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

				if(is_null($rollo_ingreso_k) || $rollo_ingreso_k=="" || $rollo_ingreso_k==" " )
				{
					$canting = "";
				}else{
					$canting = $rollo_ingreso_k.' '."ROLLOS".' | '.number_format((float)$peso_ingreso_k, 2, '.', '').' '."KG";
				}

				if(is_null($rollo_salida_k) || $rollo_salida_k=="" || $rollo_salida_k==" " )
				{
					$cantsal = "";
				}else{
					$cantsal = $rollo_salida_k.' '."ROLLOS".' | '.number_format((float)$peso_salida_k, 2, '.', '').' '."KG";
				}

				if(is_null($alm_destino_k)){$aorigen="";}else{$aorigen=$alm_origen_k;}
				if(is_null($alm_destino_k)){$adestino="";}else{$adestino=$alm_destino_k;}

				if(is_null($alm_destino_k) || $alm_destino_k=="" || $alm_destino_k==" " )
				{
					$trasladoCom=$traslado_k;
				}else{
					$trasladoCom=$traslado_k.' '.$aorigen.' >> '.$adestino;
				}

				$fecreg=date("d-m-Y", strtotime($fechareg_k));

		?>
			<tr onMouseOver="color1(this,'#dee7ec')" onMouseOut="color2(this,'#ffffff');">		
				<td height="23" align="center" valign="middle" style="font-size: 9px; color: black;"><?=$i?></td>
				<td height="23" align="center" valign="middle" style="font-size: 9px; color: black;"><?=$fecreg?></td>
				<td height="23" align="center" valign="middle" style="font-size: 9px; color: black;"><?=$partida_k?></td>
				<td height="23" align="center" valign="middle" style="font-size: 9px; color: black;"><?=$producto_k?></td>
				<td height="23" align="center" valign="middle" style="font-size: 9px; color: black;"><?=$descolor_k?></td>
				<td height="23" align="center" valign="middle" style="font-size: 9px; color: black;"><?=$tipomov_k?></td>
				<td height="23" align="center" valign="middle" style="font-size: 9px; color: black;"><?=$trasladoCom?></td>
				<td height="23" align="center" valign="middle" style="font-size: 9px; color: black;"><?=$canting?></td>
				<td height="23" align="center" valign="middle" style="font-size: 9px; color: black;"><?=$cantsal?></td>
			</tr>
		<?php

			$i++;

		}//foreach end
		$suma_kilos_ing = number_format((float)$suma_kilos_ing,2, '.', '');
		$suma_kilos_sal = number_format((float)$suma_kilos_sal,2, '.', '');

		?> 
			<tr>
				<td height="20"></td>
			</tr>
			<tr>
				<td colspan="5"></td>
				<td height="25" colspan="2" align="right" valign="middle" style="font-size: 10px; font-weight: bold;">TOTAL GENERAL:</td>
				<td width="7%" align="center" valign="middle" style="font-size: 9px;"><?=$suma_rollos_ing.' ROLLOS | '.$suma_kilos_ing.' KG'?></td>
				<td width="7%" align="center" valign="middle" style="font-size: 9px;"><?=$suma_rollos_sal.' ROLLOS | '.$suma_kilos_sal.' KG'?></td>
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