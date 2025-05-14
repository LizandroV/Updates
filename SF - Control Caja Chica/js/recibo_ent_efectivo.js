// ✅HECHO: BUSCAR USUARIO POR DNI O CREAR SI NO EXISTE
// ⚠️ALERTA: PENDIENTE AGREGAR CONSULTA RENIEC

function ree_ver_datos_dni() {
	const dni = document.getElementById("dni").value.trim();
	const tipo = document.querySelector('input[name="tipo_resp"]:checked').value;

	if (dni == "" || dni.length < 7) {
		alert("Ingrese un DNI/CE válido");
		return false;
	}

	fetch("templates/finanzas/recibo_entrega_efectivo/obtenerDatos.php", {
		method: "POST",
		headers: { "Content-Type": "application/x-www-form-urlencoded" },
		body: `consultarDni=1&dni=${dni}&tipo=${tipo}`,
	})
		.then((response) => response.text())
		.then((data) => {
			if (data.startsWith("ERROR")) {
				// console.log(data);
				alert(data);
				document.getElementById("idreceptor").value = "";
				document.getElementById("receptor").value = "";
				return false;
			} else if (data.startsWith("CREAR")) {
				alert(data);
				document.getElementById("ree_crear_responsable").disabled = false;
			} else {
				// console.log(data);
				const [codigo, nombre] = data.split("|");
				document.getElementById("idreceptor").value = codigo;
				document.getElementById("receptor").value = nombre;
				document.getElementById("ree_crear_responsable").disabled = true;
				document.getElementById("responsable_input").value = "";
				document.getElementById("nuevo_responsable").style.display = "none";
			}
		})
		.catch((error) => {
			console.error("Error al consultar DNI:", error);
		});
}

//Limpia campos al cambiar radio de tipo
function limpiarReceptor() {
	document.getElementById("idreceptor").value = "";
	document.getElementById("receptor").value = "";
	document.getElementById("ree_crear_responsable").disabled = true;
	document.getElementById("responsable_input").value = "";
	document.getElementById("nuevo_responsable").style.display = "none";
}

function ree_crear_nuevo_dni() {
	const container = document.getElementById("nuevo_responsable");
	const textoBtn = document.getElementById("textoBtnCrearDni");

	if (container.style.display === "none" || container.style.display === "") {
		container.style.display = "block";
		textoBtn.innerHTML =
			"<img width='12' height='12' src='images/publish_x.png' align='absmiddle'>&nbsp;Cerrar";
	} else {
		container.style.display = "none";
		textoBtn.innerHTML =
			"<img width='15' height='14' src='images/add.png' align='absmiddle'>&nbsp;Crear";
	}
}

//Guardar nuevos responsables
function guardarNuevoDNI() {
	const dni = document.getElementById("dni").value.trim();
	const nombre = document.getElementById("responsable_input").value.trim();
	const tipo = document.querySelector('input[name="tipo_resp"]:checked').value;
	const textoBtn = document.getElementById("textoBtnCrearDni");

	if (!dni || !nombre) {
		alert("Debe ingresar el DNI y el nombre del responsable.");
		return false;
	}

	fetch("templates/finanzas/recibo_entrega_efectivo/obtenerDatos.php", {
		method: "POST",
		headers: {
			"Content-Type": "application/x-www-form-urlencoded",
		},
		body: `insertarNuevoDni=1&dni=${encodeURIComponent(
			dni
		)}&nombre=${encodeURIComponent(nombre)}&tipo=${tipo}`,
	})
		.then((response) => response.text())
		.then((data) => {
			if (data.startsWith("ERROR")) {
				alert(data);
				return false;
			} else {
				// console.log("Texto recibido:", data);
				const [codigo, nombre] = data.split("|");
				document.getElementById("idreceptor").value = codigo;
				document.getElementById("receptor").value = nombre;
				document.getElementById("nuevo_responsable").style.display = "none";
				textoBtn.innerHTML =
					"<img width='15' height='14' src='images/add.png' align='absmiddle'>&nbsp;Crear";
			}
		})
		.catch((err) => {
			console.error("Error al guardar:", err);
		});
}

