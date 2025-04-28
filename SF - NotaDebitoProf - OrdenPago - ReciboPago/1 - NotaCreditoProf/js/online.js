// JavaScript Document

	 window.setInterval("actualizacion_reloj()", 10000); // el tiempo X que tardar� en actualizarse  
 
 
function llamadaCodCompra() 
{  
 	if(!document.getElementById("orden_de_servicio"))	return;	
	if($F("obra_cod")=='0') return;
	
 	var codobra= document.form1.obra_cod.value;
	var Destino =document.getElementById("orden_de_servicio");
	ajax_con_compra=objetoAjax();
	ajax_con_compra.open("POST","includes/motoronline.php", true);	
	ajax_con_compra.onreadystatechange=function()
	{
		if (ajax_con_compra.readyState==1)
		{
	    	Destino.innerHTML="Actualizando...";
	    }
	    else if (ajax_con_compra.readyState==4)
		{
			Destino.innerHTML = ajax_con_compra.responseText;
	  	}
	 }
	 ajax_con_compra.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	 ajax_con_compra.send("orden_de_servicio=1&codigoObra="+codobra);
}  


   function actualizacion_reloj() {  
   llamadaCodCompra();  
   
   }  
   
   
   /////////////////////////
   
   
   
   // JavaScript Document

 window.setInterval("actualizacion_clock()", 10000); // el tiempo X que tardar� en actualizarse  
 
 
function llamadaCodIngreso() 
{  
 	if(!document.getElementById("orden_de_despacho"))	return;	
	//Nombre del
	if($F("obraCodIng")=='0') return;
	
 	var codobra= document.form1.obraCodIng.value;
	var Destino =document.getElementById("orden_de_despacho");
	ajax_on_ingreso=objetoAjax();
	ajax_on_ingreso.open("POST","includes/motoronline.php", true);	
	ajax_on_ingreso.onreadystatechange=function()
	{
		if (ajax_on_ingreso.readyState==1)
		{
	    	Destino.innerHTML="Actualizando...";
	    }
	    else if (ajax_on_ingreso.readyState==4)
		{
			Destino.innerHTML = ajax_on_ingreso.responseText;
	  	}
	 }
	 ajax_on_ingreso.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	 ajax_on_ingreso.send("orden_de_despacho=1&codigoObra="+codobra);
	 ver_guias();
}  


   function actualizacion_clock() {  
   llamadaCodIngreso();  
   
   }  
   
function ver_guias()
{
	if($F("obraCodIng")=='0') return;
	if($F("ing_codorden")=='') return;
	if($F("ing_codorden")=='0') return;
	
 	var codobra= document.form1.obraCodIng.value;
 	var codServicio= document.form1.ing_codorden.value;
 	//alert(codServicio);
	ajax_on_guias=objetoAjax();
	ajax_on_guias.open("POST","includes/motoronline.php", true);	
	ajax_on_guias.onreadystatechange=function()
	{
		if (ajax_on_guias.readyState==1)
		{
	    	//Destinos.Value="Actualizando...";
	    	if(document.getElementById("txt_guiadespacho"))
	    	{
	    		document.getElementById("txt_guiadespacho").value='';
	    	}
	    }
	    else if (ajax_on_guias.readyState==4)
		{
			if(document.getElementById("txt_guiadespacho"))
			{
				document.getElementById("txt_guiadespacho").value=ajax_on_guias.responseText;
			}
	  	}
	 }
	 ajax_on_guias.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	 ajax_on_guias.send("orden_de_gelectro=1&codigoObra="+codobra+"&CodServi="+codServicio);
}
   
function ver_guias_DPL(){

	if($F("cbempresa")=='0') return;
 	var codempresa= document.getElementById("cbempresa").value;

	ajax_on_guias=objetoAjax();
	ajax_on_guias.open("POST","includes/motoronline.php", true);	
	ajax_on_guias.onreadystatechange=function()
	{
		if (ajax_on_guias.readyState==1)
		{
	    	if(document.getElementById("txt_guiadespacho"))
	    	{
	    		document.getElementById("txt_guiadespacho").value='';
	    	}
	    }
	    else if (ajax_on_guias.readyState==4)
		{
			if(document.getElementById("txt_guiadespacho"))
			{
				document.getElementById("txt_guiadespacho").value=ajax_on_guias.responseText;
			}
	  	}
	 }
	 ajax_on_guias.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	 ajax_on_guias.send("orden_de_gelectrodpl=1&codigoempresa="+codempresa);
}

   /////////////////////////
   
   
   // JavaScript Document

 window.setInterval("actualizacion_reloj_vales()", 10000); // el tiempo X que tardar� en actualizarse  
 
 
function llamadaCodFactura() 
{  
 	if(!document.getElementById("orden_de_factura"))	return;	
	if($F("codObraFac")=='0') return;
	
	
	
 	var codobra= document.getElementById("codObraFac").value;
	var Destino =document.getElementById("orden_de_factura");
	ajax_on_vales=objetoAjax();
	ajax_on_vales.open("POST","includes/motoronline.php", true);	
	ajax_on_vales.onreadystatechange=function()
	{
		if (ajax_on_vales.readyState==1)
		{
	    	Destino.innerHTML="Actualizando...";
	    }
	    else if (ajax_on_vales.readyState==4)
		{
			Destino.innerHTML = ajax_on_vales.responseText;
	  	}
	 }
	 ajax_on_vales.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	 ajax_on_vales.send("orden_de_factura=1&codigoObra="+codobra);
}  


   function actualizacion_reloj_vales() {  
   llamadaCodFactura();  
   
   }  
   
   
   
   //////////////////////////////
   
   
   // JavaScript Document

 window.setInterval("reloj_proforma()", 10000); // el tiempo X que tardar� en actualizarse  
 
 
