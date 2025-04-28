function buscar_datos_orden_proforma() {
	var codigoObra = $F("codNegocio");
	var codigoOrden = $F("order_factura");

	if (codigoObra == "0") {
		alert("Debe escoger el Negocio.");
		document.getElementById("codNegocio").focus();
		return;
	}

	if (codigoOrden == "") {
		alert("Debe ingresar el Numero de Proforma.");
		return;
	}

	var parVal = "";
	parVal =
		"&codigoObra=" +
		codigoObra +
		"&codigoOrden=" +
		codigoOrden +
		"&validar_SI_ORD_PAGO=9999";

	ajax_parVal = objetoAjax();
	ajax_parVal.open(
		"POST",
		"templates/transacciones/nota_credito_proforma/act_datos_nota_credito_prof.php",
		true
	);
	ajax_parVal.onreadystatechange = function () {
		if (ajax_parVal.readyState == 1) {
			$("validar_SI_ORD_PAGO").innerHTML = "";
		} else if (ajax_parVal.readyState == 4) {
			var respuesta = ajax_parVal.responseText;
			var valor = respuesta.split("_")[0];
			var documento = respuesta.split("_")[1];

			///Dividiendo la parte de DOCUMENTO.
			var opago = documento.split("|")[0];
			var nota = documento.split("|")[1];

			if (valor == 1) {
				///Se esta Cambiando alert por Confirm para dejar pasar y crear nueva Nota Credito Nov2018
				alert(
					"Esta Ord. de Proforma ya esta en una Orden de Pago " +
						opago +
						". \n-Consultar con el Administrador.!"
				);
			}

			if (valor == 2) {
				///Se esta Cambiando alert por Confirm para dejar pasar y crear nueva Nota Credito Nov2018
				alert(
					"Esta Ord. de Proforma ya tiene una Nota de Credito " +
						nota +
						". \n-Consultar con el Administrador.!"
				);
			}

			if (valor == 3) {
				///Se esta Cambiando alert por Confirm para dejar pasar y crear nueva Nota Credito Nov2018
				alert(
					"Esta Ord. de Proforma ya tiene una Nota de Credito " +
						nota +
						" y esta en una Orden de Pago " +
						opago +
						" . \n-Consultar con el Administrador.!"
				);
			}

			if (valor == 5 || valor >= 7) {
				///Se esta Cambiando alert por Confirm para dejar pasar y crear nueva Nota Credito Nov2018
				alert(
					"Esta Ord. de Proforma ya esta en una Orden de Pago " +
						opago +
						" . \n-Consultar con el Administrador.!"
				);
			}

			//if(valor==0)
			//{
			///espacio_factura_fisica
			var factura_fisica = document.getElementById("espacio_factura_fisica");
			var variable2 = "";
			variable2 =
				"&codigoObra=" +
				codigoObra +
				"&codigoOrden=" +
				codigoOrden +
				"&factura_fisica=3";
			ajax3 = objetoAjax();
			ajax3.open(
				"POST",
				"templates/transacciones/nota_credito_proforma/act_datos_nota_credito_prof.php",
				true
			);
			ajax3.onreadystatechange = function () {
				if (ajax3.readyState == 1) {
					factura_fisica.innerHTML = "";
				} else if (ajax3.readyState == 4) {
					factura_fisica.innerHTML = ajax3.responseText;
				}
			};
			ajax3.setRequestHeader(
				"Content-Type",
				"application/x-www-form-urlencoded"
			);
			ajax3.send(variable2);

			///espacio_empresa
			var espacio_empresa = document.getElementById("espacio_empresa");
			var variable3 = "";
			variable3 =
				"&codigoObra=" +
				codigoObra +
				"&codigoOrden=" +
				codigoOrden +
				"&espacio_empresa=4";
			ajax4_espacio_empresa = objetoAjax();
			ajax4_espacio_empresa.open(
				"POST",
				"templates/transacciones/nota_credito_proforma/act_datos_nota_credito_prof.php",
				true
			);
			ajax4_espacio_empresa.onreadystatechange = function () {
				if (ajax4_espacio_empresa.readyState == 1) {
					espacio_empresa.innerHTML = "";
				} else if (ajax4_espacio_empresa.readyState == 4) {
					espacio_empresa.innerHTML = ajax4_espacio_empresa.responseText;
				}
			};
			ajax4_espacio_empresa.setRequestHeader(
				"Content-Type",
				"application/x-www-form-urlencoded"
			);
			ajax4_espacio_empresa.send(variable3);

			/// INICIO TIPO DE ALMACEN
			var tipo_almacen = document.getElementById("cbtipo_almacen");
			var varta = "";
			varta =
				"&codigoObra=" +
				codigoObra +
				"&codigoOrden=" +
				codigoOrden +
				"&tipo_almacen=1";
			ajax4_tipo_almacen = objetoAjax();
			ajax4_tipo_almacen.open(
				"POST",
				"templates/transacciones/nota_credito_proforma/act_datos_nota_credito_prof.php",
				true
			);
			ajax4_tipo_almacen.onreadystatechange = function () {
				if (ajax4_tipo_almacen.readyState == 1) {
					tipo_almacen.value = 0;
				} else if (ajax4_tipo_almacen.readyState == 4) {
					tipo_almacen.value = ajax4_tipo_almacen.responseText;
				}
			};
			ajax4_tipo_almacen.setRequestHeader(
				"Content-Type",
				"application/x-www-form-urlencoded"
			);
			ajax4_tipo_almacen.send(varta);
			/// FIN TIPO DE ALMACEN

			//espacio_cliente
			var espacio_cliente = document.getElementById("espacio_cliente");
			var serie_var = "";
			serie_var =
				"&codigoObra=" +
				codigoObra +
				"&codigoOrden=" +
				codigoOrden +
				"&espacio_cliente=9";
			ajax_espacio_cliente = objetoAjax();
			ajax_espacio_cliente.open(
				"POST",
				"templates/transacciones/nota_credito_proforma/act_datos_nota_credito_prof.php",
				true
			);
			ajax_espacio_cliente.onreadystatechange = function () {
				if (ajax_espacio_cliente.readyState == 1) {
					espacio_cliente.innerHTML = "";
				} else if (ajax_espacio_cliente.readyState == 4) {
					espacio_cliente.innerHTML = ajax_espacio_cliente.responseText;
				}
			};
			ajax_espacio_cliente.setRequestHeader(
				"Content-Type",
				"application/x-www-form-urlencoded"
			);
			ajax_espacio_cliente.send(serie_var);

			///moneda de la _factura
			var hidden_moneda_factura = document.getElementById("moneda_factura");
			var var_com = "";
			var_com =
				"&codigoObra=" +
				codigoObra +
				"&codigoOrden=" +
				codigoOrden +
				"&value_moneda_factura=4";
			ajax_moneda = objetoAjax();
			ajax_moneda.open(
				"POST",
				"templates/transacciones/nota_credito_proforma/act_datos_nota_credito_prof.php",
				true
			);
			ajax_moneda.onreadystatechange = function () {
				if (ajax_moneda.readyState == 1) {
					hidden_moneda_factura.value = "";
				} else if (ajax_moneda.readyState == 4) {
					var moneda_cambio_moneda = ajax_moneda.responseText;
					///simbolo_moneda el ID en el campo oculto
					hidden_moneda_factura.value = moneda_cambio_moneda.split("-")[0]; ///Aqui guarda en el Value Oculto

					///////Aqui pego el SIMBOLO DE LA MONEDA DE LA FACTURA
					document.getElementById("simbolo_moneda_factura").innerHTML =
						moneda_cambio_moneda.split("-")[1];

					///////Aqui pego el SIMBOLO DEL CAMBIO MONEDA DE LA FACTURA
					document.getElementById("simbolo_moneda").innerHTML =
						moneda_cambio_moneda.split("-")[2];
				}
			};
			ajax_moneda.setRequestHeader(
				"Content-Type",
				"application/x-www-form-urlencoded"
			);
			ajax_moneda.send(var_com);

			///tipo de cambio de la _factura
			var tipo_cambio_factura = document.getElementById("txt_parametro_cambio");
			var var_com = "";
			var_com =
				"&codigoObra=" +
				codigoObra +
				"&codigoOrden=" +
				codigoOrden +
				"&tipo_cambio_factura=4";
			ajax_tipo = objetoAjax();
			ajax_tipo.open(
				"POST",
				"templates/transacciones/nota_credito_proforma/act_datos_nota_credito_prof.php",
				true
			);
			ajax_tipo.onreadystatechange = function () {
				if (ajax_tipo.readyState == 1) {
					tipo_cambio_factura.value = "";
				} else if (ajax_tipo.readyState == 4) {
					tipo_cambio_factura.value = ajax_tipo.responseText;
				}
			};
			ajax_tipo.setRequestHeader(
				"Content-Type",
				"application/x-www-form-urlencoded"
			);
			ajax_tipo.send(var_com);

			///monto total  de la _factura
			var total_factura = document.getElementById("txt_total_factura");
			var var_tot = "";
			var_tot =
				"&codigoObra=" +
				codigoObra +
				"&codigoOrden=" +
				codigoOrden +
				"&total_factura_valida=4";
			ajax_total_factura = objetoAjax();
			ajax_total_factura.open(
				"POST",
				"templates/transacciones/nota_credito_proforma/act_datos_nota_credito_prof.php",
				true
			);
			ajax_total_factura.onreadystatechange = function () {
				if (ajax_total_factura.readyState == 1) {
					total_factura.value = "";
				} else if (ajax_total_factura.readyState == 4) {
					total_factura.value = ajax_total_factura.responseText;
				}
			};
			ajax_total_factura.setRequestHeader(
				"Content-Type",
				"application/x-www-form-urlencoded"
			);
			ajax_total_factura.send(var_tot);

			/////Calculando el valor del Numero de la Nota Credito.Abril'18////
			//obraCodFactura,  es el Negocio.
			var numero_notacredito = "";
			numero_notacredito =
				"genera_nota_credito=9&codigoObra=" +
				codigoObra +
				"&codigoOrden=" +
				codigoOrden;
			var Cmbord_Nota = document.getElementById("numero_nota");
			ajax_NumeroNota = objetoAjax();
			ajax_NumeroNota.open(
				"POST",
				"templates/transacciones/nota_credito_proforma/filtro_credito_prof.php",
				true
			);
			ajax_NumeroNota.onreadystatechange = function () {
				if (ajax_NumeroNota.readyState == 1) {
					Cmbord_Nota.value = "Cargando......";
				} else if (ajax_NumeroNota.readyState == 4) {
					var valida = "";
					valida = ajax_NumeroNota.responseText;

					if (valida == "FFF") {
						document.getElementById("numero_nota").readOnly = false;
						document.getElementById("numero_nota").value = "";
						document.getElementById("numero_nota").style.textAlign = "right";
						document.getElementById("numero_nota").style.backgroundColor = "";
					} else {
						document.getElementById("numero_nota").readOnly = true;
						document.getElementById("numero_nota").value =
							ajax_NumeroNota.responseText;
						document.getElementById("numero_nota").style.textAlign = "right";
						document.getElementById("numero_nota").style.backgroundColor =
							"#999";
					}
				}
			};
			ajax_NumeroNota.setRequestHeader(
				"Content-Type",
				"application/x-www-form-urlencoded"
			);
			ajax_NumeroNota.send(numero_notacredito);

			//}
		}
	};
	ajax_parVal.setRequestHeader(
		"Content-Type",
		"application/x-www-form-urlencoded"
	);
	ajax_parVal.send(parVal);
}

