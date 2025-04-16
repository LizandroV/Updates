
function buscar_codbarra_ingtienda()
{
	var codbarra=document.getElementById("cod_barra").value;
	insertar_fila_ing_tienda(codbarra);
	
	document.getElementById("cod_barra").value="";
	document.getElementById("cod_barra").focus();
}


function insertar_fila_ing_tienda(cod_barra)
{
	$("txt_codpl").disabled=true;
	$("btn_agregar_rollos").disabled=true;

	var codemp=document.getElementById("cbempresa").value;
	if(codemp=="0")
	{
		alert("Debe selecionar la Empresa");
		document.getElementById("cbempresa").focus();
		return;
	}

	var codalmacenD=document.getElementById("cbalmacen_destino").value;
	if(codalmacenD=="0")
	{
		alert("Debe selecionar el Almacen Destino");
		document.getElementById("cbalmacen_destino").focus();
		return;
	}

	var codpl=document.getElementById("txt_pl_barra").value;
	if(codpl==""){alert("Ingrese Numero de Packing"); return;}
	
	var contador = 0; 
	var hiddens = document.getElementsByName('puntero');

	/* RROJAS - evita agregar rollos repetidos */
	var num_filas = document.getElementsByName("puntero").length;
	for(var k=0;k<num_filas;k++)
	{
		var indice=k;
		var cod_barra_fila=document.getElementById("txt_cbarra"+indice).value;
		if (cod_barra_fila==cod_barra)
		{
			alert("El Numero de Rollo Ya Existe En El Packing");
			return;
		}
	}

	for (var x=0; x < hiddens.length; x++)	contador = contador + 1;
	if(document.querySelector('#detalle_tienda').childElementCount==0)
	{
		nDiv = document.createElement('div');
		nDiv.id = "divreg0";
		nDiv.style.width="100%";
		container = document.getElementById('detalle_tienda');
		container.appendChild(nDiv);
		contador=0;
	}

	var nombrediv=document.getElementById("detalle_tienda"); 
	var txt_div=nombrediv.lastElementChild.id;
	var long = txt_div.length;
	
	if(long > 0)
	{       
		var contador=txt_div.substring(6,long);
	}
	
	var PequenoDiv=document.getElementById("divreg"+contador.toString());
	var parametros_envio="";
	parametros_envio="&correlativo="+contador+"&cod_carra="+cod_barra+"&codpl="+codpl;
	ObjectoCarga=objetoAjax();
	ObjectoCarga.open("POST","templates/despacho/ingreso_tienda/filas_detalle_codbarra_ingtienda.php", true);	
	ObjectoCarga.onreadystatechange=function()
	{
	 	 if (ObjectoCarga.readyState==1)
		 {
	         PequenoDiv.innerHTML=img_carga_small;
	     }
	     else if (ObjectoCarga.readyState==4)
		 {
			var largom = ObjectoCarga.responseText;
			if (largom.length<100)
			{
				alert(ObjectoCarga.responseText);
				return;
			}else{
			PequenoDiv.innerHTML = ObjectoCarga.responseText;
			contador = parseInt(contador) + 1;
			nDiv = document.createElement('div');
			nDiv.id = 'divreg' + contador.toString();
			nDiv.style.width='100%';
			container = document.getElementById('detalle_tienda');
			container.appendChild(nDiv);
			calcula_pesos_rollos();
			}
	  	 }
	}
	ObjectoCarga.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ObjectoCarga.send(parametros_envio);	
}