function llamadaCodProforma() 
{  
 	if(!document.getElementById("orden_de_proforma"))	return;	
	if($F("codObraProf")=='0') return;
	
	
	
	
 	var codobra= document.getElementById("codObraProf").value;
	var Destino =document.getElementById("orden_de_proforma");
	ajax_on_salido=objetoAjax();
	ajax_on_salido.open("POST","includes/motoronline.php", true);	
	ajax_on_salido.onreadystatechange=function()
	{
		if (ajax_on_salido.readyState==1)
		{
	    	Destino.innerHTML="Actualizando...";
	    }
	    else if (ajax_on_salido.readyState==4)
		{
			Destino.innerHTML = ajax_on_salido.responseText;
	  	}
	 }
	 ajax_on_salido.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	 ajax_on_salido.send("orden_de_proforma=1&codigoObra="+codobra);
}  


   function reloj_proforma() {  
   llamadaCodProforma();  
   
   }  
   
   
   
   
   ///generar pago
   
 window.setInterval("reloj_pago()", 10000); // el tiempo X que tardar� en actualizarse  
 
 
 function actualizar_pl() 
{  
		ver_numero_pl();    
}

function llamadaGenerarPago() 
{  
 	if(!document.getElementById("orden_de_pago"))	return;	
	if($F("codNegocio")=='0') return;	
	if($F("tipo_cobro")=='0') return;
	
	
 	var codnegocio	= document.getElementById("codNegocio").value;
 	var codcobro	= document.getElementById("tipo_cobro").value;
	
	var Destino =document.getElementById("orden_de_pago");
	ajax_orden_pago=objetoAjax();
	ajax_orden_pago.open("POST","includes/motoronline.php", true);	
	ajax_orden_pago.onreadystatechange=function()
	{
		if (ajax_orden_pago.readyState==1)
		{
	    	Destino.innerHTML="Actualizando...";
	    }
	    else if (ajax_orden_pago.readyState==4)
		{
			Destino.innerHTML = ajax_orden_pago.responseText;
	  	}
	 }
	 ajax_orden_pago.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	 ajax_orden_pago.send("orden_de_pago=1&codnegocio="+codnegocio+"&codcobro="+codcobro);
}  


   function reloj_pago() {  
   llamadaGenerarPago();  
   
   }  
   
//   regostro de pago
   
 window.setInterval("registra_pago()", 10000); // el tiempo X que tardar� en actualizarse  

 
function llamadaRegistrarPago() 
{  
 	if(!document.getElementById("orden_de_registro"))	return;	
	if($F("codNegocio")=='0') return;	
	if($F("tipo_cobro")=='0') return;
	
	
 	var codnegocio	= document.getElementById("codNegocio").value;
 	var codcobro	= document.getElementById("tipo_cobro").value;
	
	var Destino =document.getElementById("orden_de_registro");
	ajax_orden_pago=objetoAjax();
	ajax_orden_pago.open("POST","includes/motoronline.php", true);	
	ajax_orden_pago.onreadystatechange=function()
	{
		if (ajax_orden_pago.readyState==1)
		{
	    	Destino.innerHTML="Actualizando...";
	    }
	    else if (ajax_orden_pago.readyState==4)
		{
			Destino.innerHTML = ajax_orden_pago.responseText;
	  	}
	 }
	 ajax_orden_pago.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	 ajax_orden_pago.send("orden_de_registro=1&codnegocio="+codnegocio+"&codcobro="+codcobro);
}  

   function registra_pago() 
   {  
	llamadaRegistrarPago();  
   } 

   function registra_pago_compras() 
   {  
		if(!document.getElementById("registro_pago_compras")) return;	
	   
		var codemp	= document.getElementById("cb_emp").value;
		var Destino = document.getElementById("registro_pago_compras");
		ajax_rpc=objetoAjax();
		ajax_rpc.open("POST","includes/motoronline.php", true);	
		ajax_rpc.onreadystatechange=function()
		{
			if(ajax_rpc.readyState==1)
			{
				Destino.innerHTML="Actualizando...";
		   	}
		   	else if(ajax_rpc.readyState==4)
		   	{
				Destino.innerHTML = ajax_rpc.responseText;
			}
		}
		ajax_rpc.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajax_rpc.send("registro_pago_compras=1&codemp="+codemp);
   }  

   window.setInterval("registra_pago_compras()", 10000); // el tiempo X que tardar� en actualizarse  



   function liqui_cobranza_compras() 
   {  
		if(!document.getElementById("liquida_cobranza")) return;	
	   
		var codemp	= document.getElementById("cb_emp").value;
		var Destino = document.getElementById("liquida_cobranza");
		ajax_liqui=objetoAjax();
		ajax_liqui.open("POST","includes/motoronline.php", true);	
		ajax_liqui.onreadystatechange=function()
		{
			if(ajax_liqui.readyState==1)
			{
				Destino.innerHTML="Actualizando...";
		   	}
		   	else if(ajax_liqui.readyState==4)
		   	{
				Destino.innerHTML = ajax_liqui.responseText;
			}
		}
		ajax_liqui.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajax_liqui.send("liqui_cobranza=1&codemp="+codemp);
   }  

   window.setInterval("liqui_cobranza_compras()", 10000); // el tiempo X que tardar� en actualizarse  


   function registra_detraccion_compras() 
   {  
		if(!document.getElementById("detraccion_compras")) return;	
		if($F("codNegocio")=='0') return;	
		if($F("tipo_cobro")=='0') return;
	   
		var codnegocio = document.getElementById("codNegocio").value;
		var codcobro   = document.getElementById("tipo_cobro").value;
		var Destino	   = document.getElementById("detraccion_compras");

		ajax_detrac=objetoAjax();
		ajax_detrac.open("POST","includes/motoronline.php", true);	
		ajax_detrac.onreadystatechange=function()
		{
			if(ajax_detrac.readyState==1)
			{
				Destino.innerHTML="Actualizando...";
			}
			else if(ajax_detrac.readyState==4)
			{
				Destino.innerHTML = ajax_detrac.responseText;
			}
		}
		ajax_detrac.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajax_detrac.send("detraccion_compras=1&codnegocio="+codnegocio+"&codcobro="+codcobro);
   }  

   window.setInterval("registra_detraccion_compras()", 10000); // el tiempo X que tardar� en actualizarse  

   

//   registro de detraccion
window.setInterval("registra_detraccion()", 10000); // el tiempo X que tardar� en actualizarse  
 