function insertar_fila_detalle_nota_credito_prof() {
	if ($F("codNegocio") == "0") {
		alert("Debe Elegir el Negocio.");
		document.getElementById("codNegocio").focus();
		return;
	}
	var codigoObra = $F("codNegocio");

	if ($F("txtInicio") == "") {
		alert("Debe Ingresar la Fecha de Emision de la Nota de Credito Proforma.");
		document.getElementById("txtInicio").focus();
		return;
	}
	var txtInicio = $F("txtInicio");

	if ($F("cod_motivo") == "") {
		alert("Debe Elegir el Motivo de la Nota de Credito Proforma.");
		document.getElementById("cod_motivo").focus();
		return;
	}
	var cod_motivo = $F("cod_motivo");

	var contador = 0;
	var hiddens = document.getElementsByName("puntero");
	for (var x = 0; x < hiddens.length; x++) contador = contador + 1;

	var nombrediv = document.getElementById("detalle_orden_nota_credito");
	var txtdiv = nombrediv.lastChild.id;

	if (typeof txtdiv == "undefined") {
		nDiv = document.createElement("div");
		nDiv.id = "divreg0";
		nDiv.style.width = "100%";
		container = document.getElementById("detalle_orden_nota_credito");
		container.appendChild(nDiv);
		contador = 0;
	} else if (typeof txtdiv != "undefined") {
		var long = txtdiv.length;
		var contador = txtdiv.substring(6, long);
	}

	var CompraDetalleDiv = document.getElementById("divreg" + contador);
	var variable = "contador=" + contador + "&codigoObra=" + codigoObra;
	ajax = objetoAjax();
	ajax.open(
		"POST",
		"templates/transacciones/nota_credito_proforma/filas_detalle_nota_credito_prof.php",
		true
	);
	ajax.onreadystatechange = function () {
		if (ajax.readyState == 1) {
			CompraDetalleDiv.innerHTML = img_carga_small;
		} else if (ajax.readyState == 4) {
			CompraDetalleDiv.innerHTML = ajax.responseText;
		}
	};
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send(variable);

	// crea un nuevo div con id distinto determinado para llenar un afila
	contador = parseInt(contador) + 1;
	nDiv = document.createElement("div");
	nDiv.id = "divreg" + contador;
	nDiv.style.width = "100%";
	container = document.getElementById("detalle_orden_nota_credito");
	container.appendChild(nDiv);

	//Al agregar nuevo item en Update limpiar el panel de busqueda
	if ($("editorden").disabled == false) {
		//limpiar el panel de busqueda
		$("cmbcodobra")[0].selected = true;
		var cmbordenes = $("cmbordenes");
		cmbordenes.length = 0;
		var nuevaOp = document.createElement("option");
		nuevaOp.value = 0;
		nuevaOp.innerHTML = "------------------------------";
		cmbordenes.appendChild(nuevaOp);
		$("verorden").disabled = true;
		$("editorden").disabled = true;
	}
}