// ✅ HECHO: AGREGAR NUEVA CATEGORIA
function agregarNuevaCategoria() {
	const container = document.getElementById("nueva_categoria_container");
	const textoBtn = document.getElementById("textoBtnCategoria");

	if (container.style.display === "none" || container.style.display === "") {
		container.style.display = "block";
		textoBtn.innerHTML =
			"<img width='12' height='12' src='images/publish_x.png' align='absmiddle'>&nbsp;Cerrar";
	} else {
		container.style.display = "none";
		textoBtn.innerHTML =
			"<img width='15' height='14' src='images/add.png' align='absmiddle'>&nbsp;Crear";
	}
}

function guardarNuevaCategoria() {
	const textoBtn = document.getElementById("textoBtnCategoria");
	const nuevaCategoria = document
		.getElementById("nueva_categoria_input")
		.value.trim();
	if (nuevaCategoria == "") {
		alert("Por favor ingrese una categoría válida.");
		return false;
	}

	const formData = new FormData();
	formData.append("nombre", nuevaCategoria);
	formData.append("guardarCategoria", 1);

	fetch("templates/finanzas/recibo_entrega_efectivo/obtenerDatos.php", {
		method: "POST",
		body: formData,
	})
		.then((response) => response.text())
		.then((data) => {
			// console.log(data);
			if (data.startsWith("ERROR")) {
				alert(data);
				return false;
			}
			const [id, nombre] = data.split("|");
			const select = document.getElementById("cbcategoria");
			const option = document.createElement("option");
			option.value = id;
			option.text = nombre;
			option.selected = true;
			select.add(option);

			document.getElementById("nueva_categoria_input").value = "";
			document.getElementById("nueva_categoria_container").style.display =
				"none";
			textoBtn.innerHTML =
				"<img width='15' height='14' src='images/add.png' align='absmiddle'>&nbsp;Crear";
		})
		.catch((error) => {
			console.error("Error:", error);
			alert("Hubo un problema al guardar la categoría.");
		});
}
// ✅ HECHO: AGREGAR NUEVO CONCEPTO
function agregarNuevoConcepto() {
	const container = document.getElementById("nuevo_concepto_container");
	const textoBtn = document.getElementById("textoBtnConcepto");

	if (container.style.display === "none" || container.style.display === "") {
		container.style.display = "block";
		textoBtn.innerHTML =
			"<img width='12' height='12' src='images/publish_x.png' align='absmiddle'>&nbsp;Cerrar";
	} else {
		container.style.display = "none";
		textoBtn.innerHTML =
			"<img width='15' height='14' src='images/add.png' align='absmiddle'>&nbsp;Crear";
	}
}

function guardarNuevoConcepto() {
	const textoBtn = document.getElementById("textoBtnConcepto");
	const nuevoConcepto = document
		.getElementById("nuevo_concepto_input")
		.value.trim();
	if (nuevoConcepto == "") {
		alert("Por favor ingrese un concepto válido.");
		return false;
	}
	const formData = new FormData();
	formData.append("descripcion", nuevoConcepto);
	formData.append("guardarConcepto", 1);

	fetch("templates/finanzas/recibo_entrega_efectivo/obtenerDatos.php", {
		method: "POST",
		body: formData,
	})
		.then((response) => response.text())
		.then((data) => {
			// console.log(data);
			if (data.startsWith("ERROR")) {
				alert(data);
				return false;
			}
			const [id, descripcion] = data.split("|");
			const select = document.getElementById("cbconcepto");
			const option = document.createElement("option");
			option.value = id;
			option.text = descripcion;
			option.selected = true;
			select.add(option);

			let opcionesOriginales = [];

			if (select.dataset.originalOptions) {
				opcionesOriginales = JSON.parse(select.dataset.originalOptions);
			}

			opcionesOriginales.push({ value: id, text: descripcion });
			select.dataset.originalOptions = JSON.stringify(opcionesOriginales);

			document.getElementById("nuevo_concepto_input").value = "";
			document.getElementById("nuevo_concepto_container").style.display =
				"none";
			textoBtn.innerHTML =
				"<img width='15' height='14' src='images/add.png' align='absmiddle'>&nbsp;Crear";
		})
		.catch((error) => {
			console.error("Error:", error);
			alert("Hubo un problema al guardar la categoría.");
		});
}