function buscar_codpl_insertar_filas()
{
	$("cod_barra").disabled=true;
	$("btnbuscar").disabled=true;
	$("txt_pl_barra").disabled=true;

	var codemp=document.getElementById("cbempresa").value;
	if(codemp=="0")
	{
		alert("Debe selecionar la Empresa");
		document.getElementById("cbempresa").focus();
		return;
	}

	var codalmacenD=document.getElementById("cbalmacen_destino").value;
	if(codalmacenD=="0")
	{
		alert("Debe selecionar el Almacen Destino");
		document.getElementById("cbalmacen_destino").focus();
		return;
	}

	var codpl=document.getElementById("txt_codpl").value;
	if(codpl=="")
	{
		alert("Ingrese Nro. de Packing List");
		document.getElementById("txt_codpl").focus();
		return;
	}
	
	var contador = 0; 
	var hiddens = document.getElementsByName('puntero');
	/* RROJAS - evita agregar rollos repetidos */
	var num_filas = document.getElementsByName("puntero").length;
	for(var k=0;k<num_filas;k++)
	{
		var indice=k;
		var codpl_fila=document.getElementById("txt_codpl"+indice).value;
		if(codpl_fila==codpl)
		{
			alert("El Packing ya existe en el detalle.");
			return;
		}
	}
	
	var PequenoDiv=document.getElementById("detalle_tienda");
	var parametros_envio="";
	parametros_envio="&correlativo="+contador+"&codpl="+codpl;
	ObjectoCarga=objetoAjax();
	ObjectoCarga.open("POST","templates/despacho/ingreso_tienda/filas_detalle_packing_ingtienda.php", true);	
	ObjectoCarga.onreadystatechange=function()
	{
		if(ObjectoCarga.readyState==1)
		{
	    	PequenoDiv.innerHTML=img_carga_small;
	    }
	    else if(ObjectoCarga.readyState==4)
		{
			var largom = ObjectoCarga.responseText;
			if(largom.length<100)
			{
				//alert(ObjectoCarga.responseText);
				return;
			}else{
				PequenoDiv.innerHTML = ObjectoCarga.responseText;
				calcula_pesos_rollos();
			}
	  	}
	}
	ObjectoCarga.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ObjectoCarga.send(parametros_envio);	
}


function calcula_ingpl_tienda()
{
	var contador = 0; 
	var x = (!x ? 4 : x);
	var hiddens = document.getElementsByName('puntero');

	var suma=0.0;
	var num_filas = document.getElementsByName("puntero").length;
	for(var k=0;k<num_filas;k++)
	{
		var indice=k;	
		var peso_fila = document.getElementById("txt_peso"+indice).value;
		suma = suma + Math.round(parseFloat(peso_fila)*Math.pow(10,x)) / Math.pow(10,x);
	}
	var totalgen = Math.round(parseFloat(suma)*Math.pow(10,x))  /  Math.pow(10,x);
	document.getElementById("txt_totalkg").value = totalgen;
	document.getElementById("txt_trollos").value=num_filas;
}


function calcula_pesos_rollos()
{
	var x = (!x ? 4 : x);
	var suma_peso=0.0;
	var suma_rollos=0.0;
	var num_filas =document.getElementsByName("puntero").length;
	for(var k=0;k<=num_filas-1;k++)
	{
		var indice=k;
		var peso_fila = document.getElementById("txt_peso"+indice).value;
		var cant_rollos=document.getElementById("txt_rollos"+indice).value;

		suma_peso = suma_peso + Math.round(parseFloat(peso_fila)*Math.pow(10,x)) / Math.pow(10,x);
		suma_rollos = suma_rollos + Math.round(parseFloat(cant_rollos)*Math.pow(10,x)) / Math.pow(10,x);

	}
	var total_peso = Math.round(parseFloat(suma_peso)*Math.pow(10,x))  /  Math.pow(10,x);
	var total_rollos = Math.round(parseFloat(suma_rollos)*Math.pow(10,x))  /  Math.pow(10,x);

	document.getElementById("txt_totalkg").value = total_peso;
	document.getElementById("txt_trollos").value = total_rollos;
}


function abrir_agregar_prod_tienda() 
{
	$("btnbuscar").disabled=true;

	var codemp=document.getElementById("cbempresa").value;
	if(codemp=="0")
	{
		alert("Debe selecionar la Empresa");
		return;
	}

	var codalmacenD=document.getElementById("cbalmacen_destino").value;
	if(codalmacenD=="0")
	{
		alert("Debe selecionar el Almacen Destino");
		return;
	}
	
	var Url='templates/despacho/ingreso_tienda/buscar_prod_tienda.php';
	var largo = 1125;
	var altura = 455;
	var NombreVentana = 'BMANUAL';
	
	var top = (screen.height-altura)/2;
	var izquierda = (screen.width-largo)/2; 
	nuevaVentana=window.open(''+ Url,''+ 
		NombreVentana + '','width=' + 
		largo + ',height=' + 
		altura + ',top=' + 
		top + ',left=' + izquierda + ',features=' +  '');
	nuevaVentana.focus();
}


