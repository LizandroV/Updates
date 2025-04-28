<?
//header('Content-Type: text/xml; charset=ISO-8859-1');
session_start();
require('arch_cfg.php');
require('dbmssql_cfg.php');


if($_REQUEST['ingreso_materiaprima'])
{
	$codigo_oc=$_REQUEST['codigo_oc'];	
	if($codigo_oc!="0" && $codigo_oc!="")
	{
		$sql_nuevoCodIng="select max(coding_mp) as codUltimo from alm.cab_ingresos_materiaprima ";	
		$dsl_nuevoCodIng=$_SESSION['dbmssql']->getAll($sql_nuevoCodIng);
		foreach($dsl_nuevoCodIng as $val => $valor){
			$result=$valor['codUltimo'];
		}
		
		if(is_null($result))
			$NewCodigo_mp='0000001';	
		else
			$NewCodigo_mp=(string)str_pad($result+1,7,'0',STR_PAD_LEFT);	
		
		echo $resultado="I.M. N&ordm; ".$NewCodigo_mp;
	}
	else 
		echo "I.M. N&ordm;";	
}


if($_REQUEST['salida_hilos'])
{
	$almacen_origen=$_REQUEST['almacen_origen'];	
	if($almacen_origen!='0')
	{
		$sql_nuevoCodSal="select max(codsal_hilo) as codUltimo from alm.cab_salidas_hilo ";	
		$dsl_nuevoCodSal=$_SESSION['dbmssql']->getAll($sql_nuevoCodSal);
		foreach($dsl_nuevoCodSal as $val => $valor){
			$result=$valor['codUltimo'];
		}
		
		if(is_null($result))
			$NewCodigo_smp='0000001';	
		else
			$NewCodigo_smp=(string)str_pad($result+1,7,'0',STR_PAD_LEFT);	
		
		echo $resultado="S.H. N&ordm; ".$NewCodigo_smp;
	}
	else 
		echo "S.H. N&ordm;";	
}

if($_REQUEST['recibo_pago'])
{
	$codemp=trim($_REQUEST['codemp']);
	if($codemp!='0')
	{
		$sql_serie_recibo="select Serie from NrosdeSerie where codEmpresa='".$codemp."' and codTipoDoc=7 and Estado=0";
		$dsl_serie_recibo=$_SESSION['dbmssql']->getAll($sql_serie_recibo);
		foreach($dsl_serie_recibo as $val => $rec)
		{
			$serie_rec=trim($rec['Serie']);
		}

		$sql_cod_recibo="select max(cod_recibo) as codUltimo from CAB_RECIBOPAGO where codemp='".$codemp."' and Estado not in('C') ";	
		$dsl_cod_recibo=$_SESSION['dbmssql']->getAll($sql_cod_recibo);
		foreach($dsl_cod_recibo as $val => $cod)
		{
			$result=trim($cod['codUltimo']);
		}		
		if(is_null($result))
			$nuevo_cod='0000001';	
		else
			$nuevo_cod=(string)str_pad($result+1,7,'0',STR_PAD_LEFT);	
		
		echo $resultado=$serie_rec.'-'.$nuevo_cod;
	}
	else 
		echo "";	
}


if($_REQUEST['salida_quimicos'])
{
	$almacen_origen=$_REQUEST['almacen_origen'];	
	if($almacen_origen!='0')
	{
		$sql_nuevoCodSal="select max(codsal_quim) as codUltimo from alm.cab_salidas_quimico ";	
		$dsl_nuevoCodSal=$_SESSION['dbmssql']->getAll($sql_nuevoCodSal);
		foreach($dsl_nuevoCodSal as $val => $valor){
			$result=$valor['codUltimo'];
		}
		
		if(is_null($result))
			$NewCodigo_smp='0000001';	
		else
			$NewCodigo_smp=(string)str_pad($result+1,7,'0',STR_PAD_LEFT);	
		
		echo $resultado="S.Q. N&ordm; ".$NewCodigo_smp;
	}
	else 
		echo "S.Q. N&ordm;";	
}

if($_REQUEST['devol_hilos'])
{
	$codtraslado=trim($_REQUEST['codtraslado']);	
	if($codtraslado!=='0')
	{
		$sql_nuevoCodSal="select max(coddevol_hilo) as codUltimo from alm.cab_devolucion_hilo ";	
		$dsl_nuevoCodSal=$_SESSION['dbmssql']->getAll($sql_nuevoCodSal);
		foreach($dsl_nuevoCodSal as $val => $valor){
			$result=$valor['codUltimo'];
		}
		
		if(is_null($result))
			$NewCodigo_smp='0000001';	
		else
			$NewCodigo_smp=(string)str_pad($result+1,7,'0',STR_PAD_LEFT);	
		
		echo $resultado="D.H. N&ordm; ".$NewCodigo_smp;
	}
	else 
		echo "D.H. N&ordm;";	
}

if($_REQUEST['devol_tienda'])
{
	$codtraslado=trim($_REQUEST['codtraslado']);
	if($codtraslado!=='0')
	{
		$sql_nuevoCodSal="select max(coddevol_tda) as codUltimo from alm.cab_devolucion_tienda ";
		$dsl_nuevoCodSal=$_SESSION['dbmssql']->getAll($sql_nuevoCodSal);
		foreach($dsl_nuevoCodSal as $val => $valor)
		{
			$result=$valor['codUltimo'];
		}
		
		if(is_null($result))
			$NewCodigo_smp='0000001';	
		else
			$NewCodigo_smp=(string)str_pad($result+1,7,'0',STR_PAD_LEFT);	
		
		echo $resultado="D.T. N&ordm; ".$NewCodigo_smp;
	}
	else 
		echo "D.T. N&ordm;";	
}