// ✅ HECHO: FILTRAR CONCEPTOS
function filtrarConceptos() {
	const filtro = document.getElementById("filtroConcepto").value.toUpperCase();
	const selector = document.getElementById("cbconcepto");

	if (!selector.dataset.originalOptions) {
		const opcionesOriginales = Array.from(selector.options).map((opt) => ({
			value: opt.value,
			text: opt.text,
		}));
		selector.dataset.originalOptions = JSON.stringify(opcionesOriginales);
	}

	const opciones = JSON.parse(selector.dataset.originalOptions);
	selector.innerHTML = "";

	const opcionesFiltradas = opciones.filter((opt) =>
		opt.text.toUpperCase().includes(filtro)
	);

	if (opcionesFiltradas.length === 0) {
		const option = document.createElement("option");
		option.text = "No encontrado";
		option.value = "0";
		selector.add(option);
	} else {
		opcionesFiltradas.forEach((opt) => {
			const option = document.createElement("option");
			option.text = opt.text;
			option.value = opt.value;
			selector.add(option);
		});
	}
}

// ✅ HECHO: CARGAR SALDOS POR EMPRESA
function cargarSaldosMovimientos() {
	const select = document.getElementById("cbempresa");
	const codEmp = select.value;
	const nomEmp = select.options[select.selectedIndex].text;
	document.getElementById("mov_empresa").innerHTML = nomEmp;
	const alerta = document.getElementById("saldo_alert");
	alerta.style.display = "none";
	const MovDiv = document.getElementById("ultimos_movimientos");

	if (select.disabled == false) {
		document.getElementById("monto").value = "";
	}

	if (codEmp == 0) {
		document.getElementById("mov_empresa").innerHTML = "";
		document.getElementById("saldos").innerHTML =
			'<p class="Estilo100" style="font-size: 14px;">Seleccione una empresa para ver el saldo actual</p>';
		MovDiv.innerHTML = "";
		return false;
	}

	// Primer fetch: Obtener Saldos
	fetch("templates/finanzas/recibo_entrega_efectivo/obtenerDatos.php", {
		method: "POST",
		headers: { "Content-Type": "application/x-www-form-urlencoded" },
		body: "versaldos=1&codemp=" + codEmp,
	})
		.then((response) => response.text())
		.then((html) => {
			document.getElementById("saldos").innerHTML = html;
		})
		.catch((error) => {
			console.error("Error:", error);
			document.getElementById("saldos").innerHTML = "Error al obtener saldos.";
		});

	// Segundo fetch: Ultimos movimientos
	fetch("templates/finanzas/recibo_entrega_efectivo/listaCajaChica.php", {
		method: "POST",
		headers: { "Content-Type": "application/x-www-form-urlencoded" },
		body: "verMovimientos=1&codemp=" + codEmp,
	})
		.then((response) => response.text())
		.then((html) => {
			MovDiv.innerHTML = html;
		})
		.catch((error) => {
			console.error("Error:", error);
			MovDiv.innerHTML = "<p>Error al cargar los ultimos movimientos.</p>";
		});
}

//Valida que se ingresen solo numeros
function validarNumero(input) {
	const valor = input.value;

	if (!/^\d*\.?\d*$/.test(valor)) {
		alert("Solo se permiten números.");
		input.value = valor.replace(/[^0-9.]/g, ""); // elimina caracteres no numéricos
	}
}

//Valida saldo por empresa
function checkSaldoDisponible() {
	const monto = parseFloat(document.getElementById("monto").value) || 0;
	const codmon = parseInt(document.getElementById("cbtipomoneda").value);
	const codemp = parseInt(document.getElementById("cbempresa").value);

	if (!codmon || !codemp) return;

	const data = new FormData();
	data.append("versaldounico", 1);
	data.append("codemp", codemp);
	data.append("codmon", codmon);

	fetch("templates/finanzas/recibo_entrega_efectivo/obtenerDatos.php", {
		method: "POST",
		body: data,
	})
		.then((res) => res.text())
		.then((saldo) => {
			const saldoDisponible = parseFloat(saldo);
			const alerta = document.getElementById("saldo_alert");

			if (monto > saldoDisponible) {
				alerta.style.display = "inline";
				// desactiva el botón si quieres
				document.getElementById("btn_guardar").disabled = true;
			} else {
				alerta.style.display = "none";
				document.getElementById("btn_guardar").disabled = false;
			}
		})
		.catch((err) => {
			console.error("Error al obtener el saldo:", err);
		});
}