function llamadaReg_Detrac() 
{  
 	if(!document.getElementById("orden_de_detraccion"))	return;	
	if($F("codNegocio")=='0') return;	
	if($F("tipo_cobro")=='0') return;
	
 	var codnegocio	= document.getElementById("codNegocio").value;
 	var codcobro	= document.getElementById("tipo_cobro").value;
	
	var Destino =document.getElementById("orden_de_detraccion");
	ajax_orden_pago=objetoAjax();
	ajax_orden_pago.open("POST","includes/motoronline.php", true);	
	ajax_orden_pago.onreadystatechange=function()
	{
		if (ajax_orden_pago.readyState==1)
		{
	    	Destino.innerHTML="Actualizando...";
	    }
	    else if (ajax_orden_pago.readyState==4)
		{
			Destino.innerHTML = ajax_orden_pago.responseText;
	  	}
	 }
	 ajax_orden_pago.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	 ajax_orden_pago.send("orden_de_detraccion=1&codnegocio="+codnegocio+"&codcobro="+codcobro);
}  

function registra_detraccion()
{  
	llamadaReg_Detrac();    
} 


////// registro de autodetraccion /////
window.setInterval("registra_autodetraccion()", 10000); // el tiempo X que tardar� en actualizarse  

function llamadaReg_AutoDetrac() 
{  
 	if(!document.getElementById("orden_autodetra"))	return;	
	if($F("codNegocio")=='0') return;	
	if($F("tipo_cobro")=='0') return;
	
 	var codnegocio	= document.getElementById("codNegocio").value;
 	var codcobro	= document.getElementById("tipo_cobro").value;	
	var Destino=document.getElementById("orden_autodetra");
	ajax_autodetra=objetoAjax();
	ajax_autodetra.open("POST","includes/motoronline.php", true);	
	ajax_autodetra.onreadystatechange=function()
	{
		if (ajax_autodetra.readyState==1)
		{
	    	Destino.innerHTML="Actualizando...";
	    }
	    else if (ajax_autodetra.readyState==4)
		{
			Destino.innerHTML = ajax_autodetra.responseText;
	  	}
	 }
	 ajax_autodetra.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	 ajax_autodetra.send("orden_autodetra=1&codnegocio="+codnegocio+"&codcobro="+codcobro);
} 

function registra_autodetraccion()
{  
	llamadaReg_AutoDetrac();
} 
	

function codigo_reg_compras()
{
	if(!document.getElementById("registro_compras")) return;	
	var cod_emp= document.getElementById("cb_emp").value;
	
	if(cod_emp=="0") return;
	
	var Destino=document.getElementById("registro_compras");

	ajax_regcompra=objetoAjax();
	ajax_regcompra.open("POST","includes/motoronline.php", true);	
	ajax_regcompra.onreadystatechange=function()
	{
		if(ajax_regcompra.readyState==1)
		{
	    	Destino.innerHTML="Actualizando...";
	    }
	    else if(ajax_regcompra.readyState==4)
		{
			Destino.innerHTML = ajax_regcompra.responseText;
	  	}
	 }
	 ajax_regcompra.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	 ajax_regcompra.send("registro_compras=1&cod_emp="+cod_emp);
}
	
window.setInterval("codigo_reg_compras()",10000);
	
	
//   descuentossss
window.setInterval("reg_descuentos()", 10000); // el tiempo X que tardar� en actualizarse  
 
function llamada_Descuentos() 
{  
 	if(!document.getElementById("orden_de_descuento"))	return;	
	if($F("codNegocio")=='0') return;	
	if($F("tipo_dscto")=='0') return;
	
 	var codnegocio	= document.getElementById("codNegocio").value;
 	var coddscto	= document.getElementById("tipo_dscto").value;
	
	var Destino =document.getElementById("orden_de_descuento");
	ajax_orden_dscto=objetoAjax();
	ajax_orden_dscto.open("POST","includes/motoronline.php", true);	
	ajax_orden_dscto.onreadystatechange=function()
	{
		if (ajax_orden_dscto.readyState==1)
		{
	    	Destino.innerHTML="Actualizando...";
	    }
	    else if (ajax_orden_dscto.readyState==4)
		{
			Destino.innerHTML = ajax_orden_dscto.responseText;
	  	}
	 }
	 ajax_orden_dscto.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	 ajax_orden_dscto.send("orden_de_descuento=1&codnegocio="+codnegocio+"&coddscto="+coddscto);
}  


	function reg_descuentos()
   	{  
		llamada_Descuentos();    
   	} 	


function llamada_nc_compras() 
{  
 	if(!document.getElementById("nota_credito_compras")) return;	
	if(document.getElementById("codemp").value=='0') return;	
	
 	var codemp	= document.getElementById("codemp").value;
	var Destino = document.getElementById("nota_credito_compras");
	ajax_nc_compras=objetoAjax();
	ajax_nc_compras.open("POST","includes/motoronline.php", true);	
	ajax_nc_compras.onreadystatechange=function()
	{
		if (ajax_nc_compras.readyState==1)
		{
	    	Destino.innerHTML="Actualizando...";
	    }
	    else if (ajax_nc_compras.readyState==4)
		{
			Destino.innerHTML = ajax_nc_compras.responseText;
	  	}
	 }
	 ajax_nc_compras.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	 ajax_nc_compras.send("nc_compras=1&codemp="+codemp);
} 

//NOTA DE CREDITO COMPRAS
window.setInterval("llamada_nc_compras()", 10000); // el tiempo X que tardar� en actualizarse  
	
	
	

//   NOTA DE CREDITO
window.setInterval("reg_nota_credito()", 10000); // el tiempo X que tardar� en actualizarse  
	
function llamadaCodNotaCredito() 
{  
 	if(!document.getElementById("nota_credito"))	return;	
	if(document.getElementById("codNegocio").value=='0') return;	
	
 	var codnegocio	= document.getElementById("codNegocio").value;
	
	var Destino =document.getElementById("nota_credito");
	ajax_nota_credito=objetoAjax();
	ajax_nota_credito.open("POST","includes/motoronline.php", true);	
	ajax_nota_credito.onreadystatechange=function()
	{
		if (ajax_nota_credito.readyState==1)
		{
	    	Destino.innerHTML="Actualizando...";
	    }
	    else if (ajax_nota_credito.readyState==4)
		{
			Destino.innerHTML = ajax_nota_credito.responseText;
	  	}
	 }
	 ajax_nota_credito.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	 ajax_nota_credito.send("n_nota_credito=1&codnegocio="+codnegocio);
}  

function reg_nota_credito()
{  
	llamadaCodNotaCredito();    
} 	

	
	
//   NOTA DE DEBITO
	
window.setInterval("reg_nota_debito()", 10000); // el tiempo X que tardar� en actualizarse  
	
