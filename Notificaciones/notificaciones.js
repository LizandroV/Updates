let notificacionesPrevias = new Set();

function actualizarNotificaciones() {
    fetch("obtener_notificaciones.php")
        .then(response => response.json())
        .then(data => {
            let contenedor = document.getElementById("dv_notifica");
            let notificacionContador = document.getElementById("contador-notificaciones");

            // Actualizar el contador
            if (notificacionContador) {
                notificacionContador.textContent = data.contador;
            }

            // Crear un nuevo set
            let notificacionesActuales = new Set(data.notificaciones.map(n => n.cod_notificacion));

            // Si las notificaciones no han cambiado, no actualizar el DOM
            if (JSON.stringify([...notificacionesActuales]) === JSON.stringify([...notificacionesPrevias])) {
                return; //TERMINA SI NO HAY CAMBIOS
            }
            
            notificacionesPrevias = notificacionesActuales;

            // Actualizar solo si hay cambios
            let html = '<nav class="full-width"><ul class="full-width list-unstyle menu-principal">';

            data.notificaciones.forEach(notif => {
                let estado = notif.estado == 1 ? "read" : "new";
                html += `
                    <li class="full-width divider-menu-h"></li>
                    <li class="list-notification">
                        <div id="vertical">
                            <form action="../sistema/control_vigilancia.php" method="POST">
                                <input type="hidden" name="tipo_doc" value="${notif.tipo_doc}">
                                <input type="hidden" name="num_doc" value="${notif.numero_doc}">
                                <input type="hidden" name="cod_ord" value="${notif.cod_ord}">
                                <input type="hidden" name="cod_not" value="${notif.cod_notificacion}">
                                <button type="submit" class="${estado}">
                                    <span class="icon"></span>
                                    <div class="text">
                                        <span class="description">${notif.descrip}</span>
                                        <span class="date">Fecha: ${notif.fecha} - Hora: ${notif.hora}</span>
                                    </div>
                                </button>
                            </form>
                        </div>
                    </li>
                `;
            });

            html += "</ul></nav>";
            contenedor.innerHTML = html;
        })
        .catch(error => console.error("Error al obtener notificaciones:", error));
}


// Ejecutar cada 5 segundos
setInterval(actualizarNotificaciones, 5000); 

// Cargar notificaciones
actualizarNotificaciones();