if($_REQUEST['devol_quimicos'])
{
	$codtraslado=trim($_REQUEST['codtraslado']);	
	if($codtraslado!=='0')
	{
		$sql_nuevoCodSal="select max(coddevol_quim) as codUltimo from alm.cab_devolucion_quimico ";	
		$dsl_nuevoCodSal=$_SESSION['dbmssql']->getAll($sql_nuevoCodSal);
		foreach($dsl_nuevoCodSal as $val => $valor){
			$result=$valor['codUltimo'];
		}
		
		if(is_null($result))
			$NewCodigo_smp='0000001';	
		else
			$NewCodigo_smp=(string)str_pad($result+1,7,'0',STR_PAD_LEFT);	
		
		echo $resultado="D.Q. N&ordm; ".$NewCodigo_smp;
	}
	else 
		echo "D.Q. N&ordm;";	
}

if($_REQUEST['cotiza_clientes'])
{
	$codemp=$_REQUEST['codemp'];	
	if($codemp!='0')
	{
		$sql_cod_cotiza="select max(cod_cotizacli) as codUltimo from alm.cab_cotizacion_clientes ";	
		$dsl_cod_cotiza=$_SESSION['dbmssql']->getAll($sql_cod_cotiza);
		foreach($dsl_cod_cotiza as $val => $cod){
			$result=$cod['codUltimo'];
		}		
		if(is_null($result))
			$nuevo_cod='0000001';	
		else
			$nuevo_cod=(string)str_pad($result+1,7,'0',STR_PAD_LEFT);	
		
		echo $resultado="C.C. N&ordm; ".$nuevo_cod;
	}
	else 
		echo "C.C. N&ordm;";	
}



if($_REQUEST['devol_prodt'])
{
		$sql_nuevocod_prodt="select max(idprodt) as codUltimo from alm.cab_devolucion_prodt ";	
		$dsl_nuevocod_prodt=$_SESSION['dbmssql']->getAll($sql_nuevocod_prodt);
		foreach($dsl_nuevocod_prodt as $val => $valor){
			$result=$valor['codUltimo'];
		}
		
		if(is_null($result))
			$NewCodigo_str='0000001';	
		else
			$NewCodigo_str=(string)str_pad($result+1,7,'0',STR_PAD_LEFT);	
		
		echo $resultado="D.P. N&ordm; ".$NewCodigo_str;
}


if($_REQUEST['ingreso_tienda'])
{
	$codemp=trim($_REQUEST['codemp']);	
	if($codemp!='0')
	{
		$sql_cod_tienda="select max(coding_tda)as codUltimo from alm.cab_ingresos_tienda ";	
		$dsl_cod_tienda=$_SESSION['dbmssql']->getAll($sql_cod_tienda);
		foreach($dsl_cod_tienda as $val => $valor){
			$result=$valor['codUltimo'];
		}
		
		if(is_null($result))
			$NewCodigo_tda='0000001';	
		else
			$NewCodigo_tda=(string)str_pad($result+1,7,'0',STR_PAD_LEFT);	
		
		echo $resultado="I.T. N&ordm; ".$NewCodigo_tda;
	}
	else 
		echo "I.T. N&ordm;";	
}


if($_REQUEST['salida_tienda'])
{
	$cod_traslado=$_REQUEST['cod_traslado'];	
	if($cod_traslado!='0')
	{
		$sql_nuevoCodSal="select max(codsal_tienda) as codUltimo from alm.cab_salidas_tienda ";	
		$dsl_nuevoCodSal=$_SESSION['dbmssql']->getAll($sql_nuevoCodSal);
		foreach($dsl_nuevoCodSal as $val => $valor){
			$result=$valor['codUltimo'];
		}
		
		if(is_null($result))
			$NewCodigo_st='0000001';	
		else
			$NewCodigo_st=(string)str_pad($result+1,7,'0',STR_PAD_LEFT);	
		
		echo $resultado="S.T. N&ordm; ".$NewCodigo_st;
	}
	else 
		echo "S.T. N&ordm;";	
}


	
if($_REQUEST['orden_de_servicio'] or $_REQUEST['orden_de_serviciopl'])
{
	$obra=$_REQUEST['codigoObra'];	
	if($obra!='0')
	{
		$sql_ver_codifObra="select NegCne from Negocio where NegCod ='".$_REQUEST['codigoObra']."'";	
		$dsl_ver_codifObra=$_SESSION['dbmssql']->getAll($sql_ver_codifObra);
		foreach($dsl_ver_codifObra as $val => $value){
			$codificacion=$value['NegCne'];
		}
		
		////where CodServNeg ='".$_REQUEST['codigoObra']."'
		$sql_NuevoCodCompra="select max(CodOrdServ) codUltimo from CabOrdServ ";	
		$dsl_NuevoCodCompra=$_SESSION['dbmssql']->getAll($sql_NuevoCodCompra);
		foreach($dsl_NuevoCodCompra as $val => $valor){
			$result=$valor['codUltimo'];
		}
		
		if(is_null($result))
			$NewCodigo='0000001';	
		else
			$NewCodigo=(string)str_pad($result+1,7,'0',STR_PAD_LEFT);	
		
		echo $resultado="O.S. N&ordm; ".$NewCodigo."-".$codificacion;
	}
	else 
		echo "O.S. N&ordm;";	
}



if($_REQUEST['orden_de_despacho'] or $_REQUEST['orden_de_despachopl'])
{
	$obra=$_REQUEST['codigoObra'];
	if($obra!='0')
	{
	
		$sql_ver_codifObra="select NegCne from Negocio where NegCod ='".$_REQUEST['codigoObra']."'";	
		$dsl_ver_codifObra=$_SESSION['dbmssql']->getAll($sql_ver_codifObra);
		foreach($dsl_ver_codifObra as $val => $value){
			$codificacion=$value['NegCne'];
		}
	
		$sql_NuevoCodIngreso="select max(CodOrdDesp) codUltimo from CabOrdDesp where CodDespNeg ='".$_REQUEST['codigoObra']."' ";	
		$dsl_NuevoCodIngreso=$_SESSION['dbmssql']->getAll($sql_NuevoCodIngreso);
		foreach($dsl_NuevoCodIngreso as $val => $ing)
		{
			$result=$ing['codUltimo'];
		}
		
		if(is_null($result))
			$NewCodigoIng='0000001';	
		else
			$NewCodigoIng=(string)str_pad($result+1,7,'0',STR_PAD_LEFT);	
		
		echo $resultado="O.D. N&ordm; ".$NewCodigoIng."-".$codificacion;
	}
	else 
		echo "O.D. N&ordm;";	
}