function calcular_nota_credito_prof(numfila) {
	var cant = document.getElementById("txt_cantidad" + numfila).value;
	var punitario = document.getElementById("txt_punitario" + numfila).value;
	var monto = 0.0;

	if (Trim(punitario) == "") return;

	if (!validaDecimal(punitario)) {
		alert("* " + punitario + " decimal incorrecto. *\n-Formato v�lido: N.NN ");
		document.getElementById("txt_punitario" + numfila).focus();
		document.getElementById("txt_punitario" + numfila).value = "";
		return;
	}

	if (parseInt(cant) == 0 && parseFloat(punitario) == 0) {
		alert("El Precio Unitario no puede ser igual a cero.");
		document.getElementById("txt_punitario" + numfila).focus();
		document.getElementById("txt_punitario" + numfila).value = "";
		return;
	}

	var x = !x ? 2 : x;

	if (!isNaN(cant) && !isNaN(punitario)) {
		monto =
			Math.round(parseFloat(cant * punitario) * Math.pow(10, x)) /
			Math.pow(10, x);
		document.getElementById("txt_costo" + numfila).value = monto;
	}

	var contador = 0;
	var hiddens = document.getElementsByName("puntero");
	for (var j = 0; j < hiddens.length; j++) contador = contador + 1;
	var suma = 0.0;

	var punteros_filas = document.getElementsByName("puntero");
	var num_filas = document.getElementsByName("puntero").length;
	for (var k = 0; k < num_filas; k++) {
		var indice = punteros_filas[k].id;
		var monto_fila = document.getElementById("txt_costo" + indice).value;
		suma =
			suma +
			Math.round(parseFloat(monto_fila) * Math.pow(10, x)) / Math.pow(10, x);
	}

	//calculo del igv , obteniendo por defecto IGV
	//var valor_igv	=	document.getElementById("txt_parametro_igv").value;
	//document.getElementById("valorigv").value;

	document.getElementById("txt_subtotal").value =
		Math.round(parseFloat(suma) * Math.pow(10, x)) / Math.pow(10, x);

	var monto_subtotal = document.getElementById("txt_subtotal").value;
	var monto_neto =
		Math.round(parseFloat(monto_subtotal) * Math.pow(10, x)) / Math.pow(10, x);

	//var igv			=	valor_igv*monto_neto;
	//document.getElementById("igv").value = Math.round(parseFloat(igv)*Math.pow(10,x)) /  Math.pow(10,x);

	//calculo del monto total
	//var monto_igv = document.getElementById("igv").value;
	var monto_subtotal = document.getElementById("txt_subtotal").value;

	var temp_subtotal =
		Math.round(parseFloat(monto_subtotal) * Math.pow(10, x)) / Math.pow(10, x);

	document.getElementById("txt_total").value =
		Math.round(parseFloat(temp_subtotal) * Math.pow(10, x)) / Math.pow(10, x);

	// calculo en dolares
	var tipo_cambio = parseFloat(
		document.getElementById("txt_parametro_cambio").value
	);
	var monto_total =
		Math.round(
			parseFloat(document.getElementById("txt_total").value) * Math.pow(10, x)
		) / Math.pow(10, x);

	//capturando el tipo de moneda de la factura
	var valorMoneda = "";
	valorMoneda = document.getElementById("moneda_factura").value;

	if (valorMoneda == "D") {
		var dolares = monto_total * tipo_cambio;
	}
	if (valorMoneda == "S") {
		var dolares = monto_total / tipo_cambio;
	}
	document.getElementById("monto_al_cambio").value =
		Math.round(parseFloat(dolares) * Math.pow(10, x)) / Math.pow(10, x);
}

function eliminar_filas_nc_prof(id_fila) {
	var ByeDiv = document.getElementById("divreg" + id_fila);
	ByeDiv.parentNode.removeChild(ByeDiv);

	var cont = 0;
	var hiddens = document.getElementsByName("puntero");
	for (var j = 0; j < hiddens.length; j++) cont = cont + 1;
	if (cont == 0) {
		container = document.getElementById("detalle_orden_nota_credito");
		container.innerHTML = "";
		nDiv = document.createElement("div");
		nDiv.id = "divreg0";
		nDiv.style.width = "100%";
		container.appendChild(nDiv);
	}
}