function validarMonto(input) {
	validarNumero(input);
	checkSaldoDisponible();
}

// ⚠️ ALERTA: MOSTRAR CANTIDAD EN BOTON ADJUNTAR - CAMBIAR BOTON A VENTANA
function actualizarTextoBoton(input) {
	const maxArchivos = 3;

	if (input.files.length > maxArchivos) {
		alert(`Solo puedes seleccionar hasta ${maxArchivos} archivos.`);
		input.value = ""; // limpia la selección
		document.getElementById("textoBoton").textContent = "Adjuntar";
		return;
	}

	const cantidad = input.files.length;
	const texto =
		cantidad > 0 ? `${cantidad} archivo${cantidad > 1 ? "s" : ""}` : "Archivo";
	document.getElementById("textoBoton").textContent = texto;
}

// ✅ HECHO: HABILITA TIPO CAMBIO EN CATEGORIA CAMBIO DIVISAS
function cambiarEstadoTC() {
	var select = document.getElementById("cbcategoria");
	var inputTC = document.getElementById("tipo_cambio");

	if (select.value == "9") {
		inputTC.disabled = false;
	} else {
		inputTC.disabled = true;
		inputTC.value = "";
	}
}

// 🔥 CRÍTICO : FILTRAR ULTIMOS MOVIMIENTOS AGREGAR EN LA PARTE SUPERIOR
function ccsFiltrarMovimientos() {
	alert("INICIO");
	const texto = document
		.getElementById("filtrarMovimientos")
		.value.toUpperCase()
		.trim();

	var MovDiv = document.getElementById("ultimos_movimientos");

	const formData = new FormData();
	formData.append("texto", texto);
	formData.append("filtrarMovimientos", 1);

	fetch("templates/finanzas/recibo_entrega_efectivo/listaCajaChica.php", {
		method: "POST",
		body: formData,
	})
		.then((response) => response.text())
		.then((html) => {
			// console.log(html);
			MovDiv.innerHTML = html;
			cargarSaldosMovimientos();
		})
		.catch((error) => {
			console.error("Error:", error);
		});
}