function buscar_reporte_stock_tienda()
{
	var CodEmp=document.getElementById("entidad").value;
	var CodAlmacen=document.getElementById("almacen").value;
	var Prod=document.getElementById("producto").value;
	var Part=document.getElementById("partida").value;
	var lote=document.getElementById("txt_lote").value;
	var contenedor=document.getElementById("txt_contenedor").value;

	if(CodAlmacen=="0")
	{
		alert("Debe Elegir el Almacen");
		document.getElementById("almacen").focus();
		return;
	}

	var variable="";
 	variable ="&CodEmp="+CodEmp+
	   			"&CodAlmacen="+CodAlmacen+
	   			"&Prod="+Prod+
	   			"&Part="+Part+
				"&lote="+lote+
	   			"&contenedor="+contenedor;


	var ListordDiv=document.getElementById("lista_stock_tienda");
	ajax_hojaruta=objetoAjax();			
	ajax_hojaruta.open("post","templates/despacho/reporte_stock_tienda/lista_stock_tienda.php", true);
	ajax_hojaruta.onreadystatechange=function()
	{
	 	 if (ajax_hojaruta.readyState==1)
		 {
	         ListordDiv.innerHTML = img_carga5;
	     }
	     else if(ajax_hojaruta.readyState==4)
		 {
			 ListordDiv.innerHTML = ajax_hojaruta.responseText;
	  	 }
	}
	ajax_hojaruta.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax_hojaruta.send(variable);
}


function nuevo_ingreso_tienda()
{
	var a=confirm("¿Desea Generar un nuevo Documento?.");
	if(a==false) return;

	var boton_save=document.getElementById("guardar");
	boton_save.disabled=false;
	boton_save.innerHTML="<img width='15' height='15' src='images/btn_guardar.png' align='absmiddle'>&nbsp;Guardar Documento";
	boton_save.disabled=true;
	
	$("cbempresa").disabled=false;
	$("cbempresa").value=0;
	$("cbalmacen_destino").disabled=false;
	$("cbalmacen_destino").value=0;
	$("fecha_ing").disabled=false;
	$("fecha_ing").value="";
	$("cod_barra").value="";
	$("cod_barra").disabled=false;
	$("btnbuscar").disabled=false;
	$("agregar_prod").disabled=false;
	$("guardar").disabled=false;
	$("txt_obs_ing").value="";
	$("txt_obs_ing").disabled=false;
	$("txt_totalkg").value=0;
	$("txt_trollos").value=0;
	$("cmbordenes")[0].selected=true;
	$("nroguia").value="";
	$("nroguia").disabled=false;
	$("btn_adjuntar").disabled=false;
	$("nom_doc").value="";
	$("doc_adjunto").innerHTML="";
	$("doc_adjunto").href="";
	$("btn_agregar_rollos").disabled=false;
	$("txt_pl_barra").disabled=false;
	$("txt_pl_barra").value="";
	$("txt_codpl").disabled=false;
	$("txt_codpl").value="";

	/* RROJAS - limpiando los detalles */
	container = document.getElementById('detalle_tienda');
	container.innerHTML = "";
	nDiv = document.createElement('div');
	nDiv.id = 'divreg0';
	nDiv.style.width='100%'; 
	container.appendChild(nDiv);

	//actualizar_pl_tienda();
	if($("ingreso_tienda2"))
	$("ingreso_tienda2").id="ingreso_tienda";	

	$("ingreso_tienda").innerHTML="I.T. N&ordm;";
}



function eliminar_filas_ingtda(id_fila,flag)
{
	if(flag == 'N')
	{
		alert("No puede eliminar este Producto "+(id_fila+1)+", por que la Orden de Compra tiene Pagos asociados!.");
		return;	
	}
	
	var monto=0.0;
	var x = (!x ? 2 : x);
	var txt_peso		= document.getElementById("txt_peso"+id_fila).value;
	var total			= document.getElementById("txt_totalkg").value;
	total_gral			= Math.round(parseFloat(total-txt_peso)*Math.pow(10,x))  /  Math.pow(10,x);
	
	document.getElementById("txt_totalkg").value=total_gral;

	var ByeDiv=document.getElementById('divreg'+id_fila);
	ByeDiv.parentNode.removeChild(ByeDiv);

	//para que no aparesca el Nan al eliminar una fila
	var contador = 0; 
	var hiddens = document.getElementsByName('puntero');
	for (var j=0; j < hiddens.length; j++)	contador = contador + 1;
	if(contador>0)
	{
		var m_total=Math.round(parseFloat(document.getElementById("txt_totalkg").value)*Math.pow(10,x))/Math.pow(10,x);
		document.getElementById("txt_totalkg").value=Math.round(parseFloat(m_total)*Math.pow(10,x))/Math.pow(10,x);
		document.getElementById("txt_trollos").value=contador;
	}
	else
	{		
		document.getElementById("txt_totalkg").value=0;
		document.getElementById("txt_trollos").value=0;
	}

}


