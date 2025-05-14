<?php
session_start();
require('../../../includes/dbmssql_cfg.php');
require('../../../themes.inc');

// Detalle del Recibo //
$idRecibo   = trim($_REQUEST['idRecibo']);
$tipo   = trim($_REQUEST['tipo']);

if ($tipo == 1) {
    $detalle_ree = "SELECT CCS.CodEmp, R.tipo_resp, CCS.cod_resp, R.Dni, R.Nombre, CCS.CodMon, CCS.Monto, CCS.tipo_cambio, 
    CCS.cod_categ, CCS.cod_concepto, CCS.comentario, CCS.FecReg, COUNT(A.cod_adjunto) AS TotalAdjuntos
    FROM REE.CAJACHICA_SAL CCS
    LEFT JOIN ree.REE_RESPONSABLES R ON CCS.cod_resp = R.cod_resp
    LEFT JOIN ree.REE_ADJUNTOS A ON CCS.cod_cc_sal = A.cod_cc_sal
    WHERE CCS.Estado != 'C' AND CCS.cod_cc_sal = $idRecibo
    GROUP BY CCS.CodEmp, R.tipo_resp, CCS.cod_resp, R.Dni, R.Nombre, CCS.CodMon, CCS.Monto, 
    CCS.tipo_cambio, CCS.cod_categ, CCS.cod_concepto, CCS.comentario, CCS.FecReg";
}


$query_detalle = $_SESSION['dbmssql']->getAll($detalle_ree);
foreach ($query_detalle as $id_ree => $all_ree) {
    $CodEmp         = trim($all_ree['CodEmp']);
    $tipo_resp      = trim($all_ree['tipo_resp']);
    $cod_resp      = trim($all_ree['cod_resp']);
    $Dni            = trim($all_ree['Dni']);
    $Nombre         = utf8_encode(trim($all_ree['Nombre']));
    $CodMon         = trim($all_ree['CodMon']);
    $Monto          = trim($all_ree['Monto']);
    $tipo_cambio    = trim($all_ree['tipo_cambio']);
    $cod_categ      = trim($all_ree['cod_categ']);
    $cod_concepto   = trim($all_ree['cod_concepto']);
    $comentario     = trim($all_ree['comentario']);
    $FecReg         = trim($all_ree['FecReg']);
    $TotalAdjuntos  = trim($all_ree['TotalAdjuntos']);
}