function registrar_nota_credito_prof() {
	var codNegocio = document.getElementById("codNegocio").value;
	if ($F("codNegocio") == "0") {
		alert("Debe Elegir el Negocio.");
		document.getElementById("codNegocio").focus();
		return;
	}

	var order_factura = document.getElementById("order_factura").value;
	if ($F("order_factura") == "0") {
		alert("Debe Ingresar el Codigo de Orden de Proforma.");
		document.getElementById("order_factura").focus();
		return;
	}

	var dateInicio = document.getElementById("txtInicio").value;
	if ($F("txtInicio") == "" || !dateInicio) {
		alert("Debe Ingresar la Fecha de Emision de la Nota de Debito Proforma.");
		document.getElementById("txtInicio").focus();
		return;
	}

	var tipo_almacen = document.getElementById("cbtipo_almacen").value;
	if (tipo_almacen == "0") {
		alert("Debe Elegir el Tipo de Almacen.");
		document.getElementById("cbtipo_almacen").focus();
		return;
	}

	var cod_motivo = document.getElementById("cod_motivo").value;
	if ($F("cod_motivo") == "0") {
		alert("Debe Elegir el Motivo de la Nota de Credito Proforma.");
		document.getElementById("cod_motivo").focus();
		return;
	}

	//capturando los detalles
	var contador = 0;
	var hiddens = document.getElementsByName("puntero");
	for (var j = 0; j < hiddens.length; j++) contador = contador + 1;

	//ver si hay alguna fila en detalle
	if (contador == 0) {
		alert("No ha ingresado ningun item.");
		return;
	}

	var txt_subtotal = document.getElementById("txt_subtotal").value;
	if ($F("txt_subtotal") == "0") {
		alert("Debe calcular el Precio Total.");
		document.getElementById("txt_subtotal").focus();
		return;
	}

	var txt_total = document.getElementById("txt_total").value;

	var monto_al_cambio = document.getElementById("monto_al_cambio").value;
	if ($F("monto_al_cambio") == "0") {
		alert("Debe calcular el Monto al Cambio.");
		document.getElementById("monto_al_cambio").focus();
		return;
	}

	var numero_nota = document.getElementById("numero_nota").value;
	if ($F("numero_nota") == "") {
		alert("Debe Ingresar el Numero de la Nota de Credito Proforma.");
		document.getElementById("numero_nota").focus();
		return;
	}

	var comentario = document.form1.txt_comen_fac.value;
	var usuario = document.form1.usuario.value;

	var suma = 0.0;
	var punteros_filas = document.getElementsByName("puntero");
	var num_filas = document.getElementsByName("puntero").length;

	var arreglo = new Array();
	for (var k = 0; k < num_filas; k++) {
		var indice = punteros_filas[k].id;
		arreglo[k] = new Array();

		var cod = document.getElementById("" + indice).value;

		var txt_cantidad = document.getElementById("txt_cantidad" + indice).value;
		if (txt_cantidad == "" || txt_cantidad == "0") {
			alert(
				"Debe Ingresar la Cantidad no nula y mayor que cero para la Orden."
			);
			document.getElementById("txt_cantidad" + indice).focus();
			return;
		} else {
			if (!validaDecimal(txt_cantidad)) {
				alert(
					"* " + txt_cantidad + " decimal incorrecto. *\n-Formato v�lido: N.NN "
				);
				document.getElementById("txt_cantidad" + indice).focus();
				return false;
			}
		}

		var txt_und1 = document.getElementById("txt_und1" + indice).value;
		if (txt_und1 == "0") {
			alert("Debe Seleccionar la Unidad de la Cantidad a Ingresar.");
			document.getElementById("txt_und1" + indice).focus();
			return;
		}

		var txt_descripcion = document.getElementById(
			"txt_descripcion" + indice
		).value;
		if (txt_descripcion == "") {
			alert("Debe Ingresar la Glosa de la Nota de Credito.");
			document.getElementById("txt_descripcion" + indice).focus();
			return;
		}

		var txt_punitario = document.getElementById("txt_punitario" + indice).value;
		if (txt_punitario == "" || txt_punitario == "0") {
			alert("Debe Ingresar el Precio Unitario.");
			document.getElementById("txt_punitario" + indice).focus();
			return;
		} else {
			if (!validaDecimal(txt_punitario)) {
				alert(
					"* " +
						txt_punitario +
						" decimal incorrecto. *\n-Formato v�lido: N.NN "
				);
				document.getElementById("txt_punitario" + indice).focus();
				return false;
			}
		}

		var txt_costo = document.getElementById("txt_costo" + indice).value;

		arreglo[k][0] = txt_cantidad;
		arreglo[k][1] = txt_und1;
		arreglo[k][2] = txt_descripcion;
		arreglo[k][3] = txt_punitario;
		arreglo[k][4] = txt_costo;
		arreglo[k][5] = cod;
	}

	var txt_total_factura = document.getElementById("txt_total_factura").value;

	//ENE 2022 SI no esxite este campo ocuplto, es flujo
	if (!document.getElementById("OBVIAR_VALIDACION")) {
		if (parseFloat(txt_total_factura) < parseFloat(txt_total)) {
			alert(
				"El total de la NOTA CRED. " +
					txt_total +
					" no puede ser mayor que la Factura. " +
					txt_total_factura
			);
			return;
		}
	}

	var label = "";
	if ($("nota_credito_prof")) {
		label = "Registrar";
	} else if ($("nota_credito_prof2")) {
		label = "Actualizar";
	}

	var autoriza;
	var b = confirm("¿Desea " + label + " la Nota de Credito Proforma?.");
	if (b == false) return;

	var info_servicio = "";
	/// guardar
	if ($("nota_credito_prof")) {
		var info_servicio =
			"&arreglo=" +
			arreglo +
			"&codNegocio=" +
			codNegocio +
			"&dateInicio=" +
			dateInicio +
			"&cod_motivo=" +
			cod_motivo +
			"&txt_subtotal=" +
			txt_subtotal +
			"&usuario=" +
			usuario +
			"&comentario=" +
			comentario +
			"&monto_total=" +
			txt_total +
			"&monto_al_cambio=" +
			monto_al_cambio +
			"&numero_nota=" +
			numero_nota +
			"&order_factura=" +
			order_factura +
			"&tipo_almacen=" +
			tipo_almacen +
			"&insertar=1";
	} else if ($("nota_credito_prof2")) {
		///update
		var OrdenComp = document.getElementById("nota_credito_prof2").innerHTML;
		//   O.C. N� 0000001-CKM
		var posicion1 = OrdenComp.indexOf("0"); // posicion = 8
		var posicion2 = OrdenComp.indexOf("-"); // posicion = 14
		var porcion = OrdenComp.substring(posicion1, posicion2 + 1); // porcion = "000001"
		var codigoOrden = parseInt(porcion, 10);

		var info_servicio =
			"&arreglo=" +
			arreglo +
			"&codigoNota=" +
			codigoOrden +
			"&codNegocio=" +
			codNegocio +
			"&dateInicio=" +
			dateInicio +
			"&cod_motivo=" +
			cod_motivo +
			"&txt_subtotal=" +
			txt_subtotal +
			"&usuario=" +
			usuario +
			"&comentario=" +
			comentario +
			"&monto_total=" +
			txt_total +
			"&monto_al_cambio=" +
			monto_al_cambio +
			"&numero_nota=" +
			numero_nota +
			"&order_factura=" +
			order_factura +
			"&tipo_almacen=" +
			tipo_almacen +
			"&update=1";
	}

	ajax_guardar = objetoAjax();
	ajax_guardar.open(
		"POST",
		"templates/transacciones/nota_credito_proforma/lista_nota_credito_prof.php",
		true
	);
	ajax_guardar.onreadystatechange = function () {
		if (ajax_guardar.readyState == 1) {
			$("PRUEBA").innerHTML = "";
		} else if (ajax_guardar.readyState == 4) {
			$("PRUEBA").innerHTML = ajax_guardar.responseText;
		}
	};

	ajax_guardar.setRequestHeader(
		"Content-Type",
		"application/x-www-form-urlencoded"
	);
	ajax_guardar.send(info_servicio);

	///Luego de grabar inhabilitar el formulario  para edicion e impresion
	$("codNegocio").disabled = true;

	//solo para el caso de Guardar
	if ($("nota_credito_prof")) {
		$("nota_credito_prof").id = "nota_credito_prof2";
	}

	$("cod_motivo").disabled = true;
	$("txtInicio").disabled = true;
	$("order_factura").disabled = true;
	$("cbtipo_almacen").disabled = true;

	//Inhabilitando las filas
	var punteros_filas = document.getElementsByName("puntero");
	var num_filas = document.getElementsByName("puntero").length;
	for (var x = 0; x < num_filas; x++) {
		var indice = punteros_filas[x].id;
		$("txt_cantidad" + indice).disabled = true;
		$("txt_und1" + indice).disabled = true;
		$("txt_descripcion" + indice).disabled = true;
		$("txt_punitario" + indice).disabled = true;
		$("txt_costo" + indice).disabled = true;
	}

	// document.getElementById("model_nc").disabled = false;
	document.getElementById("guardar").disabled = true;
	document.getElementById("item").disabled = true;
	document.getElementById("txt_comen_fac").disabled = true;
	document.getElementById("numero_nota").disabled = true;
	document.getElementById("txt_total_factura").value = "";
}

