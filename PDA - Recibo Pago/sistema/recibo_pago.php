<?php
// error_reporting(E_ALL);
include "head.php";
unset($_SESSION['carroV']);
$_SESSION['igvnum1_'] = $igvnum1;

if (!isset($_SESSION['logged'])) {
    session_destroy();
    echo '<script>document.location.href="../index.php?e=24";</script>';
    exit;
}

?>
<h3 class="controlTitle"> RECIBO DE PAGO </h3>
<p id="respuesta"></p>
<p>Recibo N°: <span id="recibo_pago" class="ultimoRecibo"></span></p>
<form method="POST" enctype="multipart/form-data" name="formrecibo" id="formrecibo">
    <div class="form-recibo">
        <input type="hidden" name="usuario" id="usuario" value="<?= $_SESSION['micodigo'] ?>" />
        <label for="cbempresa"><b>Empresa:</b></label>
        <select name="cbempresa" id="cbempresa" required>
            <option value="0">Elegir</option>
            <?php
            $sql_emp = "SELECT EmpCod, EmpRaz FROM $bd.dbo.EMPRESA WHERE EmpEst='A' ORDER BY EmpRaz ASC";
            $empresas = db_fetch_all($sql_emp);
            foreach ($empresas as $emp) {
                $cod_emp = trim($emp['EmpCod']);
                $nom_emp = trim($emp['EmpRaz']);
                echo "<option value='$cod_emp'>$nom_emp</option>";
            }
            ?>
        </select>
    </div>

    <div class="form-recibo">
        <label for="cbnegocio"><b>Negocio:</b></label>
        <select name="cbnegocio" id="cbnegocio">
            <option value="0">Elegir</option>
            <?php
            $sql_neg = "SELECT NegCod, NegDes FROM $bd.dbo.NEGOCIO WHERE NegCod!=1 and NegEst='A' ORDER BY NegCod ASC";
            $negocios = db_fetch_all($sql_neg);
            foreach ($negocios as $neg) {
                $codneg = trim($neg['NegCod']);
                $desneg = trim($neg['NegDes']);
                echo "<option value='$codneg'>$desneg</option>";
            }
            ?>
        </select>
    </div>

    <div class="form-recibo">
        <label for="buscarCliente"><b>Buscar Cliente:</b></label>
        <input type="text" id="buscarCliente" placeholder="Escriba para buscar..." onkeyup="filtrarClientes()">
    </div>

    <div class="form-recibo">
        <label for="cbcliente"><b>Cliente:</b></label>
        <select name="cbcliente" id="cbcliente" required>
            <option value="0">Elegir</option>
            <?php
            $sql_cli = "SELECT CliCod, CONVERT(NVARCHAR(MAX), CliRaz) AS CliRaz FROM $bd.dbo.CLIENTE WHERE CliEst='A' ORDER BY CliRaz ASC";
            $clientes = db_fetch_all($sql_cli);

            foreach ($clientes as $cliente) {
                $codcli = trim($cliente['CliCod']);
                $nomcli = trim($cliente['CliRaz']);

                // Convertir a UTF-8
                $nomcli = mb_convert_encoding($nomcli, "UTF-8", "auto");

                echo "<option value='$codcli'>$nomcli</option>";
            }
            ?>
        </select>
    </div>

    <div class="form-recibo">
        <label for="fechaHora"><b>Fecha y Hora:</b></label>
        <input type="hidden" id="fechaHora" name="fechaHora" required>
        <span id="fechaHoraTexto"></span>
    </div>

    <div class="form-recibo">
        <label for="cbmoneda"><b>Importe de Recibo:</b></label>
        <div class="importe">
            <select name="cbmoneda" id="cbmoneda" required>
                <option value="0">Elegir</option>
                <?php
                $sql_mon = "SELECT CodMon, SimMon FROM $bd.dbo.MONEDA WHERE CodMon in ('1','2') and Estado='A'";
                $monedas = db_fetch_all($sql_mon);
                foreach ($monedas as $mon) {
                    $CodMon = trim($mon['CodMon']);
                    $SimMon = trim($mon['SimMon']);
                    echo "<option value='$CodMon'>$SimMon</option>";
                }
                ?>
            </select>
            <input type="number" id="importe" name="importe" placeholder="Ingrese importe"
                step="0.01" min="0" required>
        </div>
    </div>

    <div class="form-recibo">
        <label for="txt_obs"><b>Comentario:</b></label>
        <textarea id="txt_obs" name="txt_obs" rows="3" cols="50"></textarea>
    </div>

    <div class="form-buttons">
        <button name="boton" id="guardar_orden" onClick="registrar_recibo_pago()" class="btn2" type="button"> 💾 Guardar e Imprimir</button>
        <button name="boton" id="imprimir" disabled onClick="imprimir_recibo_pago('USUARIO')" class="btn2" type="button">📄 Imprimir Copia</button>
        <button name="boton" id="nuevo_orden" onClick="nuevo_recibo_pago()" class="btn2" type="button"> 📁 Recibo Nuevo</button>
    </div>