if ($_REQUEST['verRecibo']) {

?>
    <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="<?= $GLOBALS[color][12] ?>">
        <tr>
            <td height="100%">&nbsp;</td>

            <td valign="top">
                <table width="98%" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td width="100%" height="530" valign="top">
                            <table class="cajachica_egreso borderTabla1" width="100%" border="0" cellpadding="4" cellspacing="0" bgcolor="<?= $GLOBALS[color][18] ?>">
                                <!-- Empresa -->
                                <tr>
                                    <td width="115" height="23" valign="middle">
                                        &nbsp;<img src="images/dhtmlgoodies_minus.gif" width="18" height="13" align="absbottom"> Empresa
                                    </td>
                                    <td valign="middle">
                                        <select name="cbempresa" id="cbempresa" onchange="cargarSaldosMovimientos()" disabled style="width: 285px;">
                                            <option value="0">Elegir</option>
                                            <?php
                                            $sql_emp = "SELECT EmpCod, EmpRaz FROM EMPRESA WHERE EmpEst = 'A' ORDER BY EmpRaz";
                                            $conex_emp = $_SESSION['dbmssql']->getAll($sql_emp);
                                            foreach ($conex_emp as $v => $valor) {
                                                $cod_emp = $valor['EmpCod'];
                                                $nom_emp = $valor['EmpRaz'];

                                                if ($CodEmp == $cod_emp) {
                                                    $sel = 'selected';
                                                } else {
                                                    $sel = '';
                                                }

                                                echo "<option " . $sel . " value='" . $cod_emp . "'>" . $nom_emp . "</option>";
                                            }
                                            ?>
                                        </select>
                                        <span class="Estilo100">*</span>
                                    </td>
                                </tr>
                                <tr height="12"></tr>

                                <tr>
                                    <td colspan="3" height="26" valign="middle" background="images/fhbg.gif">
                                        <strong>&nbsp;<img src="images/impressions.png" width="16" height="16" align="absmiddle"> SALDOS CAJA CHICA</strong>
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="4" height="35" align="center" valign="middle">
                                        <div id="saldos" style="margin-top: 10px;">
                                            <p style="font-size: 15px;" class="Estilo100">Seleccione una empresa para ver el saldo actual</p>
                                        </div>
                                    </td>
                                </tr>



                                <tr height="12"></tr>

                                <tr>
                                    <td colspan="3" height="26" valign="middle" background="images/fhbg.gif">
                                        <strong>&nbsp;<img src="images/license.png" width="16" height="16" align="absmiddle"> RECIBO DE ENTREGA DE EFECTIVO</strong>
                                        <input type="hidden" name="usuario" id="usuario" value="<?= $_SESSION['percod'] ?>">
                                    </td>
                                </tr>
                                <!-- <tr>
                                <td colspan="4" height="35" align="left" valign="middle">
                                    &nbsp;Los Casilleros con <span class="Estilo100">*</span> son Datos Obligatorios
                                </td>
                            </tr> -->

                                <!-- NRO DE RECIBO -->
                                <tr>
                                    <td colspan="4" height="15" align="center" valign="middle">

                                    </td>
                                </tr>

                                <!-- TIPO -->
                                <tr>
                                    <td width="115" height="23" valign="middle">
                                        &nbsp;<img src="images/dhtmlgoodies_minus.gif" width="18" height="13" align="absbottom"> Tipo
                                    </td>

                                    <?php
                                    $check1 = ($tipo_resp == "1") ? "checked" : "";
                                    $check2 = ($tipo_resp == "2") ? "checked" : "";
                                    ?>

                                    <td valign="middle">
                                        <label>
                                            <input type="radio" name="tipo_resp" value="1" id="1" <?= $check1 ?> onchange="limpiarReceptor()"> Interno
                                        </label>&nbsp;&nbsp;
                                        <label>
                                            <input type="radio" name="tipo_resp" value="2" id="2" <?= $check2 ?> onchange="limpiarReceptor()"> Externo
                                        </label>
                                    </td>
                                </tr>
                                <!-- DNI -->
                                <tr>
                                    <td height="23" valign="middle">
                                        &nbsp;<img src="images/dhtmlgoodies_minus.gif" width="18" height="13" align="absbottom"> DNI o CE
                                    </td>
                                    <td valign="middle">
                                        <input name="dni" id="dni" type="text" size="19" value="<?= $Dni ?>" oninput="validarNumero(this)" onkeydown="if(event.key === 'Enter') { event.preventDefault(); ree_ver_datos_dni(); }">
                                        <button name="boton" onClick="ree_ver_datos_dni()" class="button2" type="button" id="btnConsultarDNI">
                                            <img width="15" height="14" src="images/b_search.png" align="absmiddle">&nbsp;Consultar
                                        </button>
                                        <button name="boton" onClick="ree_crear_nuevo_dni()" class="button2" type="button" id="ree_crear_responsable" disabled>
                                            <img width="15" height="14" src="images/add.png" align="absmiddle">&nbsp;Crear
                                        </button>
                                        <span class="Estilo100">*</span>
                                        <div id="nuevo_responsable" style="display: none;">
                                            <input type="text" id="responsable_input" placeholder="Ingrese el Nombre Completo" size="33" maxlength="60" style="text-transform: uppercase;" oninput="this.value = this.value.toUpperCase();">
                                            <button type="button" class="button2" onclick="guardarNuevoDNI()">
                                                <img width="15" height="14" src="images/btn_guardar.png" align="absmiddle">&nbsp;Guardar
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Responsable -->
                                <tr>
                                    <td width="115" height="23" valign="middle">
                                        &nbsp;<img src="images/dhtmlgoodies_minus.gif" width="18" height="13" align="absbottom"> Responsable
                                    </td>
                                    <td valign="middle">
                                        <input name="idreceptor" type="text" id="idreceptor" value="<?= $cod_resp ?>" hidden disabled>
                                        <input name="receptor" type="text" id="receptor" value="<?= $Nombre ?>" size="46" maxlength="60" disabled>
                                        <span class="Estilo100">*</span>
                                    </td>
                                </tr>


                                <!-- Monto -->
                                <tr>
                                    <td width="115" height="23" valign="middle">
                                        &nbsp;<img src="images/dhtmlgoodies_minus.gif" width="18" height="13" align="absbottom"> Monto
                                    </td>
                                    <td width="60" valign="middle">
                                        <span id="saldo_alert" style="color: red; display: none; margin-bottom: 0px;">Monto excede el saldo disponible</span>
                                        <div>
                                            <select name="cbtipomoneda" id="cbtipomoneda" style="width: 60px;" onchange="checkSaldoDisponible()">
                                                <option value="0">Elegir</option>
                                                <?php
                                                $sql_tipomon = "SELECT CodMon, SimMon FROM MONEDA WHERE Estado = 'A' AND LetMon IN ('S','D','E')";
                                                $conex_tipomon = $_SESSION['dbmssql']->getAll($sql_tipomon);
                                                foreach ($conex_tipomon as $v => $valor) {
                                                    $cod_mon = $valor['CodMon'];
                                                    $nom_mon = $valor['SimMon'];

                                                    if ($CodMon == $cod_mon) {
                                                        $sel = 'selected';
                                                    } else {
                                                        $sel = '';
                                                    }

                                                    echo "<option " . $sel . " value='" . $cod_mon . "'>" . $nom_mon . "</option>";
                                                }
                                                ?>
                                            </select>
                                            <input name="monto" type="text" id="monto" value="<?= $Monto ?>" size="10" maxlength="10" style="margin-left:2px;" oninput="validarMonto(this)">

                                            <span class="Estilo100" style="margin-right:30px;">*</span>
                                            T.C<input name="tipo_cambio" type="text" placeholder="Tipo Cambio" value="<?= $tipo_cambio ?>" id="tipo_cambio" size="10" maxlength="10" disabled style="margin-left:10px;" oninput="validarNumero(this)">
                                        </div>
                                    </td>
                                </tr>

                                <!-- Categoria -->
                                <tr>
                                    <td width="115" height="23" valign="middle">
                                        &nbsp;<img src="images/dhtmlgoodies_minus.gif" width="18" height="13" align="absbottom"> Categoría
                                    </td>
                                    <td width="60" valign="middle">
                                        <select name="cbcategoria" id="cbcategoria" style="width: 258px;" onchange="cambiarEstadoTC()">
                                            <option value="0">Elegir</option>
                                            <?php
                                            $sql_categoria = "SELECT cod_categ, Nombre FROM ree.REE_CATEGORIAS WHERE Estado = 'I' ORDER BY Nombre";
                                            $conex_categoria = $_SESSION['dbmssql']->getAll($sql_categoria);
                                            foreach ($conex_categoria as $v => $valor) {
                                                $cod_cat = $valor['cod_categ'];
                                                $nom_cat = utf8_encode($valor['Nombre']);

                                                if ($cod_categ == $cod_cat) {
                                                    $sel = 'selected';
                                                } else {
                                                    $sel = '';
                                                }

                                                echo "<option " . $sel . " value='" . $cod_cat . "'>" . $nom_cat . "</option>";
                                            }
                                            ?>
                                        </select>
                                        <button name="boton" onClick="agregarNuevaCategoria()" class="button2" type="button" id="btnCrearCategoria">
                                            <img width="15" height="14" src="images/add.png" align="absmiddle">&nbsp;Crear
                                        </button>
                                        <span class="Estilo100">*</span>

                                        <div id="nueva_categoria_container" style="display: none;">
                                            <input type="text" id="nueva_categoria_input" placeholder="Ingrese nueva categoría" size="33" maxlength="60" style="text-transform: uppercase;" oninput="this.value = this.value.toUpperCase();">
                                            <button type="button" class="button2" onclick="guardarNuevaCategoria()">
                                                <img width="15" height="14" src="images/btn_guardar.png" align="absmiddle">&nbsp;Guardar
                                            </button>
                                        </div>

                                    </td>
                                </tr>

                                <!-- Concepto -->
                                <tr>
                                    <td width="115" height="23" valign="middle">
                                        &nbsp;<img src="images/dhtmlgoodies_minus.gif" width="18" height="13" align="absbottom"> Concepto
                                    </td>
                                    <td width="360" valign="middle">

                                        <input type="text" id="filtroConcepto" placeholder="Filtrar concepto..." size="46" maxlength="60" style="text-transform: uppercase;" onkeyup=" filtrarConceptos()">
                                        <select name="cbconcepto" id="cbconcepto" style="width: 258px;">
                                            <option value="0">Elegir</option>
                                            <?php
                                            $sql_concepto = "SELECT cod_concepto, Descripcion FROM ree.REE_CONCEPTOS WHERE Estado = 'I' ORDER BY Descripcion";
                                            $conex_concepto = $_SESSION['dbmssql']->getAll($sql_concepto);
                                            foreach ($conex_concepto as $v => $valor) {
                                                $cod_con = $valor['cod_concepto'];
                                                $nom_con = utf8_encode($valor['Descripcion']);

                                                if ($cod_concepto == $cod_con) {
                                                    $sel = 'selected';
                                                } else {
                                                    $sel = '';
                                                }

                                                echo "<option " . $sel . " value='" . $cod_con . "'>" . $nom_con . "</option>";
                                            }
                                            ?>
                                        </select>
                                        <button name="boton" onClick="agregarNuevoConcepto()" class="button2" type="button" id="btnCrearConcepto">
                                            <img width="15" height="14" src="images/add.png" align="absmiddle">&nbsp;Crear
                                        </button>
                                        <span class="Estilo100">*</span>

                                        <div id="nuevo_concepto_container" style="display: none;">
                                            <input type="text" id="nuevo_concepto_input" placeholder="Ingrese nuevo concepto" size="33" maxlength="60" style="text-transform: uppercase;" oninput="this.value = this.value.toUpperCase();">
                                            <button type="button" class="button2" onclick="guardarNuevoConcepto()">
                                                <img width="15" height="14" src="images/btn_guardar.png" align="absmiddle">&nbsp;Guardar
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <!-- Comentario -->
                                <tr>
                                    <td width="115" height="23" valign="middle">
                                        &nbsp;<img src="images/dhtmlgoodies_minus.gif" width="18" height="13" align="absbottom"> Comentario
                                    </td>
                                    <td valign="middle">
                                        <textarea name="comentario" id="comentario" rows="2" cols="45"><?= $comentario ?></textarea>
                                    </td>
                                </tr>
                                <!-- Fecha y Boton Adjuntar -->
                                <tr>
                                    <td width="115" height="23" valign="middle">
                                        &nbsp;<img src="images/dhtmlgoodies_minus.gif" width="18" height="13" align="absbottom"> Fecha
                                    </td>
                                    <td width="340" valign="middle" style="display:flex;">
                                        <div>
                                            <input type="date" name="fecha_recibo" id="fecha_recibo" value="<?= date('Y-m-d', strtotime($FecReg)) ?>" disabled required>
                                        </div><span class="Estilo100">*</span> &nbsp;&nbsp;&nbsp;
                                        <div class='foto-container'>
                                            <label id='etiquetaFoto' class='botonNuevo'>
                                                <input type='file' id='adjuntarArchivos' name='img_adjunta[]' accept='image/*,.pdf,.doc,.docx' multiple style='display: none;' onchange='actualizarTextoBoton(this)'>📎&nbsp;<span id="textoBoton">Adjuntar</span>
                                            </label>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Botones -->
                                <tr>
                                    <td height="10"></td>
                                </tr>
                                <tr>
                                    <td colspan="3" align="center" valign="middle" height="39">
                                        <button name="boton" onClick="actualizarReciboEntrega(<?= $idRecibo ?>)" class="button2" type="button" id="btn_guardar">
                                            <img width="15" height="14" src="images/btn_guardar.png" align="absmiddle">&nbsp;Actualizar
                                        </button>
                                        &nbsp;&nbsp;&nbsp;
                                        <button name="boton" onClick="imprimirReciboEntrega(<?= $idRecibo ?>)" class="button2" type="button" id="btn_imprimir">
                                            <img width="15" height="14" src="images/print.png" align="absmiddle">&nbsp;Imprimir
                                        </button>
                                        &nbsp;&nbsp;&nbsp;
                                        <button name="boton" onClick="nuevoReciboEntrega()" class="button2" type="button" id="btn_nuevo">
                                            <img width="15" height="14" src="images/new_docu.png" align="absmiddle">&nbsp;Nuevo
                                        </button>
                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>
                </table>

            </td>
        </tr>

        <tr>
            <td height="19">&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td height="10"></td>
            <td></td>
            <td></td>
            <td width="5"></td>
        </tr>
    </table>


<?php
}