function nuevo_documento_nc_prof() {
	var a = confirm("¿Desea Generar un nuevo Documento?.");
	if (a == false) return;

	//Regresando el estado original del div de seria de orden
	if ($("nota_credito_prof2")) $("nota_credito_prof2").id = "nota_credito_prof";

	$("item").disabled = false;

	////////////Abril2018
	document.getElementById("numero_nota").readOnly = false;
	document.getElementById("numero_nota").style.textAlign = "right";
	document.getElementById("numero_nota").style.backgroundColor = "";
	////////////

	document.getElementById("numero_nota").disabled = false;
	document.getElementById("numero_nota").value = "";
	document.getElementById("cod_motivo")[0].selected = true;
	document.getElementById("cod_motivo").disabled = false;
	document.getElementById("txt_comen_fac").disabled = false;
	document.getElementById("txt_comen_fac").value = "*";
	document.getElementById("txt_comen_fac").innerHTML = "*";

	document.getElementById("txt_subtotal").disabled = false;
	document.getElementById("txt_subtotal").value = "";
	document.getElementById("txtInicio").disabled = false;
	document.getElementById("txtInicio").value = "";
	document.getElementById("order_factura").disabled = false;
	document.getElementById("order_factura").value = "";

	document.getElementById("txt_parametro_cambio").value = "";
	document.getElementById("txt_total").disabled = false;
	document.getElementById("txt_total").value = "";
	document.getElementById("monto_al_cambio").disabled = false;
	document.getElementById("monto_al_cambio").value = "";
	document.getElementById("cbtipo_almacen").disabled = false;
	document.getElementById("cbtipo_almacen").value = "0";

	//nuevos inputs
	document.getElementById("espacio_cliente").innerHTML =
		"Ingrese Ord. Proforma.";
	document.getElementById("espacio_factura_fisica").innerHTML =
		"Ingrese Ord. Proforma.";
	document.getElementById("espacio_empresa").innerHTML =
		"Ingrese Ord. Proforma.";

	///Limpiar los simbolos de moneda de la factura y del cambio
	document.getElementById("simbolo_moneda_factura").innerHTML = "";
	document.getElementById("simbolo_moneda").innerHTML = "";

	//no mover el orden(Esto selecciona el negocio en cero)
	document.getElementById("codNegocio").disabled = false;
	document.form1.codNegocio[0].selected = true;

	///limpia el numero de orden
	document.getElementById("nota_credito_prof").innerHTML = "N.C. N&ordm;";

	//Limpiando los detalles
	container = document.getElementById("detalle_orden_nota_credito");
	container.innerHTML = "";
	nDiv = document.createElement("div");
	nDiv.id = "divreg0";
	nDiv.style.width = "100%";
	container.appendChild(nDiv);

	//inhabilitanbdo el imprimir
	// document.getElementById("model_nc").disabled = true;
	document.getElementById("bus_ingreso").disabled = false;
	document.getElementById("guardar").disabled = false;
	document.getElementById("item").disabled = false;

	//limpiar el panel de busqueda
	document.getElementById("cmbcodobra")[0].selected = true;

	var cmbordenes = document.getElementById("cmbordenes");
	cmbordenes.length = 0;
	var nuevaOp = document.createElement("option");
	nuevaOp.value = 0;
	nuevaOp.innerHTML = "&nbsp;&nbsp;&nbsp;";
	cmbordenes.appendChild(nuevaOp);

	document.getElementById("verorden").disabled = true;
	document.getElementById("editorden").disabled = true;
	document.getElementById("guardar").innerHTML =
		"<img width='15' height='15' src='images/btn_guardar.png' align='absmiddle'>&nbsp;Guardar Documento";
	document.getElementById("eliorden").disabled = true;
}

function buscar_ordenes_nc_prof() {
	var codigoNegocio = $F("cmbcodobra");

	if (codigoNegocio == "0") {
		alert("Debe escoger el Negocio.");
		document.getElementById("cmbcodobra").focus();
		$("verorden").disabled = true;
		$("editorden").disabled = true;
		return;
	}

	var cmbus_anio = $F("cmbus_anio");
	var cmbus_mes = $F("cmbus_mes");

	var variable =
		"buscarorden=2&codigoNegocio=" +
		codigoNegocio +
		"&cmbus_anio=" +
		cmbus_anio +
		"&cmbus_mes=" +
		cmbus_mes;
	var Ordenes = document.getElementById("cmbordenes");
	ajax_busc_orden_comp = objetoAjax();
	ajax_busc_orden_comp.open(
		"POST",
		"templates/transacciones/nota_credito_proforma/filtro_credito_prof.php",
		true
	);
	ajax_busc_orden_comp.onreadystatechange = function () {
		if (ajax_busc_orden_comp.readyState == 1) {
			Ordenes.length = 0;
			var nuevaOpcion = document.createElement("option");
			nuevaOpcion.value = 0;
			nuevaOpcion.innerHTML = "Cargando......";
			Ordenes.appendChild(nuevaOpcion);
		} else if (ajax_busc_orden_comp.readyState == 4) {
			Ordenes.parentNode.innerHTML = ajax_busc_orden_comp.responseText;
		}
	};
	ajax_busc_orden_comp.setRequestHeader(
		"Content-Type",
		"application/x-www-form-urlencoded"
	);
	ajax_busc_orden_comp.send(variable);

	document.form1.cmbordenes.focus();
	$("verorden").disabled = true;
	$("editorden").disabled = true;
}

function activar_verorden_nc_prof(value) {
	if (value == "0") {
		$("verorden").disabled = true;
		$("verorden").focus = true;
		$("editorden").disabled = true;
	} else $("verorden").disabled = false;
}

