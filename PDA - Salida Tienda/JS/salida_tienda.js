function calcular_pesos_stda() {
	var x = 4;
	var contador = 0;
	var hiddens = document.getElementsByName("puntero");
	for (var j = 0; j < hiddens.length; j++) contador = contador + 1;
	var suma_rollos = 0.0;
	var suma_kg = 0.0;

	var punteros_filas = document.getElementsByName("puntero");
	var num_filas = punteros_filas.length;

	for (var k = 0; k < num_filas; k++) {
		var indice = punteros_filas[k].id;

		var inputRollos = document.getElementById("txt_rollos" + indice);
		var inputPeso = document.getElementById("txt_peso" + indice);

		if (inputRollos && inputPeso) {
			var cant_rollos = parseFloat(inputRollos.value) || 0;
			var peso_kg = parseFloat(inputPeso.value) || 0;

			suma_rollos +=
				Math.round(cant_rollos * Math.pow(10, x)) / Math.pow(10, x);
			suma_kg += Math.round(peso_kg * Math.pow(10, x)) / Math.pow(10, x);
		}
	}

	var totalRollos = document.getElementById("txt_total_rollos");
	var totalKg = document.getElementById("txt_totalkg");

	if (totalRollos) {
		totalRollos.value =
			Math.round(suma_rollos * Math.pow(10, x)) / Math.pow(10, x);
	}

	if (totalKg) {
		totalKg.value = Math.round(suma_kg * Math.pow(10, x)) / Math.pow(10, x);
	}
}

function reordenarFilas() {
	const filas = document.querySelectorAll("#contenedor_detalle tr");
	let nuevoIndice = 0;

	filas.forEach((fila) => {
		// 📌 NOTA: Actualiza el ID
		fila.id = `divreg${nuevoIndice}`;

		// 📌 NOTA: Actualiza el ID en los input
		const inputs = fila.querySelectorAll("input");
		inputs.forEach((input) => {
			if (input.id) {
				const nuevoId = input.id.replace(/\d+$/, nuevoIndice);
				input.id = nuevoId;
			}
		});

		// 📌 NOTA: Actualiza Nro Item
		const celdaItem = fila.querySelector("td[data-label='Item']");
		if (celdaItem) {
			celdaItem.textContent = nuevoIndice + 1;
		}

		// 📌 NOTA: Actualiza el Boton Eliminar
		const botonEliminar = fila.querySelector("input.btn-eliminar");
		if (botonEliminar) {
			botonEliminar.setAttribute(
				"onclick",
				`eliminar_fila_salida_tienda('${nuevoIndice}')`
			);
		}

		nuevoIndice++;
	});
}

function eliminar_fila_salida_tienda(id_fila) {
	var ByeDiv = document.getElementById("divreg" + id_fila);
	ByeDiv.parentNode.removeChild(ByeDiv);
	reordenarFilas();
	calcular_pesos_stda();
}

function buscar_codbarra_saltienda() {
	const codigo = document.getElementById("codbarra").value.trim();
	const alm_o = document.getElementById("stalmaceno").value;
	const emp_o = document.getElementById("stempresao").value;

	if (codigo === "") {
		alert("Ingrese un código de barra válido");
		return true;
	}

	if (alm_o == 0 || alm_o === "") {
		alert("Debe escoger el Almacén de Origen");
		return true;
	}

	if (emp_o == 0 || emp_o === "") {
		alert("Debe escoger la Empresa de Origen");
		return true;
	}

	let contador = 1;
	const hiddens = document.getElementsByName("puntero");
	if (hiddens.length >= 1) {
		for (var x = 1; x < hiddens.length; x++) contador = contador + 1;
	} else {
		contador = 0;
	}
	// Validar si ya existe el código de barras
	var punteros_filasX = document.getElementsByName("puntero");
	var num_filasX = document.getElementsByName("puntero").length;

	for (var y = 0; y < num_filasX; y++) {
		var indiceX = punteros_filasX[y].id;
		var num_barras = document.getElementById("txt_codbarra" + indiceX).value;
		// console.log(num_barras + " " + codigo);
		if (num_barras === codigo) {
			alert("El codigo de barra ya existe en el detalle, no se puede agregar");
			document.getElementById("codbarra").value = "";
			document.getElementById("codbarra").focus();
			return;
		}
	}

	const parametros_envio = new URLSearchParams({
		correlativo: contador,
		codbarras: codigo,
		codalmacen_origen: alm_o,
		codemp_origen: emp_o,
	});

	fetch("salida_tienda_agregar_det.php", {
		method: "POST",
		headers: {
			"Content-Type": "application/x-www-form-urlencoded",
		},
		body: parametros_envio,
	})
		.then((response) => response.text())
		.then((html) => {
			if (html.length < 100) {
				alert(html);
				document.getElementById("codbarra").value = "";
				document.getElementById("codbarra").focus();
				return;
			}

			const tabla = document.getElementById("detalle-rollos");
			const tbody = tabla.querySelector("tbody");
			tbody.insertAdjacentHTML("beforeend", html.trim());

			calcular_pesos_stda();

			document.getElementById("codbarra").value = "";
			document.getElementById("codbarra").focus();
		})
		.catch((error) => {
			console.error("Error al buscar rollo:", error);
		});
}