function registrar_ingreso_tienda()
{
	var codemp=document.getElementById("cbempresa").value;
	var codalmacenD=document.getElementById("cbalmacen_destino").value;
	var fecha_ing = document.getElementById("fecha_ing").value;
	var total_rollos=document.getElementById("txt_trollos").value;
	var total_peso=document.getElementById("txt_totalkg").value;

	//codpl rrojas
	var codpl=document.getElementById("txt_codpl").value;
	if(codpl=="" || codpl==" ")
	{
		codpl=document.getElementById("txt_pl_barra").value;
	}

	var nro_guia=document.getElementById("nroguia").value;
	//if(nro_guia=="" || nro_guia==" "){alert("Debe Ingresar Nro de Guia"); $("nroguia").focus();}

	var doc_guia=document.getElementById("nom_doc").value;
	//if(doc_guia=="" || doc_guia==" "){alert("Debe Adjuntar la Guia"); $("btn_adjuntar").focus();}

	if(codemp=="0")
	{
		alert("Debe selecionar la Empresa");
		document.getElementById("cbempresa").focus();
		return;
	}

	if(codalmacenD=="0")
	{
		alert("Debe selecionar el Almacen Destino");
		document.getElementById("cbalmacen_destino").focus();
		return;
	}
	
	if(fecha_ing=="")
	{
		alert('Debe Seleccionar la Fecha');
		document.getElementById("fecha_ing").focus();
		return;
	}

	var usuario = document.getElementById("usuario").value;
	var obs_ing	= document.getElementById("txt_obs_ing").value;
	//capturando los detalles
	var contador = 0; 
	var hiddens = document.getElementsByName('puntero');
	for (var j=0; j < hiddens.length; j++)	contador = contador + 1;
	//ver si hay alguna fila en detalle
	if(contador==0)
	{
		alert("No ha ingresado ningun Item.");
		return;
	}
	
	var punteros_filas	= document.getElementsByName("puntero");
	var num_filas		= document.getElementsByName("puntero").length;
	var arreglo_products=new Array();
	var arregloProd= new Array();	
	for(var k=0;k<num_filas;k++)
	{
		var indice=punteros_filas[k].id;
		arregloProd[k]= new Array();

		var det_partida	= 	document.getElementById("txt_partida"+indice).value;
		var det_articulo = 	document.getElementById("txt_articulo"+indice).value;
		var det_color = document.getElementById("txt_color"+indice).value;
		var det_proceso =  document.getElementById("txt_proceso"+indice).value;
		var det_cbarra =  document.getElementById("txt_cbarra"+indice).value;
		var det_peso = document.getElementById("txt_peso"+indice).value;
		var det_cdgcolor= document.getElementById("txt_cdgcolor"+indice).value;
		var det_kbruto= document.getElementById("txt_kbruto"+indice).value;
		var det_cdgart= document.getElementById("txt_cdgart"+indice).value;
		var det_numordped= document.getElementById("txt_numordped"+indice).value;
		var det_numot= document.getElementById("txt_numot"+indice).value;
		var det_rollos=document.getElementById("txt_rollos"+indice).value; 
		var det_grem=document.getElementById("txt_grem"+indice).value;
		// codpl rrojas
		var det_codpl=document.getElementById("txt_codpl"+indice).value;
	
		arregloProd[k][0] = det_partida;
		arregloProd[k][1] = det_articulo;
		arregloProd[k][2] = det_color;
		arregloProd[k][3] = det_proceso;
		arregloProd[k][4] = det_cbarra;
		arregloProd[k][5] = det_peso;
		arregloProd[k][6] = det_cdgcolor;
		arregloProd[k][7] = det_kbruto;
		arregloProd[k][8] = det_cdgart;
		arregloProd[k][9] = det_numordped;
		arregloProd[k][10] = det_numot;
		arregloProd[k][11] = det_rollos;
		arregloProd[k][12] = det_grem;
		//codpl rrojas
		arregloProd[k][13] = det_codpl;

		arreglo_products[k] = det_partida;  ///Creamos un array solo para los productos PARA VALIDAR REPETIDOS!
	}
	
	///////////Evaluar si ese Array de Productos tiene valores repetidos.///////////
	var uniqueArray = arreglo_products.filter(function(value, index, self) { 
	  return self.indexOf(value) === index;
	});

	//Solo en caso de actualizar
	var b = window.confirm("¿Desea Registrar el Ingreso a Tienda?");
	if(b==false) return;
			
	var info_tienda='';
	/// guardar
	if($("ingreso_tienda"))
	{
		//codpl rrojas
		var info_tienda	="&arregloProd=" +arregloProd+
						 "&codemp="	     +codemp+
						 "&codalmacenD=" +codalmacenD+
						 "&fecha_ing="   +fecha_ing+
						 "&usuario="	 +usuario+
						 "&obs_ing="	 +obs_ing+	
						 "&total_rollos="+total_rollos+
						 "&total_peso="  +total_peso+
						 "&nro_guia="	 +nro_guia+
						 "&doc_guia="	 +doc_guia+
						 "&codpl="		 +codpl+
						 "&insertar=1";	
	}
	/*
	else
	if($("ingreso_tienda2"))
	{	///update
		//alert("ACTUALIZAR");
		var OrdenComp=document.getElementById("ingreso_tienda2").innerHTML;
		var posicion1 = OrdenComp.indexOf('0'); // posicion = 8
		var posicion2 = OrdenComp.indexOf('-'); // posicion = 14
		var porcion   = OrdenComp.substring(posicion1, posicion2+1); // porcion = "000001"	
		var codigoOrden= parseInt(porcion,10);	

	if(codigoOrden.toString()=='NaN'){codigoOrden=document.getElementById("cmbordenes").value;}

		var info_plist	=	"&arregloPL="			+arregloPL+
							"&cli_cod="				+cli_cod+
							"&fecha_pl="			+fecha_pl+
							"&usuario="				+usuario+
							"&det_partida="			+det_partida+
							"&det_articulo="		+det_articulo+						
							"&det_color="			+det_color+
							"&det_proceso="			+det_proceso+
							"&det_cbarra=" 			+det_cbarra+
							"&det_peso=" 			+det_peso+
							"&monto_total="			+monto_total+
							"&precio_total="		+precio_total+
							"&obsPL="				+obsPL+
							"&cbOrden="				+cbOrden+
							"&codigoOrden="			+codigoOrden+
							"&det_guiarem1="		+det_guiarem1+
							"&update=1";
	}
	*/
	ajax_guardar=objetoAjax();
	ajax_guardar.open("POST","templates/despacho/ingreso_tienda/lista_ingreso_tienda.php", true);
	ajax_guardar.onreadystatechange=function()
	{
	 	if (ajax_guardar.readyState==1)
		{
			$("PRUEBA").innerHTML = "";
		}
	    else if (ajax_guardar.readyState==4)
		{		
			console.log(ajax_guardar.responseText);
		}
	}	
	ajax_guardar.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax_guardar.send(info_tienda);
		
	$("cbempresa").disabled=true;
	$("cbalmacen_destino").disabled=true;
	$("fecha_ing").disabled=true;
	$('agregar_prod').disabled=true;
	$('btnbuscar').disabled=true;
	$('cod_barra').disabled=true;
	$("imprimir").disabled=false;
	$("guardar").disabled=true;
	$("btn_eliminar_ing").disabled=true;
	$("txt_totalkg").disabled=true;
	$("txt_trollos").disabled=true;
	$("txt_obs_ing").disabled=true;
	$("nroguia").disabled=true;
	$("btn_adjuntar").disabled=true;

	if($("ingreso_tienda"))
	$("ingreso_tienda").id="ingreso_tienda2";	

}