if($_REQUEST['orden_de_gelectro'])
{
	$obra=$_REQUEST['codigoObra'];	
	$codServi=$_REQUEST['CodServi'];
	if($obra!='0')
	{
		//OBTIENE EMPRESA
		$sqlSer="SELECT CodServEmp FROM CABORDSERV WHERE CodServNeg='".$obra."' and CodOrdServ='".$codServi."'";
		$dsl_OSServ=$_SESSION['dbmssql']->getAll($sqlSer);
		foreach($dsl_OSServ as $valid => $ingSv)
		{
			$resultEmp=$ingSv['CodServEmp'];
		}		

		// SE OBTIENE EL NUMERO DE GUIA DE OTRA MANERA - RROJAS
		//$sql_NuevoCodIngreso="select max(correlativo) as codUltimo from CORRELATIVOS_GUIAS where negocio ='".$resultEmp."'";	
		//$dsl_NuevoCodIngreso=$_SESSION['dbmssql']->getAll($sql_NuevoCodIngreso);
		//foreach($dsl_NuevoCodIngreso as $val => $ing)
		//{
		//	$result=$ing['codUltimo'];
		//}
		
		//if(is_null($result))
		//	$NewCodigoIng='1';	
		//else
		//	$NewCodigoIng=$result+1;	
		//echo $resultado="T001-".$NewCodigoIng;


		//OBTIENE CODSERIE
		$sql_codserie="select codSerie from NrosdeSerie where estado='0' and codEmpresa='".$resultEmp."' and codTipoDoc='5' ";
		$dsl_codserie=$_SESSION['dbmssql']->getAll($sql_codserie);
		foreach ($dsl_codserie as $val => $dato) {
			$codserie=$dato['codSerie'];
		}

		//OBTIENE NUMERO DE GUIA ELETRONICA
		$sql_NuevoCodIngreso="select [dbo].FN_NumeroDocumentoGE(".trim($resultEmp).", ".trim($codserie).") as codUltimo";
		$dsl_NuevoCodIngreso=$_SESSION['dbmssql']->getAll($sql_NuevoCodIngreso);
		foreach ($dsl_NuevoCodIngreso as $val => $ing) {
			$result=$ing['codUltimo'];
		}

		echo $result;

	}
	else 
		echo "0";	
}



if($_REQUEST['orden_de_gelectrodpl'])
{
	$empresa=$_REQUEST['codigoempresa'];	
	
	if($obra!='0')
	{
		// SE OBTIENE EL NUMERO DE GUIA DE OTRA MANERA - RROJAS
		//$sql_NuevoCod="select max(correlativo) as codUltimo from CORRELATIVOS_GUIAS where negocio =".$empresa."";	
		//$dsl_NuevoCod=$_SESSION['dbmssql']->getAll($sql_NuevoCod);
		//foreach($dsl_NuevoCod as $val => $ing)
		//{
		//	$result=$ing['codUltimo'];
		//}
		
		//if(is_null($result))
		//	$NewCodigoIng='1';	
		//else
		//	$NewCodigoIng=$result+1;	
		
		//echo $resultado="T001-".$NewCodigoIng;


		//OBTIENE CODSERIE
		$sql_codserie="select codSerie from NrosdeSerie where estado='0' and codEmpresa='".$empresa."' and codTipoDoc='5'";
		$dsl_codserie=$_SESSION['dbmssql']->getAll($sql_codserie);
		foreach ($dsl_codserie as $val => $dato) {
			$codserie=$dato['codSerie'];
		}

		//OBTIENE NUMERO DE GUIA ELETRONICA
		$sql_NuevoCod="select [dbo].FN_NumeroDocumentoGE(".trim($empresa).", ".trim($codserie).") as codUltimo";
		$dsl_NuevoCod=$_SESSION['dbmssql']->getAll($sql_NuevoCod);
		foreach ($dsl_NuevoCod as $val => $ing) {
			$result=$ing['codUltimo'];
		}

		echo $result;

	}
	else 
		echo "0";
}


if($_REQUEST['orden_de_factura'] or $_REQUEST['orden_de_facturapl'])
{
	$obra=$_REQUEST['codigoObra'];	
	if($obra!='0')
	{
	
		$sql_ver_codifNego="select NegCne from Negocio where NegCod ='".$_REQUEST['codigoObra']."'";	
		$dsl_ver_codifNego=$_SESSION['dbmssql']->getAll($sql_ver_codifNego);
		foreach($dsl_ver_codifNego as $val => $value)
		{
			$codificacion=$value['NegCne'];
		}
	
		$sql_NuevoCodFac="select max(CodOrdFac) codUltimo from CABORDFAC where CodFacNeg ='".$_REQUEST['codigoObra']."'";	
		$dsl_NuevoCodFac=$_SESSION['dbmssql']->getAll($sql_NuevoCodFac);
		foreach($dsl_NuevoCodFac as $val => $vales)
		{
			$result=$vales['codUltimo'];
		}
		
		if(is_null($result))
			$NewCodigoFac='0000001';	
		else
			$NewCodigoFac=(string)str_pad($result+1,7,'0',STR_PAD_LEFT);	
		
		echo $resultado="F. N&ordm; ".$NewCodigoFac."-".$codificacion;
	}
	else 
		echo "F. N&ordm;";	
}


