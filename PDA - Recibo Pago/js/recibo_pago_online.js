let lastData = "";

function act_recibo_pago() {
	if (!document.getElementById("recibo_pago")) return;

	var codemp = document.getElementById("cbempresa").value;

	if (codemp == "") {
		document.getElementById("recibo_pago").innerHTML = "";
		return;
	}

	var Destino = document.getElementById("recibo_pago");

	fetch("../sistema/motorOnline.php", {
		method: "POST",
		headers: {
			"Content-Type": "application/x-www-form-urlencoded",
		},
		body: "recibo_pago=1&codemp=" + encodeURIComponent(codemp),
	})
		.then((response) => response.text())
		.then((data) => {
			if (data !== lastData) {
				Destino.innerHTML = data;
				lastData = data;
			}
		})
		.catch((error) => {
			Destino.innerHTML = "Error al actualizar.";
		});
}

// Ejecutar cada 5 segundos
setInterval(act_recibo_pago, 5000);

// Cargar notificaciones
act_recibo_pago();