function buscar_ingresos_tienda()
{
	var cmbus_anio=document.getElementById("cmbus_anio").value;
	var cmbus_mes =document.getElementById("cmbus_mes").value;

	var CodigoOrdenesCompra=$("cmbordenes");
	if(CodigoOrdenesCompra=='0')
	{
		$("verorden").disabled=true;
		$("editorden").disabled=true;	
	}
	
	var variable="buscarorden=2&CodigoOrdenesCompra="+CodigoOrdenesCompra+
	"&cmbus_anio="+cmbus_anio+"&cmbus_mes="+cmbus_mes;
	var Ordenes=document.getElementById("cmbordenes");
	ajax_buscar=objetoAjax();
	ajax_buscar.open("POST","templates/despacho/ingreso_tienda/filtro_ingresos_tienda.php", true);	

	ajax_buscar.onreadystatechange=function()
	{
		if(ajax_buscar.readyState==1)
		{
			Ordenes.length=0;
			var nuevaOpcion=document.createElement("option");
			nuevaOpcion.value=0;
			nuevaOpcion.innerHTML="Cargando......";
			Ordenes.appendChild(nuevaOpcion);
		}
		else if(ajax_buscar.readyState==4)
		{
			//console.log(ajax_buscar.responseText);
			Ordenes.parentNode.innerHTML = ajax_buscar.responseText;			
		}
	}

	ajax_buscar.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax_buscar.send(variable);

	document.getElementById("cmbordenes").focus();
	$("verorden").disabled=true;
	$("editorden").disabled=true;	
}