function ver_orden_nc_prof() {
	var codigoObra = $F("cmbcodobra");
	var codigoOrden = $F("cmbordenes");
	var container = document.getElementById("detalle_orden_nota_credito");
	container.innerHTML = "";

	if (codigoObra == "0") {
		alert("Debe escoger el Negocio.");
		document.getElementById("cmbcodobra").focus();
		return;
	}
	document.getElementById("order_factura").disabled = true;
	document.getElementById("order_factura").value = "";
	/////////////Abril,2018
	document.getElementById("numero_nota").readOnly = false;
	document.getElementById("numero_nota").style.textAlign = "right";
	document.getElementById("numero_nota").style.backgroundColor = "";
	/////////////
	document.getElementById("bus_ingreso").disabled = true;
	document.getElementById("editorden").disabled = false;
	// document.getElementById("model_nc").disabled = false;
	document.getElementById("item").disabled = true; //SEGUN YO MEJOR NO AGREGAR ITEM, QUE ELIMINE LA NOTA

	//Cambiando el nombre del boton de guardar a actualizar.
	var boton_save = document.getElementById("guardar");
	boton_save.disabled = false;
	boton_save.innerHTML =
		"<img width='15' height='15' src='images/btn_guardar.png' align='absmiddle'>&nbsp;Actualizar Documento";
	boton_save.disabled = true;
	//Cambiando el contenedor de la serie de orden y dejando el de la seria buscada
	if ($("nota_credito_prof")) $("nota_credito_prof").id = "nota_credito_prof2";

	var index = document.form1.cmbordenes.selectedIndex;
	var serie = document.form1.cmbordenes[index].innerHTML;
	document.getElementById("nota_credito_prof2").innerHTML = "" + serie + "";

	//Seleccionando en la obra buscada en la cabecera e inhabilitando....
	var indexObra = document.form1.cmbcodobra.selectedIndex;
	document.form1.codNegocio[indexObra].selected = true;
	document.getElementById("codNegocio").disabled = true;

	//// Enviando peticion para traer las filas
	var update = "";
	update = "&codigoObra=" + codigoObra + "&codigoOrden=" + codigoOrden;
	ajax = objetoAjax();
	ajax.open(
		"POST",
		"templates/transacciones/nota_credito_proforma/filas_update_nota_credito_prof.php",
		true
	);
	ajax.onreadystatechange = function () {
		if (ajax.readyState == 1) {
			container.innerHTML = img_carga2;
		} else if (ajax.readyState == 4) {
			container.innerHTML = ajax.responseText;
		}
	};
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send(update);

	///EMPRESA
	var espacio_empresa = document.getElementById("espacio_empresa");
	var variable2 = "";
	variable2 =
		"codigoObra=" +
		codigoObra +
		"&codigoOrden=" +
		codigoOrden +
		"&update_espacio_empresa=3";
	ajax3_esp_emp = objetoAjax();
	ajax3_esp_emp.open(
		"POST",
		"templates/transacciones/nota_credito_proforma/act_datos_nota_credito_prof.php",
		true
	);
	ajax3_esp_emp.onreadystatechange = function () {
		if (ajax3_esp_emp.readyState == 1) {
			espacio_empresa.innerHTML = "";
		} else if (ajax3_esp_emp.readyState == 4) {
			espacio_empresa.innerHTML = ajax3_esp_emp.responseText;
		}
	};
	ajax3_esp_emp.setRequestHeader(
		"Content-Type",
		"application/x-www-form-urlencoded"
	);
	ajax3_esp_emp.send(variable2);

	///CLIENTE.
	var espacio_cliente = document.getElementById("espacio_cliente");
	var serie_var = "";
	serie_var =
		"codigoObra=" +
		codigoObra +
		"&codigoOrden=" +
		codigoOrden +
		"&update_espacio_cliente=9";
	ajax_espacio_cliente = objetoAjax();
	ajax_espacio_cliente.open(
		"POST",
		"templates/transacciones/nota_credito_proforma/act_datos_nota_credito_prof.php",
		true
	);
	ajax_espacio_cliente.onreadystatechange = function () {
		if (ajax_espacio_cliente.readyState == 1) {
			espacio_cliente.innerHTML = "";
		} else if (ajax_espacio_cliente.readyState == 4) {
			espacio_cliente.innerHTML = ajax_espacio_cliente.responseText;
		}
	};
	ajax_espacio_cliente.setRequestHeader(
		"Content-Type",
		"application/x-www-form-urlencoded"
	);
	ajax_espacio_cliente.send(serie_var);

	///txtInicio
	var txtInicio = document.getElementById("txtInicio");
	var variable3 = "";
	variable3 =
		"&codigoObra=" +
		codigoObra +
		"&codigoOrden=" +
		codigoOrden +
		"&fecha_emision_nota=4";
	ajax4 = objetoAjax();
	ajax4.open(
		"POST",
		"templates/transacciones/nota_credito_proforma/act_datos_nota_credito_prof.php",
		true
	);
	ajax4.onreadystatechange = function () {
		if (ajax4.readyState == 1) {
			txtInicio.value = "";
		} else if (ajax4.readyState == 4) {
			txtInicio.value = ajax4.responseText;
			document.getElementById("txtInicio").disabled = true;
		}
	};
	ajax4.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax4.send(variable3);

	////traer la motivo
	var cod_motivo = document.getElementById("cod_motivo");
	var variable4 = "";
	variable4 =
		"&codigoObra=" + codigoObra + "&codigoOrden=" + codigoOrden + "&motivo=4";
	ajax5 = objetoAjax();
	ajax5.open(
		"POST",
		"templates/transacciones/nota_credito_proforma/act_datos_nota_credito_prof.php",
		true
	);
	ajax5.onreadystatechange = function () {
		if (ajax5.readyState == 1) {
			cod_motivo.length = 0;
			var nuevaOpcion = document.createElement("option");
			nuevaOpcion.value = 0;
			nuevaOpcion.innerHTML = "Cargando......";
			cod_motivo.appendChild(nuevaOpcion);
		} else if (ajax5.readyState == 4) {
			cod_motivo.parentNode.innerHTML = ajax5.responseText;
			document.getElementById("cod_motivo").disabled = true;
		}
	};
	ajax5.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax5.send(variable4);

	//numero_nota
	var numero_nota = document.getElementById("numero_nota");
	var serie_var = "";
	serie_var =
		"&codigoObra=" +
		codigoObra +
		"&codigoOrden=" +
		codigoOrden +
		"&numero_nota=9";
	ajax_serie = objetoAjax();
	ajax_serie.open(
		"POST",
		"templates/transacciones/nota_credito_proforma/act_datos_nota_credito_prof.php",
		true
	);
	ajax_serie.onreadystatechange = function () {
		if (ajax_serie.readyState == 1) {
			numero_nota.value = "";
		} else if (ajax_serie.readyState == 4) {
			numero_nota.value = ajax_serie.responseText;
			document.getElementById("numero_nota").disabled = true;
		}
	};
	ajax_serie.setRequestHeader(
		"Content-Type",
		"application/x-www-form-urlencoded"
	);
	ajax_serie.send(serie_var);

	///espacio y factura fisica
	var espacio_factura_fisica = document.getElementById(
		"espacio_factura_fisica"
	);
	var variable7 = "";
	variable7 =
		"&codigoObra=" +
		codigoObra +
		"&codigoOrden=" +
		codigoOrden +
		"&update_espacio_factura_fisica=4";
	ajax9 = objetoAjax();
	ajax9.open(
		"POST",
		"templates/transacciones/nota_credito_proforma/act_datos_nota_credito_prof.php",
		true
	);
	ajax9.onreadystatechange = function () {
		if (ajax9.readyState == 1) {
			espacio_factura_fisica.innerHTML = "Cargando.....";
		} else if (ajax9.readyState == 4) {
			espacio_factura_fisica.innerHTML = ajax9.responseText;
		}
	};
	ajax9.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax9.send(variable7);

	///Traer el Comentario
	var comentario_ord = document.getElementById("txt_comen_fac");
	var var_com = "";
	var_com =
		"&codigoObra=" +
		codigoObra +
		"&codigoOrden=" +
		codigoOrden +
		"&comentario=4";
	ajax_comen = objetoAjax();
	ajax_comen.open(
		"POST",
		"templates/transacciones/nota_credito_proforma/act_datos_nota_credito_prof.php",
		true
	);
	ajax_comen.onreadystatechange = function () {
		if (ajax_comen.readyState == 1) {
			comentario_ord.value = "Cargando......";
		} else if (ajax_comen.readyState == 4) {
			comentario_ord.value = ajax_comen.responseText;
			document.getElementById("txt_comen_fac").disabled = true;
		}
	};
	ajax_comen.setRequestHeader(
		"Content-Type",
		"application/x-www-form-urlencoded"
	);
	ajax_comen.send(var_com);

	///Traer TIPO DE ALMACEN
	var tipo_alma = document.getElementById("cbtipo_almacen");
	var var_ta = "";
	var_ta =
		"&codigoObra=" +
		codigoObra +
		"&codigoOrden=" +
		codigoOrden +
		"&tipo_almacen_upd=1";
	ajax_ta = objetoAjax();
	ajax_ta.open(
		"POST",
		"templates/transacciones/nota_credito_proforma/act_datos_nota_credito_prof.php",
		true
	);
	ajax_ta.onreadystatechange = function () {
		if (ajax_ta.readyState == 1) {
			tipo_alma.value = "0";
		} else if (ajax_ta.readyState == 4) {
			tipo_alma.value = ajax_ta.responseText;
			tipo_alma.disabled = true;
		}
	};
	ajax_ta.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax_ta.send(var_ta);

	///Traer el SUBTOTAL
	var txt_subtotal = document.getElementById("txt_subtotal");
	var var_marca = "";
	var_marca =
		"&codigoObra=" + codigoObra + "&codigoOrden=" + codigoOrden + "&subtotal=4";
	ajax_marcaplaca = objetoAjax();
	ajax_marcaplaca.open(
		"POST",
		"templates/transacciones/nota_credito_proforma/act_datos_nota_credito_prof.php",
		true
	);
	ajax_marcaplaca.onreadystatechange = function () {
		if (ajax_marcaplaca.readyState == 1) {
			txt_subtotal.value = "Cargando......";
		} else if (ajax_marcaplaca.readyState == 4) {
			txt_subtotal.value = ajax_marcaplaca.responseText;
		}
	};
	ajax_marcaplaca.setRequestHeader(
		"Content-Type",
		"application/x-www-form-urlencoded"
	);
	ajax_marcaplaca.send(var_marca);

	///Traer el TOTAL
	var txt_total = document.getElementById("txt_total");
	var var_lic = "";
	var_lic =
		"&codigoObra=" + codigoObra + "&codigoOrden=" + codigoOrden + "&total=4";
	ajax_licencia = objetoAjax();
	ajax_licencia.open(
		"POST",
		"templates/transacciones/nota_credito_proforma/act_datos_nota_credito_prof.php",
		true
	);
	ajax_licencia.onreadystatechange = function () {
		if (ajax_licencia.readyState == 1) {
			txt_total.value = "Cargando......";
		} else if (ajax_licencia.readyState == 4) {
			txt_total.value = ajax_licencia.responseText;
		}
	};
	ajax_licencia.setRequestHeader(
		"Content-Type",
		"application/x-www-form-urlencoded"
	);
	ajax_licencia.send(var_lic);

	///Traer el CAMBIO
	var monto_al_cambio = document.getElementById("monto_al_cambio");
	var var_cam = "";
	var_cam =
		"&codigoObra=" + codigoObra + "&codigoOrden=" + codigoOrden + "&cambio=4";
	ajax_cambio = objetoAjax();
	ajax_cambio.open(
		"POST",
		"templates/transacciones/nota_credito_proforma/act_datos_nota_credito_prof.php",
		true
	);
	ajax_cambio.onreadystatechange = function () {
		if (ajax_cambio.readyState == 1) {
			monto_al_cambio.value = "Cargando......";
		} else if (ajax_cambio.readyState == 4) {
			monto_al_cambio.value = ajax_cambio.responseText;
		}
	};
	ajax_cambio.setRequestHeader(
		"Content-Type",
		"application/x-www-form-urlencoded"
	);
	ajax_cambio.send(var_cam);

	///moneda de la _factura
	var hidden_moneda_factura = document.getElementById("moneda_factura");
	var var_com = "";
	var_com =
		"&codigoObra=" +
		codigoObra +
		"&codigoOrden=" +
		codigoOrden +
		"&upd_moneda_factura=4";
	ajax_moneda = objetoAjax();
	ajax_moneda.open(
		"POST",
		"templates/transacciones/nota_credito_proforma/act_datos_nota_credito_prof.php",
		true
	);
	ajax_moneda.onreadystatechange = function () {
		if (ajax_moneda.readyState == 1) {
			hidden_moneda_factura.value = "";
		} else if (ajax_moneda.readyState == 4) {
			var moneda_cambio_moneda = ajax_moneda.responseText;
			hidden_moneda_factura.value = moneda_cambio_moneda.split("-")[0];
			document.getElementById("simbolo_moneda").innerHTML =
				moneda_cambio_moneda.split("-")[1];
			document.getElementById("simbolo_moneda_factura").innerHTML =
				moneda_cambio_moneda.split("-")[2];
		}
	};
	ajax_moneda.setRequestHeader(
		"Content-Type",
		"application/x-www-form-urlencoded"
	);
	ajax_moneda.send(var_com);

	///tipo de cambio de la _factura
	var tipo_cambio_factura = document.getElementById("txt_parametro_cambio");
	var var_com = "";
	var_com =
		"&codigoObra=" +
		codigoObra +
		"&codigoOrden=" +
		codigoOrden +
		"&upd_tipo_cambio_factura=4";
	ajax_tipo = objetoAjax();
	ajax_tipo.open(
		"POST",
		"templates/transacciones/nota_credito_proforma/act_datos_nota_credito_prof.php",
		true
	);
	ajax_tipo.onreadystatechange = function () {
		if (ajax_tipo.readyState == 1) {
			tipo_cambio_factura.value = "";
		} else if (ajax_tipo.readyState == 4) {
			tipo_cambio_factura.value = ajax_tipo.responseText;
		}
	};
	ajax_tipo.setRequestHeader(
		"Content-Type",
		"application/x-www-form-urlencoded"
	);
	ajax_tipo.send(var_com);
}