// ✅ HECHO : FUNCION GUARDAR
// ⚠️ ALERTA: FALTA GUARDAR ARCHIVOS
function guardarReciboEntrega() {
	const cod_ccs = document.getElementById("cod_ccs").value;
	const usuario = document.getElementById("usuario").value;
	const empresa = document.getElementById("cbempresa").value;
	const dni = document.getElementById("dni").value.trim();
	const idreceptor = document.getElementById("idreceptor").value;
	const moneda = document.getElementById("cbtipomoneda").value;
	const monto = document.getElementById("monto").value.trim();
	const tipoCambio = document.getElementById("tipo_cambio").value.trim();
	const categoria = document.getElementById("cbcategoria").value;
	const concepto = document.getElementById("cbconcepto").value;
	const comentario = document.getElementById("comentario").value;

	if (empresa == 0) {
		alert("Seleccione una empresa para continuar.");
		document.getElementById("cbempresa").focus();
		return;
	}

	if (dni == "" || dni.length < 7) {
		alert("Ingrese un DNI/CE válido.");
		document.getElementById("dni").focus();
		return;
	}

	if (idreceptor == 0 || idreceptor == "") {
		alert("No existe el responsable.");
		document.getElementById("dni").focus();
		return;
	}

	if (moneda == 0) {
		alert("Seleccione una moneda.");
		document.getElementById("cbtipomoneda").focus();
		return;
	}
	if (monto == 0 || monto == "" || monto < 0) {
		alert("Ingrese un monto válido.");
		document.getElementById("monto").focus();
		return;
	}

	if (categoria == 0) {
		alert("Seleccione una Categoria.");
		document.getElementById("cbcategoria").focus();
		return;
	}

	if (concepto == 0) {
		alert("Seleccione un Concepto.");
		document.getElementById("cbconcepto").focus();
		return;
	}
	if (cod_ccs == 0) {
		var a = confirm("¿Desea registrar el Recibo Entrega Efectivo?");
		if (a == false) return;

		const data = new FormData();
		data.append("guardarReciboEntrega", 1);
		data.append("cod_ccs", cod_ccs);
		data.append("cod_resp", idreceptor);
		data.append("CodMon", moneda);
		data.append("Monto", monto);
		data.append("CodEmp", empresa);
		data.append("cod_categ", categoria);
		data.append("cod_concepto", concepto);
		data.append("comentario", comentario);
		data.append("usureg", usuario);

		// const MovDiv = document.getElementById("ultimos_movimientos");

		fetch("templates/finanzas/recibo_entrega_efectivo/listaCajaChica.php", {
			method: "POST",
			body: data,
		})
			.then((response) => response.text())
			.then((html) => {
				// MovDiv.innerHTML = html;
				console.log(html);
				var aviso = document.getElementById("mensajeExito");
				aviso.innerHTML = "Recibo Registrado Correctamente!";
				aviso.style.display = "block";
				cargarSaldosMovimientos();

				setTimeout(function () {
					aviso.style.display = "none";
				}, 4000);
			})
			.catch((error) => {
				console.error("Error:", error);
				// MovDiv.innerHTML = "<p>Error al cargar los ultimos movimientos.</p>";
				var avisoerror = document.getElementById("mensajeError");
				avisoerror.innerHTML = response.error;
				avisoerror.style.display = "block";
				// Configura un temporizador para ocultar el aviso después de 3 segundos (3000 milisegundos)
				setTimeout(function () {
					avisoerror.style.display = "none";
				}, 4000);
			});
	} else {
		var a = confirm("¿Desea Actualizar el Recibo Entrega Efectivo?");
		if (a == false) return;

		const data = new FormData();
		data.append("actualizarRecibo", 1);
		data.append("cod_ccs", cod_ccs);
		data.append("cod_resp", idreceptor);
		data.append("CodMon", moneda);
		data.append("Monto", monto);
		data.append("CodEmp", empresa);
		data.append("cod_categ", categoria);
		data.append("cod_concepto", concepto);
		data.append("comentario", comentario);
		data.append("usureg", usuario);

		// const MovDiv = document.getElementById("ultimos_movimientos");

		fetch("templates/finanzas/recibo_entrega_efectivo/listaCajaChica.php", {
			method: "POST",
			body: data,
		})
			.then((response) => response.text())
			.then((html) => {
				// MovDiv.innerHTML = html;
				// console.log(html);
				var aviso = document.getElementById("mensajeExito");
				aviso.innerHTML = "Recibo Actualizado Correctamente!";
				aviso.style.display = "block";
				cargarSaldosMovimientos();

				setTimeout(function () {
					aviso.style.display = "none";
				}, 4000);
			})
			.catch((error) => {
				console.error("Error:", error);
				// MovDiv.innerHTML = "<p>Error al cargar los ultimos movimientos.</p>";
				var avisoerror = document.getElementById("mensajeError");
				avisoerror.innerHTML = response.error;
				avisoerror.style.display = "block";
				// Configura un temporizador para ocultar el aviso después de 3 segundos (3000 milisegundos)
				setTimeout(function () {
					avisoerror.style.display = "none";
				}, 4000);
			});
	}

	// Deshabilita campos
	document.getElementById("cbempresa").disabled = true;
	document.getElementById("dni").disabled = true;
	document.getElementById("1").disabled = true;
	document.getElementById("2").disabled = true;
	document.getElementById("btnConsultarDNI").disabled = true;
	document.getElementById("cbtipomoneda").disabled = true;
	document.getElementById("monto").disabled = true;
	document.getElementById("cbcategoria").disabled = true;
	document.getElementById("btnCrearCategoria").disabled = true;
	document.getElementById("filtroConcepto").disabled = true;
	document.getElementById("btnCrearConcepto").disabled = true;
	document.getElementById("cbconcepto").disabled = true;
	document.getElementById("comentario").disabled = true;
	document.getElementById("adjuntarArchivos").disabled = true;
	document.getElementById("btn_guardar").disabled = true;
	document.getElementById("btn_imprimir").disabled = false;
}

// 🔥 CRÍTICO FUNCION IMPRIMIR
function imprimirReciboEntrega() {
	alert("Impirmir recibo");
}