function llamadaCodNotaDebito() 
{  
 	if(!document.getElementById("nota_debito"))	return;	
	if(document.getElementById("codNegocio").value=='0') return;	
	
 	var codnegocio	= document.getElementById("codNegocio").value;
	
	var Destino =document.getElementById("nota_debito");
	ajax_nota_debito=objetoAjax();
	ajax_nota_debito.open("POST","includes/motoronline.php", true);	
	ajax_nota_debito.onreadystatechange=function()
	{
		if (ajax_nota_debito.readyState==1)
		{
	    	Destino.innerHTML="Actualizando...";
	    }
	    else if (ajax_nota_debito.readyState==4)
		{
			Destino.innerHTML = ajax_nota_debito.responseText;
	  	}
	 }
	 ajax_nota_debito.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	 ajax_nota_debito.send("n_nota_debito=1&codnegocio="+codnegocio);
}  


	function reg_nota_debito()
   	{  
		llamadaCodNotaDebito();    
   	} 	

	
//////////////////////////Junio, 2018/////////////////////////////////	
window.setInterval("refresh_ing_tej()", 10000); // el tiempo X que tardar� en actualizarse  



//NOTA DE CREDITO DE PROFORMA
function llamadaCodNotaCredito_prof() 
{  
 	if(!document.getElementById("nota_credito_prof")) return;	
	if(document.getElementById("codNegocio").value=='0') return;	
	
 	var codnegocio	= document.getElementById("codNegocio").value;
	
	var Destino =document.getElementById("nota_credito_prof");
	ajax_nota_credito=objetoAjax();
	ajax_nota_credito.open("POST","includes/motoronline.php", true);	
	ajax_nota_credito.onreadystatechange=function()
	{
		if (ajax_nota_credito.readyState==1)
		{
	    	Destino.innerHTML="Actualizando...";
	    }
	    else if (ajax_nota_credito.readyState==4)
		{
			Destino.innerHTML = ajax_nota_credito.responseText;
	  	}
	 }
	 ajax_nota_credito.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	 ajax_nota_credito.send("nota_credito_prof=1&codnegocio="+codnegocio);
}  

window.setInterval("llamadaCodNotaCredito_prof()", 10000); // el tiempo X que tardara en actualizarse  


//NOTA DE DEBITO DE PROFORMA
function llamadaCodNotaDebito_prof() 
{  
 	if(!document.getElementById("nota_debito_prof")) return;	
	if(document.getElementById("codNegocio").value=='0') return;	
	
 	var codnegocio	= document.getElementById("codNegocio").value;
	
	var Destino=document.getElementById("nota_debito_prof");
	ajax_nota_debito=objetoAjax();
	ajax_nota_debito.open("POST","includes/motoronline.php", true);	
	ajax_nota_debito.onreadystatechange=function()
	{
		if(ajax_nota_debito.readyState==1)
		{
	    	Destino.innerHTML="Actualizando...";
	    }
	    else if(ajax_nota_debito.readyState==4)
		{
			Destino.innerHTML = ajax_nota_debito.responseText;
	  	}
	 }
	 ajax_nota_debito.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	 ajax_nota_debito.send("nota_debito_prof=1&codnegocio="+codnegocio);
}  

window.setInterval("llamadaCodNotaDebito_prof()", 10000); // el tiempo X que tardara en actualizarse  


	
	
function llamada_Ingreso_Tejido() 
{  
 	if(!document.getElementById("ingreso_de_tejido"))	return;	
	if($F("tipo_tejido")=='0') return;
	
 	var TipoIngreso	= document.getElementById("tipo_tejido").value;
	var Destino 	= document.getElementById("ingreso_de_tejido");
	ajax_con_Tejido=objetoAjax();
	ajax_con_Tejido.open("POST","includes/motoronline.php", true);	
	ajax_con_Tejido.onreadystatechange=function()
	{
		if (ajax_con_Tejido.readyState==1)
		{
	    	Destino.innerHTML="Actualizando...";
	    }
	    else if (ajax_con_Tejido.readyState==4)
		{
			Destino.innerHTML = ajax_con_Tejido.responseText;
	  	}
	 }
	 ajax_con_Tejido.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	 ajax_con_Tejido.send("ingreso_de_tejido=1&TipoIngreso="+TipoIngreso);
}  


   function refresh_ing_tej() 
   {  
   		llamada_Ingreso_Tejido();    
   }  
   
   
   /////////////////////////
   
	
	
	 
	
//////////////////////////SEPTIEMBRE, 2020/////////////////////////////////	
window.setInterval("refrech_purchase_order()", 10000); // el tiempo X que tardar� en actualizarse  
	
	
function llamada_Orden_Compra() 
{  
 	if(!document.getElementById("orden_de_compra"))	return;	
	if(document.getElementById("entidad").value=='0')
	{
		document.getElementById("orden_de_compra").innerHTML="O.C. N&ordm;"; return;
	} 
	
	var DestinoPur = document.getElementById("orden_de_compra");
	
 	var compania = document.getElementById("entidad").value;
	ajax_OrdenPur=objetoAjax();
	ajax_OrdenPur.open("POST","includes/motoronline.php", true);	
	ajax_OrdenPur.onreadystatechange=function()
	{
		if (ajax_OrdenPur.readyState==1)
		{
	    	DestinoPur.innerHTML="Actualizando...";
	    }
	    else if (ajax_OrdenPur.readyState==4)
		{
			DestinoPur.innerHTML = ajax_OrdenPur.responseText;
	  	}
	 }
	 ajax_OrdenPur.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	 ajax_OrdenPur.send("orden_de_compra=1&compania="+compania);
}  


	

function refrech_purchase_order() 
{  
		llamada_Orden_Compra();    
}  

 function actualizar_sc() 
{  
		ver_numero_sc();    
}    

 function actualizar_sc() 
{  
		ver_numero_sc();    
}    

 function actualizar_pl() 
{  
		ver_numero_pl();    
}


function actualizar_osod() 
{  
	ver_numero_os();
	ver_numero_od();
	ver_numero_proforma();
	ver_numero_factura();
}