</form>
<script>
    // ESTABLECER FECHA
    document.addEventListener("DOMContentLoaded", function() {
        let now = new Date();

        // Obtener fecha y hora
        let year = now.getFullYear();
        let month = String(now.getMonth() + 1).padStart(2, '0');
        let day = String(now.getDate()).padStart(2, '0');
        let hours = now.getHours();
        let minutes = String(now.getMinutes()).padStart(2, '0');

        // Convertir a formato 12 horas
        let amPm = hours >= 12 ? 'PM' : 'AM';
        let hours12 = hours % 12 || 12;

        // Formato para la BD (YYYY-MM-DD HH:MM:SS)
        let formattedDateTimeDB = `${year}-${month}-${day} ${String(hours).padStart(2, '0')}:${minutes}:00`;

        // Formato para mostrar (DD/MM/YYYY HH:MM AM/PM)
        let formattedDateTimeDisplay = `${day}/${month}/${year} ${hours12}:${minutes} ${amPm}`;

        // Asignar valores
        document.getElementById("fechaHora").value = formattedDateTimeDB; // Para enviar a la BD
        document.getElementById("fechaHoraTexto").textContent = formattedDateTimeDisplay; // Para mostrar en pantalla
    });

    //FILTRAR CLIENTES EN FORMULARIO 
    let clientes = [];

    // Guardamos la lista original de clientes
    document.addEventListener("DOMContentLoaded", function() {
        let options = document.querySelectorAll("#cbcliente option");
        options.forEach(option => {
            if (option.value !== "0") {
                clientes.push({
                    codigo: option.value,
                    nombre: option.textContent
                });
            }
        });
    });

    function filtrarClientes() {
        let input = document.getElementById("buscarCliente").value.toLowerCase();
        let select = document.getElementById("cbcliente");

        // Limpiamos las opciones actuales
        select.innerHTML = "<option value='0'>Elegir</option>";

        // Filtramos los clientes según el texto ingresado
        let clientesFiltrados = clientes.filter(cliente => cliente.nombre.toLowerCase().includes(input));

        // Agregamos las nuevas opciones al select
        clientesFiltrados.forEach(cliente => {
            let option = document.createElement("option");
            option.value = cliente.codigo;
            option.textContent = cliente.nombre;
            select.appendChild(option);
        });
    }

    document.getElementById("cbempresa").addEventListener("change", function() {
        document.getElementById("cbnegocio").value = "0";
        document.getElementById("cbcliente").value = "0";
        document.getElementById("buscarCliente").value = "";
        document.getElementById("cbmoneda").value = "0";
        document.getElementById("importe").value = "";
        document.getElementById("txt_obs").value = "";
        document.getElementById("recibo_pago").innerText = "";

        const campos = ["cbnegocio", "cbcliente", "cbmoneda", "importe"];
        campos.forEach(id => {
            const campo = document.getElementById(id);
            campo.dispatchEvent(new Event("input"));
            campo.dispatchEvent(new Event("change"));
        });

        filtrarClientes();
    });

    document.addEventListener("DOMContentLoaded", function() {
        const form = document.getElementById("formrecibo");

        form.querySelectorAll("input, select, textarea").forEach((el) => {
            el.addEventListener("change", () => {
                if (el.checkValidity() && el.value.trim() !== "" && el.value !== "0") {
                    el.style.borderLeft = "5px solid green";
                } else if (el.required) {
                    el.style.borderLeft = "5px solid red";
                } else {
                    el.style.borderLeft = "";
                }
            });
        });
    });
</script>
<script src="../js/recibo_pago.js"></script>
<script src="../js/recibo_pago_online.js"></script>
<?php $xidform = "formbuscar";
include "pie.php";
?>