function ver_ingresos_tienda()
{
	var cmbordenes	=$F("cmbordenes");
	var container = document.getElementById("detalle_tienda");
	container.innerHTML = "";
	document.getElementById("editorden").disabled=false;
	document.getElementById("imprimir").disabled=false;
	document.getElementById("guardar").disabled=true;
	var boton_save=document.getElementById("guardar");
	boton_save.disabled=false;
	boton_save.innerHTML="<img width='15' height='15' src='images/btn_guardar.png' align='absmiddle'>&nbsp;Actualizar Documento";
	boton_save.disabled=true;
	if($("ingreso_tienda"))
	$("ingreso_tienda").id="ingreso_tienda2";	
	var index=document.form1.cmbordenes.selectedIndex;
	var serie=document.form1.cmbordenes[index].innerHTML;
	document.getElementById("ingreso_tienda2").innerHTML=""+serie+"";

	//CABECERA
	var field_vOrden=document.getElementById("vorden");
	var parametro ='';
		parametro ="&coding_tda="+cmbordenes+"&busca_ing=1";
	ajax_cabecera=objetoAjax();	
	ajax_cabecera.open("POST","templates/despacho/ingreso_tienda/filtro_ingresos_tienda.php", true);	
	ajax_cabecera.onreadystatechange=function()
	{
		if(ajax_cabecera.readyState==1)
		{
			field_vOrden.value="";
		}
	    else if(ajax_cabecera.readyState==4)
		{
			rpta=JSON.parse(ajax_cabecera.responseText);
			document.getElementById("cbempresa").value = rpta.codemp;
			document.getElementById("cbalmacen_destino").value = rpta.codalmacen;
			document.getElementById("fecha_ing").value = rpta.fechareg;
			document.getElementById("txt_totalkg").value=rpta.total_pesokg;
			document.getElementById("txt_trollos").value=rpta.total_rollos;
			document.getElementById("txt_obs_ing").value=rpta.obs;
			document.getElementById("salidas_ingcancelar").value=rpta.cant_rollos_mov;
			$("nroguia").value=rpta.nro_guia;
			$("nroguia").disabled=true;
			$("nom_doc").value=rpta.doc_guia;
			$("doc_adjunto").innerHTML=rpta.doc_guia;
			$("doc_adjunto").href="images/docs_ingreso_tienda/"+rpta.doc_guia;
			$("btn_adjuntar").disabled=true;
			//codpl rrojas
			$("txt_codpl").value=rpta.codpl;
			$("txt_codpl").disabled=true;
			$("txt_pl_barra").disabled=true;
		}
	}
	ajax_cabecera.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax_cabecera.send(parametro);

	//DETALLE
	var contador = 0; 
	var update ='';
		update ="&coding_tda="+cmbordenes+"&correlativo=0";
	var url_destino = "filas_detalle_ver_ingresos_tienda";
	var container = document.getElementById("detalle_tienda");
	container.innerHTML = "";
	ajax_detalle=objetoAjax();
	ajax_detalle.open("POST","templates/despacho/ingreso_tienda/"+url_destino+".php", true);	
	ajax_detalle.onreadystatechange=function()
	{
	 	 if(ajax_detalle.readyState==1)
		 {
	     	container.innerHTML=img_carga2;
	     }
	     else if(ajax_detalle.readyState==4)
		 {
			container.innerHTML = ajax_detalle.responseText;
	  	 }
	}
	ajax_detalle.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax_detalle.send(update);

	$("cbempresa").disabled=true;
	$("cbalmacen_destino").disabled=true;
	$("fecha_ing").disabled=true;
	$("txt_obs_ing").disabled=true;
	
	document.getElementById("btn_eliminar_ing").disabled=true;
	document.getElementById('agregar_prod').disabled=true;
	document.getElementById('btnbuscar').disabled=true;
	document.getElementById('cod_barra').disabled=true;
	document.getElementById("imprimir").disabled=false;
	document.getElementById("guardar").disabled=true;

}