function act_codigo_mp_importa()
{
	if(!document.getElementById("codigo_ingresomp")) return;	
	
	if(document.getElementById("txt_ci").value=="" || document.getElementById("txt_ci").value=="0")
	{
		document.getElementById("codigo_ingresomp").innerHTML="I.M. N&ordm;"; return;
	} 
	
	var DestinoIMP = document.getElementById("codigo_ingresomp");
 	var xorden = document.getElementById("txt_ci").value;

	ajax_mp=objetoAjax();
	ajax_mp.open("POST","includes/motoronline.php", true);	
	ajax_mp.onreadystatechange=function()
	{
		if (ajax_mp.readyState==1)
		{
	    	DestinoIMP.innerHTML="Actualizando...";
	    }
	    else if (ajax_mp.readyState==4)
		{
			DestinoIMP.innerHTML = ajax_mp.responseText;
	  	}
	 }
	 ajax_mp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	 ajax_mp.send("ingreso_materiaprima=1&codigo_oc="+xorden);
}



function act_ingreso_materiaprima()
{
	if(!document.getElementById("ingreso_materiaprima")) return;	
	
	if(document.getElementById("txt_cn").value=="" || document.getElementById("txt_cn").value=="0")
	{
		document.getElementById("ingreso_materiaprima").innerHTML="I.M. N&ordm;"; return;
	} 
	
	var DestinoIMP = document.getElementById("ingreso_materiaprima");
 	var xorden = document.getElementById("txt_cn").value;

	ajax_mp=objetoAjax();
	ajax_mp.open("POST","includes/motoronline.php", true);	
	ajax_mp.onreadystatechange=function()
	{
		if (ajax_mp.readyState==1)
		{
	    	DestinoIMP.innerHTML="Actualizando...";
	    }
	    else if (ajax_mp.readyState==4)
		{
			DestinoIMP.innerHTML = ajax_mp.responseText;
	  	}
	 }
	 ajax_mp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	 ajax_mp.send("ingreso_materiaprima=1&codigo_oc="+xorden);
}


function act_salida_hilos()
{
	if(!document.getElementById("salida_hilos")) return;	
	if(document.getElementById("cborigen").value=='0')
	{
		document.getElementById("salida_hilos").innerHTML="S.H. N&ordm;"; return;
	} 
	
	var DestinoIMP = document.getElementById("salida_hilos");
 	var almacen_origen = document.getElementById("cborigen").value;

	ajax_smp=objetoAjax();
	ajax_smp.open("POST","includes/motoronline.php", true);	
	ajax_smp.onreadystatechange=function()
	{
		if (ajax_smp.readyState==1)
		{
	    	DestinoIMP.innerHTML="Actualizando...";
	    }
	    else if (ajax_smp.readyState==4)
		{
			DestinoIMP.innerHTML = ajax_smp.responseText;
	  	}
	 }
	 ajax_smp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	 ajax_smp.send("salida_hilos=1&almacen_origen="+almacen_origen);
}

function act_recibo_pago()
{	
	if(!document.getElementById("recibo_pago")) return;
	var codemp = document.getElementById("cbempresa").value;
	
	if(document.getElementById("cbempresa").value=='0')
	{
		document.getElementById("recibo_pago").innerHTML=""; 
		return;
	}
	
	var Destino = document.getElementById("recibo_pago");
	ajax_recibo=objetoAjax();
	ajax_recibo.open("POST","includes/motoronline.php", true);	
	ajax_recibo.onreadystatechange=function()
	{
		if(ajax_recibo.readyState==1)
		{
			Destino.innerHTML="Actualizando...";
		}
		else if(ajax_recibo.readyState==4)
		{
			Destino.innerHTML = ajax_recibo.responseText;
		}
	}
	ajax_recibo.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax_recibo.send("recibo_pago=1&codemp="+codemp);
}


function act_salida_tienda()
{
	if(!document.getElementById("salida_tienda")) return;	
	if(document.getElementById("cbtraslado").value=='0')
	{
		document.getElementById("salida_tienda").innerHTML="S.T. N&ordm;"; 
		return;
	} 
	
	var DestinoIMP = document.getElementById("salida_tienda");
 	var cod_traslado = document.getElementById("cbtraslado").value;
	ajax_st=objetoAjax();
	ajax_st.open("POST","includes/motoronline.php", true);	
	ajax_st.onreadystatechange=function()
	{
		if (ajax_st.readyState==1)
		{
	    	DestinoIMP.innerHTML="Actualizando...";
	    }
	    else if (ajax_st.readyState==4)
		{
			DestinoIMP.innerHTML = ajax_st.responseText;
	  	}
	 }
	 ajax_st.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	 ajax_st.send("salida_tienda=1&cod_traslado="+cod_traslado);
}

function act_devol_hilos()
{
	if(!document.getElementById("devolucion_hilos")) return;	
	if(document.getElementById("cbtraslado").value=='0')
	{
		document.getElementById("devolucion_hilos").innerHTML="D.H. N&ordm;"; return;
	} 
	
	var DestinoIMP = document.getElementById("devolucion_hilos");
 	var codtraslado = document.getElementById("cbtraslado").value;

	ajax_smp=objetoAjax();
	ajax_smp.open("POST","includes/motoronline.php", true);	
	ajax_smp.onreadystatechange=function()
	{
		if (ajax_smp.readyState==1)
		{
	    	DestinoIMP.innerHTML="Actualizando...";
	    }
	    else if (ajax_smp.readyState==4)
		{
			DestinoIMP.innerHTML = ajax_smp.responseText;
	  	}
	 }
	 ajax_smp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	 ajax_smp.send("devol_hilos=1&codtraslado="+codtraslado);
}

function act_devol_tienda()
{
	if(!document.getElementById("devolucion_tienda")) return;	
	if(document.getElementById("cbtraslado").value=="0")
	{
		document.getElementById("devolucion_tienda").innerHTML="D.T. N&ordm;"; return;
	} 
	
	var DestinoIMP 	= document.getElementById("devolucion_tienda");
 	var codtraslado = document.getElementById("cbtraslado").value;
	ajax_smp=objetoAjax();
	ajax_smp.open("POST","includes/motoronline.php", true);	
	ajax_smp.onreadystatechange=function()
	{
		if (ajax_smp.readyState==1)
		{
	    	DestinoIMP.innerHTML="Actualizando...";
	    }
	    else if (ajax_smp.readyState==4)
		{
			DestinoIMP.innerHTML = ajax_smp.responseText;
	  	}
	 }
	 ajax_smp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	 ajax_smp.send("devol_tienda=1&codtraslado="+codtraslado);
}