function editar_orden_nc_prof() {
	//si es nivel personal adm. puede editar
	//habilitando el form para actualizar
	document.getElementById("codNegocio").disabled = true;
	document.getElementById("guardar").disabled = false;
	document.getElementById("txtInicio").disabled = false;
	document.getElementById("txt_comen_fac").disabled = false;
	// document.getElementById("model_nc").disabled = false;
	document.getElementById("cod_motivo").disabled = false;
	document.getElementById("cbtipo_almacen").disabled = false;

	///Creo que no se deberia permitir agregar un item mas en modificar, mejor que se cree otra nota.
	document.getElementById("item").disabled = true;
	document.getElementById("eliorden").disabled = false;

	document.getElementById("bus_ingreso").disabled = true;
	//Habiliatando las filas del detalle
	var punteros_filas = document.getElementsByName("puntero");
	var num_filas = document.getElementsByName("puntero").length;
	for (var x = 0; x < num_filas; x++) {
		var indice = punteros_filas[x].id;
		$("txt_cantidad" + indice).disabled = false;
		$("txt_und1" + indice).disabled = false;
		$("txt_descripcion" + indice).disabled = false;
		$("txt_punitario" + indice).disabled = false;
		$("txt_costo" + indice).disabled = false;
	}
}

function pinta_obra_codif_nc_prof(valor) {
	var codNegocio = document.getElementById("codNegocio").value;
	var Destino = document.getElementById("nota_credito_prof");
	var variable = "n_nota_credito_prof=1&codNegocio=" + valor;
	ajax_codif_nc = objetoAjax();
	ajax_codif_nc.open(
		"POST",
		"templates/transacciones/nota_credito_proforma/filtro_credito_prof.php",
		true
	);
	ajax_codif_nc.onreadystatechange = function () {
		if (ajax_codif_nc.readyState == 1) {
			Destino.innerHTML = "Generando.......";
		} else if (ajax_codif_nc.readyState == 4) {
			Destino.innerHTML = ajax_codif_nc.responseText;
		}
	};
	ajax_codif_nc.setRequestHeader(
		"Content-Type",
		"application/x-www-form-urlencoded"
	);
	ajax_codif_nc.send(variable);
}