if($_REQUEST['orden_de_proforma'] or $_REQUEST['orden_de_proformapl'])
{
	$obra=$_REQUEST['codigoObra'];	
	if($obra!='0')
	{
	
		$sql_ver_codifNego="select NegCne from Negocio where NegCod ='".$_REQUEST['codigoObra']."'";	
		$dsl_ver_codifNego=$_SESSION['dbmssql']->getAll($sql_ver_codifNego);
		foreach($dsl_ver_codifNego as $val => $value)
		{
			$codificacion=$value['NegCne'];
		}
	
		$sql_NuevoCodRegVal="select max(CodOrdProf) codUltimo from CabOrdProf where CodProfNeg ='".$_REQUEST['codigoObra']."'";	
		$dsl_NuevoCodRegVal=$_SESSION['dbmssql']->getAll($sql_NuevoCodRegVal);
		foreach($dsl_NuevoCodRegVal as $val => $valesReg){
			$resultado=$valesReg['codUltimo'];
		}
		
		if(is_null($resultado))
			$NewCodigoValReg='0000001';	
		else
			$NewCodigoValReg=(string)str_pad($resultado+1,7,'0',STR_PAD_LEFT);	
		
		echo $codigo="P. N&ordm; ".$NewCodigoValReg."-".$codificacion;
	}
	else 
		echo "P. N&ordm;";	
}



if($_REQUEST['orden_de_pago'])
{
	$codnegocio	= $_REQUEST['codnegocio'];
	$codcobro	= $_REQUEST['codcobro'];
	if($codnegocio!='0' && $codcobro!='0')
	{
	
		switch($codcobro)
		{
			case 'Q':{$sigla='CHQ';break;};
			case 'L':{$sigla='LTR';break;};		
			case 'C':{$sigla='CSH';break;};
		}
		
		$sql_ver_codifNego="select NegCne from Negocio where NegCod ='".$_REQUEST['codnegocio']."'";	
		$dsl_ver_codifNego=$_SESSION['dbmssql']->getAll($sql_ver_codifNego);
		foreach($dsl_ver_codifNego as $val => $value)
		{
			$codificacion=$sigla.$value['NegCne'];
		}
	
		$sql_NuevoCodRegVal = " select 	max(CodOrdPag) as codUltimo 
								from   	CabOrdPago 
								where	CodPagNeg = '".$_REQUEST['codnegocio']."' and 
										TipoCob = '".$codcobro."'";	
		$dsl_NuevoCodRegVal=$_SESSION['dbmssql']->getAll($sql_NuevoCodRegVal);
		foreach($dsl_NuevoCodRegVal as $val => $valesReg)
		{
			$resultado=$valesReg['codUltimo'];
		}
		
		if(is_null($resultado))
			$NewCodigoValReg='0000001';	
		else
			$NewCodigoValReg=(string)str_pad($resultado+1,7,'0',STR_PAD_LEFT);	
		
		echo $codigo="O.P. N&ordm; ".$NewCodigoValReg."-".$codificacion;
	}
	else 
		echo "O.P. N&ordm;";	
}


if($_REQUEST['orden_de_registro'])
{
	$codnegocio	= $_REQUEST['codnegocio'];
	$codcobro	= $_REQUEST['codcobro'];
	if($codnegocio!='0' && $codcobro!='0')
	{
	
		switch($codcobro)
		{
			case 'Q':{$sigla='CHQ';break;};
			case 'L':{$sigla='LTR';break;};		
			case 'C':{$sigla='CSH';break;};
		}
		
		$sql_ver_codifNego="select NegCne from Negocio where NegCod ='".$_REQUEST['codnegocio']."'";	
		$dsl_ver_codifNego=$_SESSION['dbmssql']->getAll($sql_ver_codifNego);
		foreach($dsl_ver_codifNego as $val => $value)
		{
			$codificacion=$sigla.$value['NegCne'];
		}
	
		$sql_NuevoCodRegVal = " select 	max(CodRegPag) as codUltimo 
								from   	CabRegPago 
								where	CodRegNeg = '".$_REQUEST['codnegocio']."' and
										TipoCob = '".$codcobro."'";	
		$dsl_NuevoCodRegVal=$_SESSION['dbmssql']->getAll($sql_NuevoCodRegVal);
		foreach($dsl_NuevoCodRegVal as $val => $valesReg)
		{
			$resultado=$valesReg['codUltimo'];
		}
		
		if(is_null($resultado))
			$NewCodigoValReg='0000001';	
		else
			$NewCodigoValReg=(string)str_pad($resultado+1,7,'0',STR_PAD_LEFT);	
		
		echo $codigo="R.P. N&ordm; ".$NewCodigoValReg."-".$codificacion;
	}
	else 
		echo "R.P. N&ordm;";
		
		
}


if($_REQUEST['registro_pago_compras'])
{
	$codemp		= trim($_REQUEST["codemp"]);
	if($codemp!=="0")
	{
		$sql_NuevoCodRegVal ="select max(CodRegPag) as codUltimo 
							from rc.CABREGPAGO_COMPRAS 
							where CodRegEmp='".$codemp."' and 
							Estado not in('C','E') ";
		$dsl_NuevoCodRegVal=$_SESSION['dbmssql']->getAll($sql_NuevoCodRegVal);
		foreach($dsl_NuevoCodRegVal as $val => $valesReg)
		{
			$resultado=trim($valesReg['codUltimo']);
		}
		
		if(is_null($resultado))
			$NewCodigoValReg='0000001';	
		else
			$NewCodigoValReg=(string)str_pad($resultado+1,7,'0',STR_PAD_LEFT);	
		
		echo $codigo="R.P.C. N&ordm; ".$NewCodigoValReg;
	}
	else 
		echo "R.P.C. N&ordm;";
}


if($_REQUEST['liqui_cobranza'])
{
	$codemp	= trim($_REQUEST["codemp"]);
	if($codemp!=="0")
	{
		$sql_NuevoCod="select max(cod_liquida) as codUltimo 
							from rc.CAB_LIQUIDA_COBRANZA 
							where codemp='".$codemp."' and 
							Estado not in('C') ";
		$dsl_NuevoCod=$_SESSION['dbmssql']->getAll($sql_NuevoCod);
		foreach($dsl_NuevoCod as $val => $vales)
		{
			$resultado=trim($vales['codUltimo']);
		}
		
		if(is_null($resultado))
			$NewCodigoVal='0000001';	
		else
			$NewCodigoVal=(string)str_pad($resultado+1,7,'0',STR_PAD_LEFT);	
		
		echo $codigo="L.C. N&ordm; ".$NewCodigoVal;
	}
	else 
		echo "L.C. N&ordm;";
}