// ✅ HECHO FUNCION NUEVO CON OPCION DE MANTENER
function nuevoReciboEntrega() {
	var a = confirm("¿Desea mantener los últimos cambios?");
	const hoy = new Date().toISOString().split("T")[0];
	if (a == false) {
		document.getElementById("cod_ccs").value = 0;
		document.getElementById("cbempresa").disabled = false;
		document.getElementById("dni").disabled = false;
		document.getElementById("dni").value = "";
		document.getElementById("receptor").value = "";
		document.getElementById("idreceptor").value = 0;
		document.getElementById("1").disabled = false;
		document.getElementById("2").disabled = false;
		document.getElementById("btnConsultarDNI").disabled = false;
		document.getElementById("cbtipomoneda").disabled = false;
		document.getElementById("cbtipomoneda").value = 0;
		document.getElementById("monto").disabled = false;
		document.getElementById("monto").value = "";
		document.getElementById("cbcategoria").disabled = false;
		document.getElementById("cbcategoria").value = 0;
		document.getElementById("btnCrearCategoria").disabled = false;
		document.getElementById("filtroConcepto").disabled = false;
		document.getElementById("filtroConcepto").value = "";
		document.getElementById("btnCrearConcepto").disabled = false;
		document.getElementById("cbconcepto").disabled = false;
		document.getElementById("cbconcepto").value = 0;
		document.getElementById("comentario").disabled = false;
		document.getElementById("comentario").value = "";
		document.getElementById("adjuntarArchivos").disabled = false;
		document.getElementById("adjuntarArchivos").value = "";
		document.getElementById("btn_guardar").disabled = false;
		document.getElementById("btn_imprimir").disabled = true;
	} else {
		checkSaldoDisponible();
		document.getElementById("cod_ccs").value = 0;
		document.getElementById("cbempresa").disabled = false;
		document.getElementById("dni").disabled = false;
		document.getElementById("1").disabled = false;
		document.getElementById("2").disabled = false;
		document.getElementById("btnConsultarDNI").disabled = false;
		document.getElementById("cbtipomoneda").disabled = false;
		document.getElementById("monto").disabled = false;
		document.getElementById("cbcategoria").disabled = false;
		document.getElementById("btnCrearCategoria").disabled = false;
		document.getElementById("filtroConcepto").disabled = false;
		document.getElementById("btnCrearConcepto").disabled = false;
		document.getElementById("cbconcepto").disabled = false;
		document.getElementById("comentario").disabled = false;
		document.getElementById("adjuntarArchivos").disabled = false;
		document.getElementById("btn_guardar").disabled = false;
		document.getElementById("btn_imprimir").disabled = true;
	}
	document.getElementById("fecha_recibo").value = hoy;
	const btnGuardar = document.getElementById("btn_guardar");
	btnGuardar.innerHTML = `<img width="15" height="14" src="images/btn_guardar.png" align="absmiddle">&nbsp;Guardar`;
	btnGuardar.onclick = guardarReciboEntrega;
}

