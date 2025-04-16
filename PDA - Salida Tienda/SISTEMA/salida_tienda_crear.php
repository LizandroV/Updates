<?php
error_reporting(E_ALL);
include "head.php";
?>

<h4>Salida de Tienda <span id="salida_tienda" class="ultimoST"></h4>
<p id="respuesta"></p>
<form method="POST" enctype="multipart/form-data" name="stForm" class="stForm-det">
    <input type="hidden" name="usuario" id="usuario" value="<?= $_SESSION['micodigo'] ?>" />
    <div class="stHead">
        <div>
            <label for="stmotivo"><strong>Motivo:</strong></label><br>
            <select name="stmotivo" id="stmotivo" required>
                <option value="0">Elegir</option>
                <?php
                $sql_mot = "SELECT codtraslado, descrip FROM $bd.alm.motivo_traslado 
                WHERE estado='0' and familia='tienda' and codtraslado=18";
                $motivo = db_fetch_all($sql_mot);
                foreach ($motivo as $mot) {
                    $cod_mot = trim($mot['codtraslado']);
                    $mot_des = trim($mot['descrip']);
                    $selected = ($cod_mot == 18) ? 'selected' : '';
                    echo "<option value='$cod_mot' $selected>$mot_des</option>";
                }
                ?>
            </select>
        </div>
        <div>
            <label for="stfecha"><strong>Fecha:</strong></label><br>
            <input type="date" name="stfecha" id="stfecha" value="<?php echo date('Y-m-d'); ?>">
        </div>
    </div>

    <div class="stTransfer">
        <div class="stSection">
            <h5>📦 Origen</h5>
            <div class="stFila">
                <div class="stAlmacen">
                    <label for="stalmaceno">Almacén</label>
                    <select name="stalmaceno" id="stalmaceno" required>
                        <option value="0">Elegir</option>
                        <?php
                        $sql_almo = "SELECT CodAlmacen, Almacen FROM $bd.im.Almacen 
                        WHERE CodAlmacen IN(10016,10017) ORDER BY 1 ASC";
                        $alm_o = db_fetch_all($sql_almo);
                        foreach ($alm_o as $almo) {
                            $cod_alm = trim($almo['CodAlmacen']);
                            $alm_ori = trim($almo['Almacen']);
                            echo "<option value='$cod_alm'>$alm_ori</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="stAlmacen">
                    <label for="stempresao">Empresa</label>
                    <select name=" stempresao" id="stempresao" required>
                        <option value="0">Elegir</option>
                        <?php
                        $sql_empo = "SELECT EmpCod, EmpRaz FROM $bd.dbo.EMPRESA 
                        WHERE EmpEst='A' AND EmpCod in(4,9) ORDER BY EmpRaz ASC";
                        $emp_o = db_fetch_all($sql_empo);
                        foreach ($emp_o as $empo) {
                            $cod_emp = trim($empo['EmpCod']);
                            $emp_des = trim($empo['EmpRaz']);
                            echo "<option value='$cod_emp'>$emp_des</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>
        </div>

        <div class="stSection">
            <h5>🏢 Destino</h5>
            <div class="stFila">
                <div class="stAlmacen">
                    <label for="stalmacend">Almacén</label>
                    <select name=" stalmacend" id="stalmacend" required>
                        <option value="0">Elegir</option>
                        <?php
                        $sql_almd = "SELECT CodAlmacen, Almacen FROM $bd.im.Almacen 
                        WHERE CodAlmacen IN(10016,10017) ORDER BY 1 ASC";
                        $alm_d = db_fetch_all($sql_almd);
                        foreach ($alm_d as $almo) {
                            $cod_alm = trim($almo['CodAlmacen']);
                            $alm_des = trim($almo['Almacen']);
                            echo "<option value='$cod_alm'>$alm_des</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="stAlmacen">
                    <label for="stempresad">Empresa</label>
                    <select name=" stempresad" id="stempresad" required>
                        <option value="0">Elegir</option>
                        <?php
                        $sql_empd = "SELECT EmpCod, EmpRaz FROM $bd.dbo.EMPRESA 
                        WHERE EmpEst='A' AND EmpCod in(4,9) ORDER BY EmpRaz ASC";
                        $emp_d = db_fetch_all($sql_empd);
                        foreach ($emp_d as $empd) {
                            $cod_emp = trim($empd['EmpCod']);
                            $emp_des = trim($empd['EmpRaz']);
                            echo "<option value='$cod_emp'>$emp_des</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="stRollos">
        <label>Agregar Rollos a ST<br></label>
        <input type="text" name="codbarra" id="codbarra" placeholder="Escanear código de barras..." required>
    </div>

    <h4>Detalle de Rollos</h4>

    <table class="texto tableM" id="detalle-rollos" style="width: 100%;">
        <thead>
            <tr>
                <th>ITEM</th>
                <th>COD PROD</th>
                <th>COD BARRA</th>
                <th>PARTIDA</th>
                <th>PRODUCTO</th>
                <th>PROCESO</th>
                <th>COLOR</th>
                <th>CANT ROLLOS</th>
                <th>PESO KG</th>
                <th>ACCION</th>
            </tr>
        </thead>
        <tbody id="contenedor_detalle">

        </tbody>
        <tfoot class="footer-table" style="width: 100%;">
            <tr style="background: transparent; height:20px; border: none">
                <td colspan="10">
                    <hr>
                </td>
            </tr>
            <tr>
                <td id="totalTbl" colspan="7" style="text-align: right; font-size: 12px; font-weight: bold;">TOTAL:</td>
                <td data-label="Total Rollos" class="total" align="center" style="font-size: 12px; font-weight: bold;">
                    <input type="text" name="txt_total_rollos" id="txt_total_rollos" value="0" readonly disabled style="width: 100px; text-align: center;" />
                </td>
                <td data-label="Total KG" class="total" align="center" style="font-size: 12px; font-weight: bold;">
                    <input type="text" name="txt_totalkg" id="txt_totalkg" value="0" readonly disabled style="width: 100px; text-align: center;" />
                </td>
                <td style="font-size: 12px; font-weight: bold;"></td>
            </tr>
        </tfoot>
    </table>

    <div class="buttons">
        <button type="button" class="btn2" id="btn-guardar">💾&nbsp;Guardar</button>
        <a href="salida_tienda.php" class="btn2" id="btn-volver">↩️&nbsp;Volver</a>
        <button type="button" class="btn2" id="btn-nuevo">📄&nbsp;Nuevo</button>
    </div>
</form>

<script>
    // 📌 NOTA: Abrir Calendario
    stfecha.addEventListener('focus', () => {
        stfecha.showPicker && stfecha.showPicker();
    });

    // 📌 NOTA: Boton Guardar
    document.getElementById('btn-guardar').addEventListener('click', function() {
        registrar_salida_tienda();

    });

    // 📌 NOTA: Boton Nuevo - Limpiar
    document.getElementById('btn-nuevo').addEventListener('click', function() {

        if (document.getElementById("salida_tienda_final")) {
            let spanCodSalida = document.getElementById("salida_tienda_final");
            spanCodSalida.innerHTML = "";
            spanCodSalida.id = "salida_tienda";
        }

        if (document.getElementById("salida_tienda")) {
            let CodSalida = document.getElementById("salida_tienda");
            CodSalida.innerHTML = "";
        }

        let respuesta = document.getElementById("respuesta");
        respuesta.value = '';
        respuesta.style.display = "none";
        respuesta.className = "";

        const hoy = new Date();
        const yyyy = hoy.getFullYear();
        const mm = String(hoy.getMonth() + 1).padStart(2, '0');
        const dd = String(hoy.getDate()).padStart(2, '0');

        const selectMotivo = document.getElementById("stmotivo").value = 18;
        document.getElementById('stfecha').value = `${yyyy}-${mm}-${dd}`;
        document.getElementById("stmotivo").disabled = false;
        document.getElementById("stfecha").disabled = false;
        document.getElementById("stalmaceno").disabled = false;
        document.getElementById("stempresao").disabled = false;
        document.getElementById("stalmacend").disabled = false;
        document.getElementById("stempresad").disabled = false;
        document.getElementById("codbarra").disabled = false;
        document.getElementById('stalmaceno').value = '0';
        document.getElementById('stempresao').value = '0';
        document.getElementById('stalmacend').value = '0';
        document.getElementById('stempresad').value = '0';
        document.getElementById('codbarra').value = '';
        document.getElementById('contenedor_detalle').innerHTML = '';
        document.getElementById('txt_total_rollos').value = '0';
        document.getElementById('txt_totalkg').value = '0';
        document.getElementById("btn-guardar").disabled = false;

    });
    // 📌 NOTA: Limpiar al cambiar almacen
    function limpiarCampos() {
        document.getElementById('codbarra').value = '';
        document.getElementById('txt_total_rollos').value = '0';
        document.getElementById('txt_totalkg').value = '0';
        document.getElementById('contenedor_detalle').innerHTML = '';
    }
    document.getElementById('stempresao').addEventListener("change", limpiarCampos);
    document.getElementById('stalmaceno').addEventListener("change", limpiarCampos);

    // 📌 NOTA: Agregar Cod Barras
    document.addEventListener("DOMContentLoaded", function() {
        const input = document.getElementById("codbarra");

        input.addEventListener("keydown", function(event) {
            if (event.key === "Enter") {
                event.preventDefault();
                buscar_codbarra_saltienda();
            }
        });
    });
</script>
<script src="../js/salida_tienda.js"></script>
<script src="../js/salida_tienda_online.js"></script>
<?php include "pie.php"; ?>