if($_REQUEST['orden_de_detraccion'])
{
	$codnegocio	= $_REQUEST['codnegocio'];
	$codcobro	= $_REQUEST['codcobro'];
	if($codnegocio!='0' && $codcobro!='0')
	{
		switch($codcobro)
		{
			case 'Q':{$sigla='CHQ';break;};
			case 'L':{$sigla='LTR';break;};		
			case 'C':{$sigla='CSH';break;};
		}
		
		$sql_ver_codifNego="select NegCne from Negocio where NegCod ='".$_REQUEST['codnegocio']."'";	
		$dsl_ver_codifNego=$_SESSION['dbmssql']->getAll($sql_ver_codifNego);
		foreach($dsl_ver_codifNego as $val => $value)
		{
			$codificacion=$sigla.$value['NegCne'];
		}
	
		$sql_NuevoDet = "select max(CodRegDet) as codUltimo 
						 from   CabRegDetra 
						 where	CodRegNeg = '".$_REQUEST['codnegocio']."' and
								TipoCob = '".$codcobro."'";	
		$dsl_NuevoDet=$_SESSION['dbmssql']->getAll($sql_NuevoDet);
		foreach($dsl_NuevoDet as $val => $RegDetrac)
		{
			$resultado=$RegDetrac['codUltimo'];
		}
		
		if(is_null($resultado))
			$NewCodigoDet='0000001';	
		else
			$NewCodigoDet=(string)str_pad($resultado+1,7,'0',STR_PAD_LEFT);	
		
		echo $codigo="R.D. N&ordm; ".$NewCodigoDet."-".$codificacion;
	}
	else 
		echo "R.D. N&ordm;";	
}

if($_REQUEST['detraccion_compras'])
{
	$codnegocio	= $_REQUEST['codnegocio'];
	$codcobro	= $_REQUEST['codcobro'];
	if($codnegocio!='0' && $codcobro!='0')
	{
		switch($codcobro)
		{
			case 'Q':{$sigla='CHQ';break;};
			case 'L':{$sigla='LTR';break;};		
			case 'C':{$sigla='CSH';break;};
		}
		
		$sql_ver_codifNego="select NegCne from Negocio where NegCod ='".$_REQUEST['codnegocio']."'";	
		$dsl_ver_codifNego=$_SESSION['dbmssql']->getAll($sql_ver_codifNego);
		foreach($dsl_ver_codifNego as $val => $value)
		{
			$codificacion=$sigla.$value['NegCne'];
		}
	
		$sql_NuevoDet = "select max(CodRegDet) as codUltimo 
					from CABREGDETRA_COMPRAS  
					where CodRegNeg ='".$_REQUEST['codnegocio']."' and 
					TipoCob ='".$codcobro."' ";	
		$dsl_NuevoDet=$_SESSION['dbmssql']->getAll($sql_NuevoDet);
		foreach($dsl_NuevoDet as $val => $RegDetrac)
		{
			$resultado=$RegDetrac['codUltimo'];
		}
		
		if(is_null($resultado))
			$NewCodigoDet='0000001';	
		else
			$NewCodigoDet=(string)str_pad($resultado+1,7,'0',STR_PAD_LEFT);	
		
		echo $codigo="R.D.C. N&ordm; ".$NewCodigoDet."-".$codificacion;
	}
	else 
		echo "R.D.C. N&ordm;";	
}

if($_REQUEST['orden_autodetra'])
{
	$codnegocio	= $_REQUEST['codnegocio'];
	$codcobro	= $_REQUEST['codcobro'];
	if($codnegocio!='0' && $codcobro!='0')
	{
		switch($codcobro)
		{
			case 'Q':{$sigla='CHQ';break;};
			case 'L':{$sigla='LTR';break;};		
			case 'C':{$sigla='CSH';break;};
		}
		
		$sql_ver_codifNego="select NegCne from Negocio where NegCod ='".$_REQUEST['codnegocio']."' ";	
		$dsl_ver_codifNego=$_SESSION['dbmssql']->getAll($sql_ver_codifNego);
		foreach($dsl_ver_codifNego as $val => $value)
		{
			$codificacion=$sigla.$value['NegCne'];
		}
	
		$sql_NuevoDet="select max(CodAutoCab) as codUltimo 
						 from CABREGAUTODETRA 
						 where CodRegNeg='".$_REQUEST['codnegocio']."' 
						 and TipoCob='".$codcobro."' ";	
		$dsl_NuevoDet=$_SESSION['dbmssql']->getAll($sql_NuevoDet);
		foreach($dsl_NuevoDet as $val => $RegDetrac)
		{
			$resultado=$RegDetrac['codUltimo'];
		}
		
		if(is_null($resultado))
			$NewCodigoDet='0000001';	
		else
			$NewCodigoDet=(string)str_pad($resultado+1,7,'0',STR_PAD_LEFT);	
		
		echo $codigo="R.AD. N&ordm; ".$NewCodigoDet."-".$codificacion;
	}
	else 
		echo "R.AD. N&ordm;";		
}


if($_REQUEST['registro_compras'])
{
	$cod_emp= $_REQUEST['cod_emp'];
	if($cod_emp!=="0")
	{		
		$sql_new_compra="select max(cod_compras) as codUltimo from rc.CABORDCOMPRAS where codemp=".$cod_emp." and estado not in('C') ";	
		$dsl_new_compra=$_SESSION['dbmssql']->getAll($sql_new_compra);
		foreach($dsl_new_compra as $reg =>$val)
		{
			$resultado=$val['codUltimo'];
		}
		
		if(is_null($resultado))
			$NewCodigo='0000001';	
		else
			$NewCodigo=(string)str_pad($resultado+1,7,'0',STR_PAD_LEFT);	
		
		echo $codigo="R.C. N&ordm; ".$NewCodigo;
	}
	else 
		echo "R.C. N&ordm;";		
}