function ver_rollos_partida_tienda(partida, producto, codalmacen, codemp)
{
	var Url='templates/despacho/reporte_stock_tienda/ver_rollos_partida_tienda.php?partida='+base64_encode(partida);
	var largo = 340;
	var altura = 415;
	var NombreVentana = 'ROLLOS';	
	var top = (screen.height-altura)/2;
	var izquierda = (screen.width-largo)/2; 
	nuevaVentana=window.open(''+ Url + '&producto='+base64_encode(producto)+
			'&codalmacen='+base64_encode(codalmacen)+
			'&codemp='+base64_encode(codemp),
			''+ NombreVentana + '',
			'width=' + largo + ',height=' + altura +
			',top=' + top + ',left=' + izquierda + 
			',features=' +  '');
	nuevaVentana.focus();
}

function activar_verorden_ing_tienda(value)
{
	//func del combo de ordenes
	if(value=='0'){
		$("verorden").disabled=true;
		$("verorden").focus=true;
		$("editorden").disabled=true;	
	}
	else{
		$("verorden").disabled=false;
	}
}

function editar_ingreso_tienda()
{
	var mov_ingtienda=$("salidas_ingcancelar").value;
	if(mov_ingtienda>0){
		alert("No es posible Editar este Ingreso porque ya tiene Movimientos de Almacen asociados.");
		return;
	}

	$("imprimir").disabled=true;
	$("nuevo").disabled=false;
	$("guardar").disabled=false;

	//habilitando el form para actualizar
	$("cbempresa").disabled=false;
	$("cbalmacen_destino").disabled=false;
	$("fecha_ing").disabled=false;
	$("txt_obs_ing").disabled=false;
	$("cod_barra").disabled=false;
	$("btnbuscar").disabled=false;
	$("agregar_prod").disabled=false;
	$("btn_eliminar_ing").disabled=false;
	$("imprimir").disabled=true;
	$("guardar").disabled=false;
}

function eliminar_ingreso_tienda()
{
	var mov_ingtienda=$("salidas_ingcancelar").value;
	if(mov_ingtienda>0)
	{
		alert("No es posible Eliminar este Ingreso porque ya tiene Movimientos de Almacen asociados.");
		return;
	}

	//si se puede cancelar
	var Ordeningreso=document.getElementById("ingreso_tienda2").innerHTML;
	var usuario=document.getElementById("usuario").value;
	var a=confirm("¿Esta Ud. Realmente seguro de Eliminar este Documento "+Ordeningreso+" ?.");
	if(a==false) return;
	
	var coding_tienda = document.getElementById("cmbordenes").value;		
	var parametro = '';
		parametro =	'&usuario='+usuario+
				'&coding_tienda='+coding_tienda+
				'&eliminar=1';
	ajax_elimina=objetoAjax();
	ajax_elimina.open("POST","templates/despacho/ingreso_tienda/lista_ingreso_tienda.php", true);	
	ajax_elimina.onreadystatechange=function()
	{
	 	if(ajax_elimina.readyState==1)
		{
			$("PRUEBA").innerHTML = "";
		}
	    else if(ajax_elimina.readyState==4)
		{
			$("PRUEBA").innerHTML = ajax_elimina.responseText;			
		}
	}
	ajax_elimina.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax_elimina.send(parametro);


	////////////////////Regrsando a estado original
	//Regresando el estado original del div de seria de orden
	if($("ingreso_tienda2"))
	$("ingreso_tienda2").id="ingreso_tienda";	
	
	/////////////TRAER TODO DE NUEVO DOCUMENTO///////////////
	$("cbempresa").disabled=false;
	$("cbempresa").value=0;
	$("cbalmacen_destino").disabled=false;
	$("cbalmacen_destino").value=0;
	$("fecha_ing").disabled=false;
	$("fecha_ing").value="";
	$("txt_obs_ing").disabled=false;
	$("txt_obs_ing").value="";
	$("txt_totalkg").value=0;
	$("txt_trollos").value=0;
	
	$("guardar").innerHTML="<img width='15' height='15' src='images/btn_guardar.png' align='absmiddle'>&nbsp;Guardar";
	$("detalle_tienda").innerHTML="";

	/// botones
	$("imprimir").disabled=true;
	$("nuevo").disabled=false;
	$("agregar_prod").disabled=false;
	$("guardar").disabled=false;
	$("btn_eliminar_ing").disabled=true;
}


