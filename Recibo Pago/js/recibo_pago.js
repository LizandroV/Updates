function registrar_recibo_pago() {
	var codemp = document.getElementById("cbempresa").value;
	var codneg = document.getElementById("cbnegocio").value;
	var cod_cli = document.getElementById("cbcliente").value;
	var fecha_recibo = document.getElementById("fechaHora").value;
	var cod_moneda = document.getElementById("cbmoneda").value;
	var importe = document.getElementById("importe").value;
	var obs = document.getElementById("txt_obs").value;
	var usuario = document.getElementById("usuario").value;
	var recibo = document.getElementById("recibo_pago").innerHTML;

	if (codemp == "") {
		alert("Debe Elegir la Empresa.");
		document.getElementById("cbempresa").focus();
		return false;
	}

	if (cod_cli == "") {
		alert("Debe Elegir el Cliente.");
		document.getElementById("cbcliente").focus();
		return false;
	}

	if (cod_moneda == "") {
		alert("Debe Elegir la Moneda.");
		document.getElementById("cbmoneda").focus();
		return false;
	}

	if (importe == "" || importe == " " || importe == 0) {
		alert("Debe Ingresar el Importe.");
		document.getElementById("importe").focus();
		return false;
	}

	if (recibo == "") {
		alert("Espere N° de Recibo");
		return false;
	}

	var a = confirm("¿Desea Registrar el Recibo de Pago?");
	if (a == false) return;

	var spanRecibo = document.getElementById("recibo_pago");
	if (spanRecibo) {
		spanRecibo.id = "recibo_pago_final";
	}
	document.getElementById("cbempresa").disabled = true;
	document.getElementById("cbnegocio").disabled = true;
	document.getElementById("cbcliente").disabled = true;
	document.getElementById("buscarCliente").disabled = true;
	document.getElementById("fechaHora").disabled = true;
	document.getElementById("cbmoneda").disabled = true;
	document.getElementById("importe").disabled = true;
	document.getElementById("txt_obs").disabled = true;
	// Botones
	document.getElementById("imprimir").disabled = false;
	document.getElementById("nuevo_orden").disabled = false;
	document.getElementById("guardar_orden").disabled = true;

	var datos = `codemp=${codemp}&codneg=${codneg}&cod_cli=${cod_cli}&fecha_recibo=${fecha_recibo}&cod_moneda=${cod_moneda}&importe=${importe}&obs=${obs}&usuario=${usuario}`;

	fetch("./templates/registrarReciboPago.php", {
		method: "POST",
		headers: { "Content-Type": "application/x-www-form-urlencoded" },
		body: datos,
	})
		.then((response) => response.text())
		.then((data) => {
			// console.log(data);
			let respuesta = document.getElementById("respuesta");
			respuesta.innerHTML = data;
			respuesta.style.display = "block";

			// Aplicar clase según la respuesta
			respuesta.className = data.includes("REGISTRADO") ? "success" : "error";

			if (data.includes("REGISTRADO")) {
				imprimir_recibo_pago("CLIENTE");
			}
		})
		.catch((error) => console.error("Error en el envío:", error));
}

//
//   NUEVO RECIBO DE PAGO
//
function nuevo_recibo_pago() {
	var a = confirm("¿Desea Generar un nuevo Documento?.");
	if (a == false) return;

	// Recargar la página actual
	location.reload();
}

//
//   IMPRIMIR RECIBO DE PAGO
//
function imprimir_recibo_pago(label) {
	const ruta = "../sistema/templates/impresion_ticket.php";
	let codemp = document.getElementById("cbempresa").value;
	let cod_recibo = document.getElementById("recibo_pago_final").innerText;
	//let cod_recibo = document.getElementsByClassName("ultimoRecibo")[0].innerText; //recibo_pago_final

	if (codemp == "") {
		alert("Debe Elegir la Empresa.");
		document.getElementById("cbempresa").focus();
		return false;
	}

	function obtenerNumero(cadena) {
		if (!cadena.includes("-")) return null;
		let partes = cadena.split("-");
		let numero = partes[1].replace(/^0+/, "");
		return parseInt(numero, 10);
	}
	cod_recibo = obtenerNumero(cod_recibo);

	// Abrir el PDF en una nueva pestaña
	window.open(
		ruta +
			"?codemp=" +
			codemp +
			"&cod_recibo=" +
			cod_recibo +
			"&label=" +
			label,
		"_blank"
	);

	// var link = document.createElement("a");
	// link.href = ruta + "?codemp=" + codemp + "&cod_recibo=" + cod_recibo;
	// link.download = "Recibo_" + cod_recibo + ".pdf";
	// link.click();
}