if($_REQUEST['orden_de_descuento'])
{
	$codnegocio	= $_REQUEST['codnegocio'];
	$coddscto	= $_REQUEST['coddscto'];
	if($codnegocio!='0' && $codcobro!='0')
	{
		switch($coddscto)
		{
			case 'D':{$sigla='DVOL';break;};
			case 'S':{$sigla='SLDO';break;};		
			case 'O':{$sigla='OTRO';break;};
		}
		
		$sql_ver_codifNego="select NegCne from Negocio where NegCod ='".$_REQUEST['codnegocio']."'";	
		$dsl_ver_codifNego=$_SESSION['dbmssql']->getAll($sql_ver_codifNego);
		foreach($dsl_ver_codifNego as $val => $value)
		{
			$codificacion=$sigla.$value['NegCne'];
		}
	
		$sql_NuevoDescto = "select  max(CodOrdDscto) as codUltimo 
						 	from    CABORDDSCTO 
						 	where	CodDsctoNeg = '".$_REQUEST['codnegocio']."' ";
						 		///////and TipoCob = '".$codcobro."'	
		$dsl_NuevoDescto=$_SESSION['dbmssql']->getAll($sql_NuevoDescto);
		foreach($dsl_NuevoDescto as $val => $RegDscto)
		{
			$resultado=$RegDscto['codUltimo'];
		}
		
		if(is_null($resultado))
			$NewCodigoDet='0000001';	
		else
			$NewCodigoDet=(string)str_pad($resultado+1,7,'0',STR_PAD_LEFT);	
		
		echo $codigo="O.D. N&ordm; ".$NewCodigoDet."-".$codificacion;
	}
	else 
		echo "O.D. N&ordm;";		
}

//NOTA DE CREDITO COMPRAS
if($_REQUEST['nc_compras'])
{
	$codemp=trim($_REQUEST['codemp']);
	if($codemp!=="0")
	{	
		$sql_codnc="select max(CodOrdNotaCre)as codUltimo from rc.CABNOTACREDITO_COMPRAS 
			where CodNotaEmp='".$codemp."' and Estado not in('E','C') ";	
		$dsl_codnc=$_SESSION['dbmssql']->getAll($sql_codnc);
		foreach($dsl_codnc as $val => $reg)
		{
		    $resultado=$reg['codUltimo'];
		}
		
		if(is_null($resultado))
			$NewCodigo_nc='0000001';
		else
			$NewCodigo_nc=(string)str_pad($resultado+1,7,'0',STR_PAD_LEFT);	
		
		echo "N.C.C. N&ordm; ".$NewCodigo_nc;
	}
	else
	{
		echo "N.C.C. N&ordm;";
	}
}


if($_REQUEST['n_nota_credito'])
{
	
	
	$codnegocio	= $_REQUEST['codnegocio'];
	if($codnegocio!='0' )
	{
		
		
		$sql_ver_codifNego="select NegCne from Negocio where NegCod ='".$codnegocio."'";	
		$dsl_ver_codifNego=$_SESSION['dbmssql']->getAll($sql_ver_codifNego);
		foreach($dsl_ver_codifNego as $val => $value)
		{
			$codificacion=$value['NegCne'];
		}
	
		$sql_NuevoDescto = "select  max(CodOrdNotaCre) as codUltimo 
						 	from    CABNOTACREDITO 
						 	where	CodNotaNeg = '".$codnegocio."' ";
						 		///////and TipoCob = '".$codcobro."'	
		$dsl_NuevoDescto=$_SESSION['dbmssql']->getAll($sql_NuevoDescto);
		foreach($dsl_NuevoDescto as $val => $RegDscto)
		{
			$resultado=$RegDscto['codUltimo'];
		}
		
		if(is_null($resultado))
			$NewCodigoDet='0000001';	
		else
			$NewCodigoDet=(string)str_pad($resultado+1,7,'0',STR_PAD_LEFT);	
		
		echo $codigo="N.C. N&ordm; ".$NewCodigoDet."-".$codificacion;
	}
	else 
		echo "N.C. N&ordm;";
				
}


if($_REQUEST['nota_credito_prof'])
{
	$codnegocio	= $_REQUEST['codnegocio'];
	if($codnegocio!='0' )
	{		
		$sql_ver_codifNego="select NegCne from Negocio where NegCod='".$codnegocio."'";	
		$dsl_ver_codifNego=$_SESSION['dbmssql']->getAll($sql_ver_codifNego);
		foreach($dsl_ver_codifNego as $val => $value)
		{
			$codificacion=$value['NegCne'];
		}
	
		$sql_NuevoDescto="select max(CodOrdNotaCre)as codUltimo 
				from CABNOTACREDITO_PROF 
				where CodNotaNeg='".$codnegocio."'";
		$dsl_NuevoDescto=$_SESSION['dbmssql']->getAll($sql_NuevoDescto);
		foreach($dsl_NuevoDescto as $val => $RegDscto)
		{
			$resultado=$RegDscto['codUltimo'];
		}
		
		if(is_null($resultado))
			$NewCodigoDet='0000001';
		else
			$NewCodigoDet=(string)str_pad($resultado+1,7,'0',STR_PAD_LEFT);	
		
		echo $codigo="N.C. N&ordm; ".$NewCodigoDet."-".$codificacion;
	}
	else 
		echo "N.C. N&ordm;";		
}