function adjuntar_guia_ingreso_tda()
{
	var cod_emp			  = $("cbempresa").value;
	var cod_almacen		  = $("cbalmacen_destino").value;
	var coding_tienda	  = 0;
	var val_nuevoedit 	  = "";
	var str_coding_tienda = "";
	var valida			  = $("validador").value;
	var usuario			  =	$("usuario").value;
	
	if(cod_emp=="0"){alert("Debe Elegir la Empresa."); $("cbempresa").focus(); return;}
	if(cod_almacen=="0"){alert("Debe Elegir el Almacen."); $("cbalmacen_destino").focus(); return;}
	if($("editorden").disabled==true)
	{
		str_coding_tienda = $("ingreso_tienda").innerHTML;
		val_nuevoedit 	  = "NUEVO";
		var porcion       = str_coding_tienda.substring(8, 15);
		coding_tienda	  = parseInt(porcion,10);
	}else if($("editorden").disabled==false)
	{
		str_coding_tienda = $("ingreso_tienda2").innerHTML;
		val_nuevoedit 	  = "EDITAR";
		coding_tienda	  = $("cmbordenes").value;
	}

	var Url="templates/despacho/ingreso_tienda/adjuntar_ingreso_tienda.php";
	var largo = 670;
	var altura = 240;
	var NombreVentana = "Adjuntar Archivos";
	
	var top = (screen.height-altura)/2;
	var izquierda = (screen.width-largo)/2; 
	nuevaVentana=window.open(''+ Url + '?val_nuevoedit='+val_nuevoedit+
			'&coding_tienda='+coding_tienda+'&cod_emp='+cod_emp+
			'&cod_almacen='+cod_almacen+'&valida='+valida+
			'&usuario='+usuario,''+ NombreVentana + '',
			'width=' + largo + ',height=' + altura + ',top=' + top + ',left=' + izquierda + ',features=' + '');
	nuevaVentana.focus();	
}


function stock_tienda_excel()
{
	var CodEmp=document.getElementById("entidad").value;
	var CodAlmacen=document.getElementById("almacen").value;
	var Prod=document.getElementById("producto").value;
	var Part=document.getElementById("partida").value;
	var lote=document.getElementById("txt_lote").value;
	var contenedor=document.getElementById("txt_contenedor").value;
	
	if(CodAlmacen=="0")
	{
		alert("Debe Elegir el Almacen");
		document.getElementById("almacen").focus();
		return;
	}

	var variable="";
 	variable ="&CodEmp="+CodEmp+
	   			"&CodAlmacen="+CodAlmacen+
	   			"&Prod="+Prod+
				"&Part="+Part+
	   			"&lote="+lote+
	   			"&contenedor="+contenedor;

	// window.open("plantilla/exportar_stock_tiendaxl.php?"+variable,
	// 	'REPORTE','resizable=yes,scrollbars=yes',false);
	
	var link = document.createElement("a");
	link.href = "plantilla/exportar_stock_tiendaxl.php?" + variable;
	link.setAttribute("download", "rep_stock_tienda.xlsx"); // Nombre del archivo a descargar
	document.body.appendChild(link);
	link.click();
	document.body.removeChild(link);
}