function registrar_salida_tienda() {
	var codtraslado = document.getElementById("stmotivo").value;
	var codalmacen_origen = document.getElementById("stalmaceno").value;
	var codalmacen_destino = document.getElementById("stalmacend").value;
	var codemp_origen = document.getElementById("stempresao").value;
	var codemp_destino = document.getElementById("stempresad").value;
	var fecha_tienda = document.getElementById("stfecha").value;
	var obs = "";
	var total_rollos = document.getElementById("txt_total_rollos").value;
	var total_kg = document.getElementById("txt_totalkg").value;
	var usuario = document.getElementById("usuario").value;
	var codSalida = document.getElementById("salida_tienda").innerHTML;

	if (codtraslado == "0") {
		alert("Debe Elegir el Motivo de Traslado.");
		document.getElementById("stmotivo").focus();
		return true;
	}

	if (codalmacen_origen == "0") {
		alert("Debe Elegir el Almacen Origen.");
		document.getElementById("stalmaceno").focus();
		return true;
	}

	if (codemp_origen == "0") {
		alert("Debe Elegir la Empresa de Origen.");
		document.getElementById("stempresao").focus();
		return true;
	}

	if (codalmacen_destino == "0") {
		alert("Debe Elegir el Almacen Destino.");
		document.getElementById("stalmacend").focus();
		return true;
	}

	if (codemp_destino == "0") {
		alert("Debe Elegir la Empresa de Destino.");
		document.getElementById("stempresad").focus();
		return true;
	}

	if (fecha_tienda == "" || fecha_tienda == " ") {
		alert("Debe Elegir la Fecha de Salida.");
		document.getElementById("stfecha").focus();
		return true;
	}

	if (
		codalmacen_origen == codalmacen_destino &&
		codemp_origen == codemp_destino
	) {
		alert("No se puede hacer Traslado al mismo origen");
		return true;
	}

	//capturando los detalles
	var concatenado = "";
	var punteros_filas = document.getElementsByName("puntero");
	var num_filas = document.getElementsByName("puntero").length;
	// alert(num_filas);
	if (num_filas == "0" || num_filas == "") {
		alert("Debe completar todos registros del Detalle");
		return;
	}

	var arreglo = new Array();
	for (var k = 0; k < num_filas; k++) {
		var indice = punteros_filas[k].id;
		// console.log("Indice:", indice);
		arreglo[k] = new Array();

		var cdgart_fila = document.getElementById("txt_cdgart" + indice).value;
		var codbarra_fila = document.getElementById("txt_codbarra" + indice).value;
		var partida_fila = document.getElementById("txt_partida" + indice).value;
		var desprod_fila = document.getElementById("txt_desprod" + indice).value;
		var proceso_fila = document.getElementById("txt_procesos" + indice).value;
		var descolor_fila = document.getElementById("txt_descolor" + indice).value;
		var rollos_fila = document.getElementById("txt_rollos" + indice).value;
		var peso_fila = document.getElementById("txt_peso" + indice).value;
		var codemp_fila = document.getElementById("txt_codemp" + indice).value;
		var numordped_fila = document.getElementById(
			"txt_numordped" + indice
		).value;
		var numot_fila = document.getElementById("txt_numot" + indice).value;
		var codalmacen_fila = document.getElementById(
			"txt_codalmacen" + indice
		).value;
		var cdgcolor_fila = document.getElementById("txt_cdgcolor" + indice).value;
		var grem1_fila = document.getElementById("txt_grem" + indice).value;
		var coddet_ingtda_fila = document.getElementById(
			"txt_coddet_ingtda" + indice
		).value;
		var coding_tda_fila = document.getElementById(
			"txt_coding_tda" + indice
		).value;

		if (
			peso_fila == "" ||
			peso_fila == "0" ||
			peso_fila == " " ||
			peso_fila < 0.1
		) {
			alert("El Peso no debe ser 0KG !!!");
			return;
		}

		arreglo[k][0] = cdgart_fila;
		arreglo[k][1] = codbarra_fila;
		arreglo[k][2] = partida_fila;
		arreglo[k][3] = desprod_fila;
		arreglo[k][4] = proceso_fila;
		arreglo[k][5] = descolor_fila;
		arreglo[k][6] = rollos_fila;
		arreglo[k][7] = peso_fila;
		arreglo[k][8] = codemp_fila;
		arreglo[k][9] = codalmacen_fila;
		arreglo[k][10] = cdgcolor_fila;
		arreglo[k][11] = grem1_fila;
		arreglo[k][12] = coddet_ingtda_fila;
		arreglo[k][13] = coding_tda_fila;
		arreglo[k][14] = numordped_fila;
		arreglo[k][15] = numot_fila;
	}

	if (codSalida == "") {
		alert("Espere N° de Salida Tienda");
		return false;
	}

	var a = confirm("¿Desea registrar la Salida de Tienda?");
	if (a == false) return;
	// console.log(arreglo);

	var spanCodSalida = document.getElementById("salida_tienda");
	if (spanCodSalida) {
		spanCodSalida.id = "salida_tienda_final";
	}

	// 📌 NOTA: Desactivar Botones
	document.getElementById("stmotivo").disabled = true;
	document.getElementById("stfecha").disabled = true;
	document.getElementById("stalmaceno").disabled = true;
	document.getElementById("stempresao").disabled = true;
	document.getElementById("stalmacend").disabled = true;
	document.getElementById("stempresad").disabled = true;
	document.getElementById("codbarra").disabled = true;

	// Botones
	document.querySelectorAll(".btn-eliminar").forEach(function (boton) {
		boton.disabled = true;
	});
	document.getElementById("btn-guardar").disabled = true;

	const nro_salida = document.getElementById("salida_tienda_final");

	async function registrarSalidasYTraslado() {
		if (nro_salida) {
			const datos = new URLSearchParams();
			datos.append("arreglo", arreglo);
			datos.append("codtraslado", codtraslado);
			datos.append("codalmacen_origen", codalmacen_origen);
			datos.append("codalmacen_destino", codalmacen_destino);
			datos.append("codemp_origen", codemp_origen);
			datos.append("codemp_destino", codemp_destino);
			datos.append("fecha_tienda", fecha_tienda);
			datos.append("usuario", usuario);
			datos.append("obs", obs);
			datos.append("total_rollos", total_rollos);
			datos.append("total_kg", total_kg);
			datos.append("registrar_salida_tienda", "1");

			try {
				const response = await fetch("salida_tienda_control.php", {
					method: "POST",
					headers: {
						"Content-Type": "application/x-www-form-urlencoded",
					},
					body: datos.toString(),
				});
				const data = await response.text();
				// console.log(data);
				let respuesta = document.getElementById("respuesta");
				// respuesta.innerHTML = data;
				// respuesta.style.display = "block";
				// respuesta.className = data.includes("REGISTRADO") ? "success" : "error";

				if (codtraslado == "18") {
					const params = new URLSearchParams();
					params.append("arreglo", arreglo);
					params.append("codemp_origen", codemp_destino);
					params.append("codalmacen_origen", codalmacen_destino);
					params.append("fecha_ing", fecha_tienda);
					params.append("usuario", usuario);
					params.append("obs_ing", obs);
					params.append("total_rollos", total_rollos);
					params.append("total_peso", total_kg);
					params.append("codtraslado", codtraslado);
					params.append("registrar_traslado", "1");

					const traslado = await fetch("salida_tienda_traslado_control.php", {
						method: "POST",
						headers: {
							"Content-Type": "application/x-www-form-urlencoded",
						},
						body: params.toString(),
					});
					const trasladoData = await traslado.text();
					// console.log(trasladoData);
					respuesta.innerHTML = trasladoData;
					respuesta.style.display = "block";
					respuesta.className = trasladoData.includes("REGISTRADO")
						? "success"
						: "error";
				}
			} catch (error) {
				console.error("Error al registrar Ingreso Tienda:", error);
			}
		}
	}
	registrarSalidasYTraslado();
}