//✅ HECHO: Ver Recibo
function verRecibo(idRecibo, tipoCaja) {
	var itemCodCCS = document.getElementById("cod_ccs");
	var itemEmpresa = document.getElementById("cbempresa");
	var itemDNI = document.getElementById("dni");
	var itemReceptor = document.getElementById("receptor");
	var itemIdreceptor = document.getElementById("idreceptor");
	var itemRadio1 = document.getElementById("1");
	var itemRadio2 = document.getElementById("2");
	var itemTipoMoneda = document.getElementById("cbtipomoneda");
	var itemMonto = document.getElementById("monto");
	var itemTipoCambio = document.getElementById("tipo_cambio");
	var itemCategoria = document.getElementById("cbcategoria");
	var itemFiltro = document.getElementById("filtroConcepto");
	var itemConcepto = document.getElementById("cbconcepto");
	var itemComentario = document.getElementById("comentario");
	var itemFecha = document.getElementById("fecha_recibo");
	var itemArchivos = document.getElementById("adjuntarArchivos");

	// Bloquear inputs
	itemRadio1.disabled = true;
	itemRadio2.disabled = true;
	itemDNI.disabled = true;
	itemEmpresa.disabled = true;
	document.getElementById("btnConsultarDNI").disabled = true;
	itemTipoMoneda.disabled = true;
	itemMonto.disabled = true;
	itemCategoria.disabled = true;
	document.getElementById("btnCrearCategoria").disabled = true;
	itemFiltro.disabled = true;
	document.getElementById("btnCrearConcepto").disabled = true;
	itemConcepto.disabled = true;
	itemComentario.disabled = true;
	itemArchivos.disabled = true;
	document.getElementById("btn_imprimir").disabled = false;

	// Cambiar Guardar a Editar
	const btnGuardar = document.getElementById("btn_guardar");
	btnGuardar.innerHTML =
		"<img width='15' height='14' src='images/edit.png' align='absmiddle'>&nbsp;Editar";
	btnGuardar.onclick = editarRecibo;

	const hoy = new Date().toISOString().split("T")[0];
	const formData = new FormData();
	formData.append("idRecibo", idRecibo);
	formData.append("tipoCaja", tipoCaja);
	formData.append("verRecibo", 1);

	fetch("templates/finanzas/recibo_entrega_efectivo/verRecibo.php", {
		method: "POST",
		body: formData,
	})
		.then((response) => response.json())
		.then((data) => {
			// Asignar datos a los inputs
			itemCodCCS.value = data.cod_cc_sal;
			itemEmpresa.value = data.CodEmp;
			data.tipo_resp == 1
				? (itemRadio1.checked = true)
				: (itemRadio2.checked = true);

			itemIdreceptor.value = data.cod_resp;
			itemDNI.value = data.Dni;
			itemReceptor.value = data.Nombre;
			itemTipoMoneda.value = data.CodMon;
			itemMonto.value = data.Monto;
			itemTipoCambio.value = data.tipo_cambio;
			itemCategoria.value = data.cod_categ;
			itemConcepto.value = data.cod_concepto;
			itemComentario.value = data.comentario;
			itemFecha.value = data.FecReg;

			if (data.FecReg === hoy) {
				btnGuardar.disabled = false;
			} else {
				btnGuardar.disabled = true;
			}

			if (data.TotalAdjuntos) {
				console.log(
					"Total de adjuntos:",
					data.TotalAdjuntos + " " + data.FecReg
				);
			}

			cargarSaldosMovimientos();
		})
		.catch((error) => {
			console.error("Error:", error);
		});
}

//✅ HECHO: Editar Recibo
function editarRecibo() {
	document.getElementById("cbempresa").disabled = true;
	document.getElementById("dni").disabled = false;
	document.getElementById("1").disabled = false;
	document.getElementById("2").disabled = false;
	document.getElementById("btnConsultarDNI").disabled = false;
	document.getElementById("cbtipomoneda").disabled = false;
	document.getElementById("monto").disabled = false;
	document.getElementById("cbcategoria").disabled = false;
	document.getElementById("btnCrearCategoria").disabled = false;
	document.getElementById("filtroConcepto").disabled = false;
	document.getElementById("btnCrearConcepto").disabled = false;
	document.getElementById("cbconcepto").disabled = false;
	document.getElementById("comentario").disabled = false;
	document.getElementById("adjuntarArchivos").disabled = false;
	document.getElementById("btn_guardar").disabled = false;
	document.getElementById("btn_imprimir").disabled = true;

	//Cambiar Boton Editar a Actualizar
	const btnGuardar = document.getElementById("btn_guardar");
	btnGuardar.innerHTML = `<img width="15" height="14" src="images/btn_guardar.png" align="absmiddle">&nbsp;Actualizar`;
	btnGuardar.onclick = guardarReciboEntrega;
}

//✅ HECHO: Cancelar Recibo
function cancelarRecibo(idRecibo, tipoCaja) {
	var a = confirm("¿Desea Cancelar este Recibo de Entrega?");
	if (a == false) return;

	const formData = new FormData();
	formData.append("idRecibo", idRecibo);
	formData.append("tipo", tipoCaja);
	formData.append("cancelarRecibo", 1);

	fetch("templates/finanzas/recibo_entrega_efectivo/listaCajaChica.php", {
		method: "POST",
		body: formData,
	})
		.then((response) => response.text())
		.then((html) => {
			// console.log(html);
			cargarSaldosMovimientos();
		})
		.catch((error) => {
			console.error("Error:", error);
		});
}
