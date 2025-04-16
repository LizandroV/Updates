function buscar_kardex_tienda()
{
	var codAlmacen=document.getElementById("valAlmacen").value;
	var codAEmpresa=document.getElementById("valEmpresa").value;
	var codProducto=document.getElementById("valProducto").value;
	var codPartida=document.getElementById("valPartida").value;
	var fecInicio=document.getElementById("fecha_ini").value;
	var fecFin=document.getElementById("fecha_fin").value;
	
	if(codAEmpresa==0)
		{
			alert("Debe Elegir la Empresa");
			document.getElementById("valEmpresa").focus();
			return;
		}

	if(codAlmacen==0)
	{
		alert("Debe Elegir el Almacen");
		document.getElementById("valAlmacen").focus();
		return;
	}

	if(fecInicio=="" || fecFin=="")
		{
			alert("Debe Elegir las Fechas");
			document.getElementById("fecha_ini").focus();
			document.getElementById("fecha_fin").focus();
			return true;
		}

	var variable="";
		variable ="&almacen="+codAlmacen+
				   "&empresa="+codAEmpresa+
				   "&producto="+codProducto+
				   "&partida="+codPartida+
				   "&inicio="+fecInicio+
				   "&fin="+fecFin;

	var ListordDiv=document.getElementById("lista_kardex_tienda");
	ajax_kardex=objetoAjax();			
	ajax_kardex.open("post","templates/despacho/kardex_tienda/lista_kardex_tienda.php", true);
	ajax_kardex.onreadystatechange=function()
	{
	 	 if(ajax_kardex.readyState==1)
		 {
	   		ListordDiv.innerHTML = img_carga5;
	   }
	   else if(ajax_kardex.readyState==4)
		 {
			 	ListordDiv.innerHTML = ajax_kardex.responseText;
	   }
	}
	ajax_kardex.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax_kardex.send(variable);
}

function kardex_productos_tienda_excel()
{
	var codAlmacen=document.getElementById("valAlmacen").value;
	var codAEmpresa=document.getElementById("valEmpresa").value;
	var codProducto=document.getElementById("valProducto").value;
	var codPartida=document.getElementById("valPartida").value;
	var fecInicio=document.getElementById("fecha_ini").value;
	var fecFin=document.getElementById("fecha_fin").value;
	
	if(codAEmpresa==0)
		{
			alert("Debe Elegir la Empresa");
			document.getElementById("valEmpresa").focus();
			return;
		}

	if(codAlmacen==0)
	{
		alert("Debe Elegir el Almacen");
		document.getElementById("valAlmacen").focus();
		return;
	}

	if(fecInicio=="" || fecFin=="")
		{
			alert("Debe Elegir las Fechas");
			document.getElementById("fecha_ini").focus();
			document.getElementById("fecha_fin").focus();
			return true;
		}

	var variable="";
		variable ="&almacen="+codAlmacen+
				   "&empresa="+codAEmpresa+
				   "&producto="+codProducto+
				   "&partida="+codPartida+
				   "&inicio="+fecInicio+
				   "&fin="+fecFin;

	// window.open("plantilla/exportar_kardex__productos_tienda.php?"+variable,
	// 	'REPORTE','resizable=yes,scrollbars=yes',false);

	var link = document.createElement("a");
	link.href = "plantilla/exportar_kardex__productos_tienda.php?" + variable;
	link.setAttribute("download", "kardex_productos.xlsx"); // Nombre del archivo a descargar
	document.body.appendChild(link);
	link.click();
	document.body.removeChild(link);
}