if($_REQUEST['nota_debito_prof'])
{
	$codnegocio	= $_REQUEST['codnegocio'];
	if($codnegocio!=='0')
	{		
		$sql_cod_neg="select NegCne from Negocio where NegCod='".$codnegocio."'";	
		$dsl_cod_neg=$_SESSION['dbmssql']->getAll($sql_cod_neg);
		foreach($dsl_cod_neg as $val => $value)
		{
			$codificacion=$value['NegCne'];
		}
	
		$sql_debprof="select max(CodOrdNotaDeb)as codUltimo 
				from CABNOTADEBITO_PROF 
				where CodNotaNeg='".$codnegocio."'";
		$dsl_debprof=$_SESSION['dbmssql']->getAll($sql_debprof);
		foreach($dsl_debprof as $val => $regdeb)
		{
			$resultado=$regdeb['codUltimo'];
		}
		
		if(is_null($resultado))
			$NewCodigo_Deb='0000001';
		else
			$NewCodigo_Deb=(string)str_pad($resultado+1,7,'0',STR_PAD_LEFT);	
		
		echo $codigo="N.D. N&ordm; ".$NewCodigo_Deb."-".$codificacion;
	}
	else 
		echo "N.D. N&ordm;";		
}



if($_REQUEST['n_nota_debito'])
{
	
	
	$codnegocio	= $_REQUEST['codnegocio'];
	if($codnegocio!='0' )
	{
		
		
		$sql_ver_codifNego="select NegCne from Negocio where NegCod ='".$codnegocio."'";	
		$dsl_ver_codifNego=$_SESSION['dbmssql']->getAll($sql_ver_codifNego);
		foreach($dsl_ver_codifNego as $val => $value)
		{
			$codificacion=$value['NegCne'];
		}
	
		$sql_NuevoDescto = "select  max(CodOrdNotaDeb) as codUltimo 
						 	from    CABNOTADEBITO 
						 	where	CodNotaNeg = '".$codnegocio."' ";
						 		///////and TipoCob = '".$codcobro."'	
		$dsl_NuevoDescto=$_SESSION['dbmssql']->getAll($sql_NuevoDescto);
		foreach($dsl_NuevoDescto as $val => $RegDscto)
		{
			$resultado=$RegDscto['codUltimo'];
		}
		
		if(is_null($resultado))
			$NewCodigoDet='0000001';	
		else
			$NewCodigoDet=(string)str_pad($resultado+1,7,'0',STR_PAD_LEFT);	
		
		echo $codigo="N.D. N&ordm; ".$NewCodigoDet."-".$codificacion;
	}
	else 
		echo "N.D. N&ordm;";
		
		
}




////////////////Junio, 2018 ///////////////

//
//if($_REQUEST['ingreso_de_tejido'])
//{
//	
//	///Es tl tipo de Ingreso, se genera un correlativo por TipoIngreso ( TP, TL, TC)
//	$TipoIngreso	= $_REQUEST['TipoIngreso'];
//	if($TipoIngreso!='0' )
//	{
//		
//		
//		$sql_ver_TejAbre = "select TejAbre from INGRESO_TEJIDO where TejCod ='".$TipoIngreso."'";	
//		$dsl_ver_TejAbre = $_SESSION['dbmssql']->getAll($sql_ver_TejAbre);
//		foreach($dsl_ver_TejAbre as $val => $value)
//		{
//			$codificacion=$value['TejAbre'];
//		}
//	
//		$sql_NuevoDescto = "select  max([CodTejIng]) as codUltimo 
//						 	from    CABTEJ_INGRESO 
//						 	where	[TipoIngreso] = '".$TipoIngreso."' ";
//						 		///////and TipoCob = '".$codcobro."'	
//		$dsl_NuevoDescto=$_SESSION['dbmssql']->getAll($sql_NuevoDescto);
//		foreach($dsl_NuevoDescto as $val => $RegDscto)
//		{
//			$resultado=$RegDscto['codUltimo'];
//		}
//		
//		if(is_null($resultado))
//			$NewCodigoDet='0000001';	
//		else
//			$NewCodigoDet=(string)str_pad($resultado+1,7,'0',STR_PAD_LEFT);	
//		
//		echo $codigo="I.T. N&ordm; ".$NewCodigoDet."-".$codificacion;
//	}
//	else 
//		echo "I.T. N&ordm;";
//		
//		
//}
//////////////////////////////////////

if($_REQUEST['orden_de_compra'])
{
	$compania=$_REQUEST['compania'];	
	if($compania!='0')
	{

		$sql_NuevoCodCompra="	select 	max(CodPur) as codUltimo 
								from 	im.PURCHASE_ORDER ";	
		$dsl_NuevoCodCompra=$_SESSION['dbmssql']->getAll($sql_NuevoCodCompra);
		foreach($dsl_NuevoCodCompra as $val => $valor){
			$result=$valor['codUltimo'];
		}
		
		if(is_null($result))
			$NewCodigo='0000001';  //////SETEAR EN TABLA	
		else
			$NewCodigo=(string)str_pad($result+1,7,'0',STR_PAD_LEFT);	
		
		echo $resultado="O.C. N&ordm; ".$NewCodigo;
	}
	else 
		echo "O.C. N&ordm;";	
}





if($_REQUEST['comercial_invoice'])
{
	$anything=$_REQUEST['anything'];	
	if($anything!='')
	{

		$sql_NuevoComercial="	select 	max(CodInvoice) as codUltimo 
								from 	im.CAB_COMERCIAL_INVOICE ";	
		$dsl_NuevoComercial=$_SESSION['dbmssql']->getAll($sql_NuevoComercial);
		foreach($dsl_NuevoComercial as $val => $valor){
			$result=$valor['codUltimo'];
		}
		
		if(is_null($result))
			$NewCodigo='0000001';  //////SETEAR EN TABLA	
		else
			$NewCodigo=(string)str_pad($result+1,7,'0',STR_PAD_LEFT);	
		
		echo $resultado="C.I. ".$NewCodigo;
	}
	else 
		echo "C.I. ";	
}