function eliminar_nota_nc_prof() {
	var obraCodigo = document.form1.codNegocio.value;
	var OrdenComp = document.getElementById("nota_credito_prof2").innerHTML;
	var usuario = document.form1.usuario.value;

	OrdenComp = OrdenComp.replace("&nbsp;", " ");
	var a = confirm(
		"¿Esta Ud. Realmente seguro de Eliminar este Documento " + OrdenComp + " ?."
	);
	if (a == false) return;

	//   O.S. N� 0000001-CKM
	var posicion1 = OrdenComp.indexOf("0"); // posicion = 8
	var posicion2 = OrdenComp.indexOf("-"); // posicion = 14
	var porcion = OrdenComp.substring(posicion1, posicion2 + 1); // porcion = "000001"
	var codigoOrden = parseInt(porcion, 10);

	var par = "";
	par =
		"&usuario=" +
		usuario +
		"&codigoObra=" +
		obraCodigo +
		"&codigoOrden=" +
		codigoOrden +
		"&delete_orden=2";
	ajax_sup = objetoAjax();
	ajax_sup.open(
		"POST",
		"templates/transacciones/nota_credito_proforma/act_datos_nota_credito_prof.php",
		true
	);
	ajax_sup.onreadystatechange = function () {
		if (ajax_sup.readyState == 1) {
			$("PRUEBA").innerHTML = "";
		} else if (ajax_sup.readyState == 4) {
			$("PRUEBA").innerHTML = ajax_sup.responseText;
		}
	};
	ajax_sup.setRequestHeader(
		"Content-Type",
		"application/x-www-form-urlencoded"
	);
	ajax_sup.send(par);

	if ($("nota_credito_prof2")) $("nota_credito_prof2").id = "nota_credito_prof";

	$("item").disabled = false;
	document.getElementById("numero_nota").disabled = false;
	document.getElementById("numero_nota").value = "";
	document.getElementById("cod_motivo")[0].selected = true;
	document.getElementById("cod_motivo").disabled = false;
	document.getElementById("txt_comen_fac").disabled = false;
	document.getElementById("txt_comen_fac").value = "*";
	document.getElementById("txt_comen_fac").innerHTML = "*";
	document.getElementById("txt_subtotal").disabled = false;
	document.getElementById("txt_subtotal").value = "";
	document.getElementById("txt_total").disabled = false;
	document.getElementById("txt_total").value = "";
	document.getElementById("monto_al_cambio").disabled = false;
	document.getElementById("monto_al_cambio").value = "";
	document.getElementById("cbtipo_almacen").disabled = false;
	document.getElementById("cbtipo_almacen").value = "0";

	document.getElementById("txtInicio").value = "";
	document.getElementById("txtInicio").disabled = false;

	document.getElementById("txt_parametro_cambio").value = "";

	//nuevos inputs
	document.getElementById("espacio_cliente").innerHTML =
		"Ingrese Ord. Proforma.";
	document.getElementById("espacio_factura_fisica").innerHTML =
		"Ingrese Ord. Proforma.";
	document.getElementById("espacio_empresa").innerHTML =
		"Ingrese Ord. Proforma.";

	//no mover el orden  (Esto selecciona el negocio en cero)
	document.getElementById("codNegocio").disabled = false;
	document.form1.codNegocio[0].selected = true;

	///limpia el numero de orden
	document.getElementById("nota_credito_prof").innerHTML = "N.C. N&ordm;";

	//Limpiando los detalles
	container = document.getElementById("detalle_orden_nota_credito_prof");
	container.innerHTML = "";
	nDiv = document.createElement("div");
	nDiv.id = "divreg0";
	nDiv.style.width = "100%";
	container.appendChild(nDiv);

	// document.getElementById("model_nc").disabled = true;
	document.getElementById("guardar").disabled = false;
	document.getElementById("item").disabled = false;

	//limpiar el panel de busqueda
	document.getElementById("cmbcodobra")[0].selected = true;

	var cmbordenes = document.getElementById("cmbordenes");
	cmbordenes.length = 0;
	var nuevaOp = document.createElement("option");
	nuevaOp.value = 0;
	nuevaOp.innerHTML = "&nbsp;&nbsp;&nbsp;";
	cmbordenes.appendChild(nuevaOp);

	document.getElementById("verorden").disabled = true;
	document.getElementById("editorden").disabled = true;
	document.getElementById("guardar").innerHTML =
		"<img width='15' height='15' src='images/btn_guardar.png' align='absmiddle'>&nbsp;Guardar Documento";
	document.getElementById("eliorden").disabled = true;
}

function imprimir_ncp() {
	if ($F("codNegocio") == "0") {
		alert("Debe generar una Nota de Credito Proforma.");
		return;
	}

	var obraCodigo = document.form1.codNegocio.value;
	var OrdenComp = document.getElementById("nota_credito_prof2").innerHTML;

	if (!$("verorden").disabled == false) {
		//   O.C. N� 000001-CKM
		var posicion1 = OrdenComp.indexOf("0"); // posicion = 8
		var posicion2 = OrdenComp.indexOf("-"); // posicion = 14
		var porcion = OrdenComp.substring(posicion1, posicion2 + 1); // porcion = "000001"
		var CodOrdComp = parseInt(porcion, 10);
	} else {
		CodOrdComp = document.getElementById("cmbordenes").value;
		obraCodigo = document.getElementById("cmbcodobra").value;
	}

	document.getElementById("CodOrdComp").value = CodOrdComp;
	document.getElementById("CodObra").value = obraCodigo;

	///alert(obraCodigo+"   "+CodOrdComp+"   "+cmbServicio);

	window.open(
		"plantilla/xpdf_notadecredito_prof.php?cneg=" +
			base64_encode(obraCodigo) +
			"&codncprof=" +
			base64_encode(CodOrdComp.toString()),
		"REPORTE",
		"resizable=yes,scrollbars=yes",
		false
	);
}