function act_devol_quimicos()
{
	if(!document.getElementById("devolucion_quimicos")) return;	
	if(document.getElementById("cbtraslado").value=='0')
	{
		document.getElementById("devolucion_quimicos").innerHTML="D.Q. N&ordm;"; return;
	} 
	
	var DestinoIMP = document.getElementById("devolucion_quimicos");
 	var codtraslado = document.getElementById("cbtraslado").value;

	ajax_smp=objetoAjax();
	ajax_smp.open("POST","includes/motoronline.php", true);	
	ajax_smp.onreadystatechange=function()
	{
		if (ajax_smp.readyState==1)
		{
	    	DestinoIMP.innerHTML="Actualizando...";
	    }
	    else if (ajax_smp.readyState==4)
		{
			DestinoIMP.innerHTML = ajax_smp.responseText;
	  	}
	 }
	 ajax_smp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	 ajax_smp.send("devol_quimicos=1&codtraslado="+codtraslado);
}


function act_cotiza_clientes()
{
	if(!document.getElementById("cotiza_clientes")) return;	
	if(document.getElementById("cbempresa").value=='0')
	{
		document.getElementById("cotiza_clientes").innerHTML="C.C. N&ordm;"; return;
	} 
	
	var DestinoIMP = document.getElementById("cotiza_clientes");
 	var codemp = document.getElementById("cbempresa").value;

	ajax_coti=objetoAjax();
	ajax_coti.open("POST","includes/motoronline.php", true);	
	ajax_coti.onreadystatechange=function()
	{
		if (ajax_coti.readyState==1)
		{
	    	DestinoIMP.innerHTML="Actualizando...";
	    }
	    else if (ajax_coti.readyState==4)
		{
			DestinoIMP.innerHTML = ajax_coti.responseText;
	  	}
	 }
	 ajax_coti.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	 ajax_coti.send("cotiza_clientes=1&codemp="+codemp);
}

function act_salida_quimicos()
{
	if(!document.getElementById("salida_quimicos")) return;	
	if(document.getElementById("cborigen").value=='0')
	{
		document.getElementById("salida_quimicos").innerHTML="S.Q. N&ordm;"; return;
	} 
	
	var DestinoIMP = document.getElementById("salida_quimicos");
 	var almacen_origen = document.getElementById("cborigen").value;

	ajax_smp=objetoAjax();
	ajax_smp.open("POST","includes/motoronline.php", true);	
	ajax_smp.onreadystatechange=function()
	{
		if (ajax_smp.readyState==1)
		{
	    	DestinoIMP.innerHTML="Actualizando...";
	    }
	    else if (ajax_smp.readyState==4)
		{
			DestinoIMP.innerHTML = ajax_smp.responseText;
	  	}
	 }
	 ajax_smp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	 ajax_smp.send("salida_quimicos=1&almacen_origen="+almacen_origen);
}

function act_devol_prodt()
{
	if(!document.getElementById("devol_prodt")) return;	
	if(document.getElementById("cbtraslado").value=='0')
	{
		document.getElementById("devol_prodt").innerHTML="D.P. N&ordm;"; return;
	} 

	var DestinoIMP = document.getElementById("devol_prodt");
	ajax_smp=objetoAjax();
	ajax_smp.open("POST","includes/motoronline.php", true);	
	ajax_smp.onreadystatechange=function()
	{
		if (ajax_smp.readyState==1)
		{
	    	DestinoIMP.innerHTML="Actualizando...";
	    }
	    else if (ajax_smp.readyState==4)
		{
			DestinoIMP.innerHTML = ajax_smp.responseText;
	  	}
	 }
	 ajax_smp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	 ajax_smp.send("devol_prodt=1");
}


function actualizar_ordencompranaci() 
{  
		ver_numero_ocnacional();    
}   
 
function ver_numero_os() 
{  
 	if(!document.getElementById("orden_de_serviciopl")) return;	
	if(document.getElementById("cbnegocio").value=='0')
	{
		document.getElementById("orden_de_serviciopl").innerHTML="O.S. N&ordm;"; return;
	} 
	
	var DestinoOS = document.getElementById("orden_de_serviciopl");
 	var xnegocio = document.getElementById("cbnegocio").value;
	ajax_OrdenOS=objetoAjax();
	ajax_OrdenOS.open("POST","includes/motoronline.php", true);	
	ajax_OrdenOS.onreadystatechange=function()
	{
		if (ajax_OrdenOS.readyState==1)
		{
	    	DestinoOS.innerHTML="Actualizando...";
	    }
	    else if (ajax_OrdenOS.readyState==4)
		{
			DestinoOS.innerHTML = ajax_OrdenOS.responseText;
			var NroServ=document.getElementById("orden_de_serviciopl").innerHTML;
			//   P. N&ordm; 0021442-NPLA
			var posicion1 = NroServ.indexOf('0'); // posicion = 8
			var posicion2 = NroServ.indexOf('-'); // posicion = 14
			var porcion   = NroServ.substring(posicion1, posicion2); // porcion = "000001"	
			var codigoserv= parseInt(porcion,10);
			document.getElementById("Cod_osnuevo").value=codigoserv;
	  	}
	 }
	 ajax_OrdenOS.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	 ajax_OrdenOS.send("orden_de_serviciopl=1&codigoObra="+xnegocio);
}


function ver_numero_sc() 
{  
 	if(!document.getElementById("solicitud_compra"))	return;	
	if(document.getElementById("entidad").value=='0')
	{
		document.getElementById("solicitud_compra").innerHTML="S.C. N&ordm;"; return;
	} 
	
	var DestinoPur = document.getElementById("solicitud_compra");
	
 	var compania = document.getElementById("entidad").value;
	ajax_OrdenPur=objetoAjax();
	ajax_OrdenPur.open("POST","includes/motoronline.php", true);	
	ajax_OrdenPur.onreadystatechange=function()
	{
		if (ajax_OrdenPur.readyState==1)
		{
	    	DestinoPur.innerHTML="Actualizando...";
	    }
	    else if (ajax_OrdenPur.readyState==4)
		{
			DestinoPur.innerHTML = ajax_OrdenPur.responseText;
	  	}
	 }
	 ajax_OrdenPur.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	 ajax_OrdenPur.send("solicitud_compra=1&compania="+compania);
} 