if($_REQUEST['ingreso_importacion'])
{
	$anything=$_REQUEST['anything'];	
	if($anything!='')
	{

		$sql_NuevoComercial="	select 	max(CodIngreso) as codUltimo 
								from 	im.CAB_INGRESO_IMPORTACION ";	
		$dsl_NuevoComercial=$_SESSION['dbmssql']->getAll($sql_NuevoComercial);
		foreach($dsl_NuevoComercial as $val => $valor){
			$result=$valor['codUltimo'];
		}
		
		if(is_null($result))
			$NewCodigo='0000001';  //////SETEAR EN TABLA	
		else
			$NewCodigo=(string)str_pad($result+1,7,'0',STR_PAD_LEFT);	
		
		echo $resultado="I.I. ".$NewCodigo;
	}
	else 
		echo "I.I. ";	
}








if($_REQUEST['movimiento_importacion'])
{
	$anything=$_REQUEST['anything'];	
	if($anything!='')
	{

		$sql_NuevoComercial="	select 	max(CodMovimiento) as codUltimo 
								from 	im.CAB_MOVIMIENTO_IMPORTACION ";	
		$dsl_NuevoComercial=$_SESSION['dbmssql']->getAll($sql_NuevoComercial);
		foreach($dsl_NuevoComercial as $val => $valor){
			$result=$valor['codUltimo'];
		}
		
		if(is_null($result))
			$NewCodigo='0000001';  //////SETEAR EN TABLA	
		else
			$NewCodigo=(string)str_pad($result+1,7,'0',STR_PAD_LEFT);	
		
		echo $resultado="#M ".$NewCodigo;
	}
	else 
		echo "#M ";	
}


if($_REQUEST['solicitud_compra'])
{
	$compania=$_REQUEST['compania'];	
	if($compania!='0')
	{

		$sql_NuevoCodCompra="	select 	max(CodSC) as codUltimo 
								from 	lg.SOLICITUD_COMPRA ";	
		$dsl_NuevoCodCompra=$_SESSION['dbmssql']->getAll($sql_NuevoCodCompra);
		foreach($dsl_NuevoCodCompra as $val => $valor){
			$result=$valor['codUltimo'];
		}
		
		if(is_null($result))
			$NewCodigo='0000001';  //////SETEAR EN TABLA	
		else
			$NewCodigo=(string)str_pad($result+1,7,'0',STR_PAD_LEFT);	
		
		echo $resultado="S.C. N&ordm; ".$NewCodigo;
	}
	else 
		echo "S.C. N&ordm;";	
}

if($_REQUEST['packing_list'])
{
	$compania=$_REQUEST['compania'];	
	if($compania!='0')
	{

		$sql_NuevoCodPL="select max(CodPL) as codUltimo 
								from [des].[PLIST_CAB]";	
		$dsl_NuevoCodPL=$_SESSION['dbmssql']->getAll($sql_NuevoCodPL);
		//print_r($dsl_NuevoCodPL);
		foreach($dsl_NuevoCodPL as $val => $valorpl){
			$resultpl=$valorpl[codUltimo];
		}
		if(is_null($resultpl))
			$NewCodigopl='0000001';  //////SETEAR EN TABLA	
		else
			$NewCodigopl=(string)str_pad($resultpl+1,7,'0',STR_PAD_LEFT);	
		
		echo $resultadopl="P.L. N&ordm; ".$NewCodigopl;
	}
	else 
		echo "P.L. N&ordm;";	
}


if($_REQUEST['ordencompranacionalnro'])
{
	$compania=$_REQUEST['compania'];	
	if($compania!='0')
	{

		$sql_NuevoCodCompra="select  max(CodOC) as codUltimo 
								from lg.ORDEN_COMPRA_NACIONAL";
		$dsl_NuevoCodCompra=$_SESSION['dbmssql']->getAll($sql_NuevoCodCompra);
		foreach($dsl_NuevoCodCompra as $val => $valor){
			$result=$valor['codUltimo'];
		}
		
		if(is_null($result))
			$NewCodigo='0000001';  //////SETEAR EN TABLA	
		else
			$NewCodigo=(string)str_pad($result+1,7,'0',STR_PAD_LEFT);	
		
		echo $resultado="O.C. N&ordm; ".$NewCodigo;
	}
	else 
		echo "O.C. N&ordm;";	
}


//packing list tienda
if($_REQUEST['packing_list_tienda'])
{
	$compania=$_REQUEST['compania'];	
	if($compania!='0')
	{

		$sql_NuevoCodPL="select max(CodPL) as codUltimo 
								from des.PLIST_CAB_TIENDA";	
		$dsl_NuevoCodPL=$_SESSION['dbmssql']->getAll($sql_NuevoCodPL);
		//print_r($dsl_NuevoCodPL);
		foreach($dsl_NuevoCodPL as $val => $valorpl){
			$resultpl=$valorpl['codUltimo'];
		}
		if(is_null($resultpl))
			$NewCodigopl='0000001';  //////SETEAR EN TABLA	
		else
			$NewCodigopl=(string)str_pad($resultpl+1,7,'0',STR_PAD_LEFT);	
		
		echo $resultadopl="P.L. N&ordm; ".$NewCodigopl;
	}
	else 
		echo "P.L. N&ordm;";	
}

if($_REQUEST['orden_de_guiatransp'])
{
	$codemp	 = $_REQUEST['codemp'];
	$codserv = $_REQUEST['codserv'];

	if($codemp!=="0")
	{	
		//OBTIENE CODSERIE
		$sql_codserie="select codSerie from NrosdeSerie where estado='0' and codEmpresa='".$codemp."' and codTipoDoc='6' ";
		$dsl_codserie=$_SESSION['dbmssql']->getAll($sql_codserie);
		foreach($dsl_codserie as $val => $dato) 
		{
			$codserie=$dato['codSerie'];
		}

		//OBTIENE NUMERO DE GUIA ELETRONICA
		$sql_NuevoCodIngreso="select [dbo].FN_NumeroDocumentoTranspGE(".trim($codemp).", ".trim($codserie).") as codUltimo";
		$dsl_NuevoCodIngreso=$_SESSION['dbmssql']->getAll($sql_NuevoCodIngreso);
		foreach ($dsl_NuevoCodIngreso as $val => $ing) 
		{
			$result=$ing['codUltimo'];
		}
		echo $result;

	}
	else 
		echo "0";	
}


?>
