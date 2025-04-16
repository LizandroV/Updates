let lastData = "";

function act_salida_tienda() {
	if (!document.getElementById("salida_tienda")) return;

	var stalmaceno = document.getElementById("stalmaceno").value;
	if (stalmaceno == "0") {
		document.getElementById("salida_tienda").innerHTML = "";
		return;
	}

	var Destino = document.getElementById("salida_tienda");
	var Actual = document.getElementById("salida_tienda").innerHTML;
	fetch("../sistema/salida_tienda_online.php", {
		method: "POST",
		headers: {
			"Content-Type": "application/x-www-form-urlencoded",
		},
		body: "salida_tienda=1&stalmaceno=" + encodeURIComponent(stalmaceno),
	})
		.then((response) => response.text())
		.then((data) => {
			if (data !== lastData) {
				Destino.innerHTML = data;
				lastData = data;
			} else if (Actual == "") {
				Destino.innerHTML = data;
			}
			// console.log(lastData);
			// console.log(data);
		})
		.catch((error) => {
			Destino.innerHTML = "Error al actualizar.";
		});
}

// Ejecutar cada 5 segundos
setInterval(act_salida_tienda, 5000);

// Cargar notificaciones
act_salida_tienda();