function ver_numero_od() 
{  
 	if(!document.getElementById("orden_de_despachopl")) return;

	if(document.getElementById("cbnegocio").value=='0')
	{
		document.getElementById("orden_de_despachopl").innerHTML="O.D. N&ordm;"; return;
	} 
	
	var DestinoOD =document.getElementById("orden_de_despachopl");
	var codobra = document.getElementById("cbnegocio").value;
	
	ajax_on_ingreso=objetoAjax();
	ajax_on_ingreso.open("POST","includes/motoronline.php", true);	
	ajax_on_ingreso.onreadystatechange=function()
	{
		if (ajax_on_ingreso.readyState==1)
		{
	    	DestinoOD.innerHTML="Actualizando...";
	    }
	    else if (ajax_on_ingreso.readyState==4)
		{
			DestinoOD.innerHTML = ajax_on_ingreso.responseText;
	  	}
	 }
	 ajax_on_ingreso.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	 ajax_on_ingreso.send("orden_de_despachopl=1&codigoObra="+codobra);
} 

function ver_numero_pl() 
{  
 	if(!document.getElementById("packing_list"))	return;	
	if(document.getElementById("cli_cod").value=='0')
	{
		document.getElementById("packing_list").innerHTML="P.L. N&ordm;"; return;
	} 
	
	var DestinoPur = document.getElementById("packing_list");
	
 	var compania = document.getElementById("cli_cod").value;
	ajax_OrdenPur=objetoAjax();
	ajax_OrdenPur.open("POST","includes/motoronline.php", true);	
	ajax_OrdenPur.onreadystatechange=function()
	{
		if (ajax_OrdenPur.readyState==1)
		{
	    	DestinoPur.innerHTML="Actualizando...";
	    }
	    else if (ajax_OrdenPur.readyState==4)
		{
			DestinoPur.innerHTML = ajax_OrdenPur.responseText;
	  	}
	 }
	 ajax_OrdenPur.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	 ajax_OrdenPur.send("packing_list=1&compania="+compania);
}
function ver_numero_proforma() 
{  
 	if(!document.getElementById("orden_de_proformapl")) return;	
	if(document.getElementById("cbnegocio").value==0) return;

 	var codobra= document.getElementById("cbnegocio").value;
	var Destinoprof =document.getElementById("orden_de_proformapl");
	ajax_on_salido=objetoAjax();
	ajax_on_salido.open("POST","includes/motoronline.php", true);	
	ajax_on_salido.onreadystatechange=function()
	{
		if (ajax_on_salido.readyState==1)
		{
	    	Destinoprof.innerHTML="Actualizando...";
	    }
	    else if (ajax_on_salido.readyState==4)
		{
			Destinoprof.innerHTML = ajax_on_salido.responseText;
		var Nroprof=document.getElementById("orden_de_proformapl").innerHTML;
		//   P. N&ordm; 0021442-NPLA
		var posicion1 = Nroprof.indexOf('0'); // posicion = 8
		var posicion2 = Nroprof.indexOf('-'); // posicion = 14
		var porcion   = Nroprof.substring(posicion1, posicion2); // porcion = "000001"	
		var codigoprof= parseInt(porcion,10);
			document.getElementById("txt_proforma").value=codigoprof;
	  	}
	 }
	 ajax_on_salido.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	 ajax_on_salido.send("orden_de_proformapl=1&codigoObra="+codobra);
}  

function ver_numero_factura() 
{  
 	if(!document.getElementById("orden_de_facturapl"))	return;	
	if(document.getElementById("cbnegocio").value==0) return;
	

 	var codobra= document.getElementById("cbnegocio").value;
	var DestinoFact =document.getElementById("orden_de_facturapl");

	ajax_on_fact=objetoAjax();
	ajax_on_fact.open("POST","includes/motoronline.php", true);	
	ajax_on_fact.onreadystatechange=function()
	{
		if (ajax_on_fact.readyState==1)
		{
	    	DestinoFact.innerHTML="Actualizando...";
	    }
	    else if (ajax_on_fact.readyState==4)
		{
			DestinoFact.innerHTML = ajax_on_fact.responseText;
			var NroFact=document.getElementById("orden_de_facturapl").innerHTML;
		//   P. N&ordm; 0021442-NPLA
		var posicion1 = NroFact.indexOf('0'); // posicion = 8
		var posicion2 = NroFact.indexOf('-'); // posicion = 14
		var porcion   = NroFact.substring(posicion1, posicion2); // porcion = "000001"	
		var codigofact= parseInt(porcion,10);
			document.getElementById("txt_facturacion").value=codigofact;
	  	}
	 }
	 ajax_on_fact.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	 ajax_on_fact.send("orden_de_facturapl=1&codigoObra="+codobra);
}  

function ver_numero_ocnacional() 
{  
 	if(!document.getElementById("ordencompranacionalnro"))	return;	
	if(document.getElementById("entidad").value=='0')
	{
		document.getElementById("ordencompranacionalnro").innerHTML="O.C. N&ordm;"; return;
	} 
	
	var DestinoPur = document.getElementById("ordencompranacionalnro");
	
 	var compania = '1';
	ajax_OrdenPur=objetoAjax();
	ajax_OrdenPur.open("POST","includes/motoronline.php", true);	
	ajax_OrdenPur.onreadystatechange=function()
	{
		if (ajax_OrdenPur.readyState==1)
		{
	    	DestinoPur.innerHTML="Actualizando...";
	    }
	    else if (ajax_OrdenPur.readyState==4)
		{
			DestinoPur.innerHTML = ajax_OrdenPur.responseText;
	  	}
	 }
	 ajax_OrdenPur.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	 ajax_OrdenPur.send("ordencompranacionalnro=1&compania="+compania);
} 


// packing list tienda 
function actualizar_pl_tienda() 
{  
	ver_numero_pl_tienda();    
}

function ver_numero_pl_tienda() 
{  
 	if(!document.getElementById("packing_list_tienda"))	return;	
	if(document.getElementById("cb_cli_cod").value=='0')
	{
		document.getElementById("packing_list_tienda").innerHTML="P.L. N&ordm;"; return;
	} 
	
	var DestinoPur = document.getElementById("packing_list_tienda");
	
 	var compania = document.getElementById("cb_cli_cod").value;
	ajax_OrdenPur=objetoAjax();
	ajax_OrdenPur.open("POST","includes/motoronline.php", true);	
	ajax_OrdenPur.onreadystatechange=function()
	{
		if (ajax_OrdenPur.readyState==1)
		{
	    	DestinoPur.innerHTML="Actualizando...";
	    }
	    else if (ajax_OrdenPur.readyState==4)
		{
			DestinoPur.innerHTML = ajax_OrdenPur.responseText;
	  	}
	 }
	 ajax_OrdenPur.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	 ajax_OrdenPur.send("packing_list_tienda=1&compania="+compania);
}	


//ingreso a tienda
function act_ingreso_tienda() 
{  
 	if(!document.getElementById("ingreso_tienda"))	return;	
	if(document.getElementById("cbempresa").value=='0')
	{
		document.getElementById("ingreso_tienda").innerHTML="I.T. N&ordm;"; return;
	} 
	
	var Destinotda = document.getElementById("ingreso_tienda");
	var codemp=document.getElementById("cbempresa").value;
	ajax_ingtda=objetoAjax();
	ajax_ingtda.open("POST","includes/motoronline.php", true);	
	ajax_ingtda.onreadystatechange=function()
	{
		if(ajax_ingtda.readyState==1)
		{
	    	Destinotda.innerHTML="Actualizando...";
	   }
	   else if(ajax_ingtda.readyState==4)
		{
			Destinotda.innerHTML = ajax_ingtda.responseText;
	  	}
	 }
	 ajax_ingtda.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	 ajax_ingtda.send("ingreso_tienda=1&codemp="+codemp);
}	
/////////////////////////

   
window.setInterval("refresh_comercial_invoice()", 10000); // el tiempo X que tardar� en actualizarse  
window.setInterval("actualizar_sc()", 10000); // el tiempo X que tardar� en actualizarse 
window.setInterval("actualizar_pl()", 10000); // el tiempo X que tardar� en actualizarse
window.setInterval("actualizar_osod()", 10000);// el tiempo X que tardar� en actualizarse 	
window.setInterval("actualizar_ordencompranaci()", 10000); // el tiempo X que tardar� en actualizarse
window.setInterval("act_ingreso_materiaprima()",10000);
window.setInterval("act_salida_hilos()",10000);
window.setInterval("act_salida_quimicos()",10000);
window.setInterval("act_codigo_mp_importa()",10000);
window.setInterval("act_devol_prodt()",10000);
window.setInterval("act_cotiza_clientes()",10000);
window.setInterval("ver_numero_pl_tienda()",10000);
window.setInterval("act_ingreso_tienda()",10000);
window.setInterval("act_salida_tienda()",10000);
window.setInterval("act_devol_hilos()",10000);
window.setInterval("act_devol_quimicos()",10000);
window.setInterval("act_devol_tienda()",10000);
window.setInterval("act_recibo_pago()",10000);


function llamada_comercial() 
{  
 	if(!document.getElementById("comercial_invoice"))	return;	
	if(document.getElementById("campo_oc").value=='')
	{
		document.getElementById("comercial_invoice").innerHTML="C.I. "; return;
	} 
	
	var DestinoComm = document.getElementById("comercial_invoice");
	
 	////var anything = document.getElementById("entidad").value;
	ajax_Comm=objetoAjax();
	ajax_Comm.open("POST","includes/motoronline.php", true);	
	ajax_Comm.onreadystatechange=function()
	{
		if (ajax_Comm.readyState==1)
		{
	    	DestinoComm.innerHTML="Actualizando...";
	    }
	    else if (ajax_Comm.readyState==4)
		{
			DestinoComm.innerHTML = ajax_Comm.responseText;
	  	}
	 }
	 ajax_Comm.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	 ajax_Comm.send("comercial_invoice=1&anything=1");
}  


   function refresh_comercial_invoice() 
   {  
   		llamada_comercial();    
   }  
      

	   /////////////////////////
   
window.setInterval("refresh_ingreso_importacion()", 10000); // el tiempo X que tardar� en actualizarse  
	
	
function llamada_ingreso_importacion() 
{  
 	if(!document.getElementById("ingreso_importacion"))	return;	
	if(document.getElementById("txt_ci").value=='')
	{
		document.getElementById("ingreso_importacion").innerHTML="I.I. "; return;
	} 
	
	var DestinoComm = document.getElementById("ingreso_importacion");
	
	
 	////var anything = document.getElementById("entidad").value;
	ajax_Comm=objetoAjax();
	ajax_Comm.open("POST","includes/motoronline.php", true);	
	ajax_Comm.onreadystatechange=function()
	{
		if (ajax_Comm.readyState==1)
		{
	    	DestinoComm.innerHTML="Actualizando...";
	    }
	    else if (ajax_Comm.readyState==4)
		{
			DestinoComm.innerHTML = ajax_Comm.responseText;
	  	}
	 }
	 ajax_Comm.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	 ajax_Comm.send("ingreso_importacion=1&anything=1");
}  


function refresh_ingreso_importacion() 
{  
	llamada_ingreso_importacion();    
}  
     
	
/////////////////////	
	
window.setInterval("refresh_movimiento_importacion()", 10000); // el tiempo X que tardar� en actualizarse  
	
	
function llamada_movimiento_importacion() 
{  
 	if(!document.getElementById("movimiento_importacion"))	return;	
	if(document.getElementById("cod_origen").value=='')
	{
		document.getElementById("movimiento_importacion").innerHTML="#M"; return;
	} 
	
	var DestinoComm = document.getElementById("movimiento_importacion");
	
	
 	////var anything = document.getElementById("entidad").value;
	ajax_Comm_Mov=objetoAjax();
	ajax_Comm_Mov.open("POST","includes/motoronline.php", true);	
	ajax_Comm_Mov.onreadystatechange=function()
	{
		if (ajax_Comm_Mov.readyState==1)
		{
	    	DestinoComm.innerHTML="Actualizando...";
	    }
	    else if (ajax_Comm_Mov.readyState==4)
		{
			DestinoComm.innerHTML = ajax_Comm_Mov.responseText;
	  	}
	 }
	 ajax_Comm_Mov.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	 ajax_Comm_Mov.send("movimiento_importacion=1&anything=1");
}  


function refresh_movimiento_importacion() 
{  
		llamada_movimiento_importacion();    
}  
     

