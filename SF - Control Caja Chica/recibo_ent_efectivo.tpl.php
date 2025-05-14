<?
session_start();
require('includes/class_caja_chica.php');
?>

<body>

    <head>
        <style>
            /* 📌 NOTA: ESTILOS PESTAÑAS  */
            .tab {
                overflow: hidden;
                border: 1px solid #ccc;
                background-color: #f1f1f1;
            }

            /* Estilo de botones dentro de los Tabs */
            .tab button {
                background-color: inherit;
                float: left;
                border: none;
                outline: none;
                cursor: pointer;
                padding: 10px 16px;
                transition: 0.3s;
                font-size: 15px;
            }

            /* Cambiar el color al pasar el mouse */
            .tab button:hover {
                background-color: #ddd;
            }

            /* Crear una clase tablink activa/actual */
            .tab button.active {
                background-color: #ccc;
            }

            /* Dale estilo al contenido de la pestaña */
            .tabcontent {
                display: none;
                padding: 0px 0px;
                border: 0px none;
                color: #ccc;
                border-top: none;
            }

            /* ESTILOS FORM CAJA CHICA */
            input[type="text"],
            input[type="number"],
            input[type="textarea"],
            input[type="date"],
            input[type="file"],
            select,
            textarea,
            button {
                padding: 8px 10px;
                font-size: 12px;
                margin-bottom: 5px;
                border: 1px solid #ccc;
                border-radius: 4px;
            }

            input[type="text"]:focus,
            input[type="number"]:focus,
            input[type="textarea"]:focus,
            input[type="date"]:focus,
            input[type="file"],
            select:focus,
            textarea:focus,
            button:focus {
                outline: none;
                border: 1px solid #007bff;
                box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
                transition: all 0.1s ease-in-out;
            }

            .botonNuevo {
                display: inline-block;
                background-color: #323a51;
                color: white;
                padding: 8px 16px;
                border-radius: 5px;
                cursor: pointer;
                font-size: 12px;
                transition: background-color 0.2s ease, opacity 0.2s ease;
                border: none;
            }

            .botonNuevo:hover {
                background-color: #505c7a;
            }

            /* Cuando el botón está deshabilitado */
            .botonNuevo:disabled {
                background-color: #a0a0a0;
                color: #eee;
                cursor: not-allowed;
                opacity: 0.7;
            }

            .cajachica_egreso button {
                padding: 8px 10px;
                font-size: 12px;
                margin-left: 5px;
                cursor: pointer;
                border: none;
                border-radius: 4px;
                background-color: #323a51;
                color: white;
            }

            .cajachica_egreso button:hover {
                background-color: rgb(126, 127, 127);
            }

            .cajachica_egreso button:disabled {
                background-color: #ccc;
                color: #666;
                cursor: not-allowed;
                opacity: 0.7;
            }

            .cajachica_egreso td {
                padding: 3px 5px;
                font-size: 12px;
            }
        </style>
    </head>

    <div class="tab">
        <button class="tablinks active" onClick="ree_muestracontenedor(event, 'RECIBO_ENTREGA')">EGRESO CAJA CHICA</button>
        <button class="tablinks" onClick="ree_muestracontenedor(event, 'RECIBO_ENTREGA')">INGRESO CAJA CHICA</button>
        <div id="mensajeExito" style=" background-color: green; display:none; color: white;font-size: 12px; width: 30%;  float: right; padding: 12px 14px; text-align: center; "></div>
        <div id="mensajeError" style=" background-color: red; display:none; color: white;font-size: 10px; width: 30%;  float: right; padding: 12px 14px; text-align: center; "></div>
    </div>


    <form name="form1" method="post" action="" target="_blank">
        <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="<?= $GLOBALS[color][12] ?>">
            <tr>
                <td width="4" height="19">&nbsp;</td>
                <td width="35%">&nbsp;</td>
                <td width="75%">&nbsp;</td>
                <td width="9">&nbsp;</td>
            </tr>
            <tr>
                <td height="436">&nbsp;</td>
                <td valign="top">
                    <!------------------------ INICIO CABECERA - RECIBO ENTREGA EFECTIVO --------------------->
                    <div id="RECIBO_ENTREGA" class="tabcontent" style="display: block;">

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
                                                            <select name="cbempresa" id="cbempresa" onchange="cargarSaldosMovimientos()" style="width: 285px;">
                                                                <option value="0">Elegir</option>
                                                                <?php
                                                                $sql_emp = "SELECT EmpCod, EmpRaz FROM EMPRESA WHERE EmpEst = 'A' ORDER BY EmpRaz";
                                                                $conex_emp = $_SESSION['dbmssql']->getAll($sql_emp);
                                                                foreach ($conex_emp as $v => $valor) {
                                                                    $cod_emp = $valor['EmpCod'];
                                                                    $nom_emp = $valor['EmpRaz'];
                                                                    echo "<option value='" . $cod_emp . "'>" . $nom_emp . "</option>";
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
                                                            <input type="hidden" name="cod_ccs" id="cod_ccs" value="0">
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
                                                        <td valign="middle">
                                                            <label><input type="radio" name="tipo_resp" value="1" id="1" checked onchange="limpiarReceptor()"> Interno</label>&nbsp;&nbsp;
                                                            <label><input type="radio" name="tipo_resp" value="2" id="2" onchange="limpiarReceptor()"> Externo</label>
                                                        </td>
                                                    </tr>
                                                    <!-- DNI -->
                                                    <tr>
                                                        <td height="23" valign="middle">
                                                            &nbsp;<img src="images/dhtmlgoodies_minus.gif" width="18" height="13" align="absbottom"> DNI o CE
                                                        </td>
                                                        <td valign="middle">
                                                            <input name="dni" id="dni" type="text" size="19" oninput="validarNumero(this)" onkeydown="if(event.key === 'Enter') { event.preventDefault(); ree_ver_datos_dni(); }">
                                                            <button name="boton" onClick="ree_ver_datos_dni()" class="button2" type="button" id="btnConsultarDNI">
                                                                <img width="15" height="14" src="images/b_search.png" align="absmiddle">&nbsp;Consultar
                                                            </button>
                                                            <button name="boton" onClick="ree_crear_nuevo_dni()" class="button2" type="button" id="ree_crear_responsable" disabled>
                                                                <span id="textoBtnCrearDni"><img width="15" height="14" src="images/add.png" align="absmiddle">&nbsp;Crear</span>
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
                                                            <input name="idreceptor_n" type="text" id="idreceptor" hidden disabled>
                                                            <input name="receptor_n" type="text" id="receptor" size="46" maxlength="60" disabled>
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
                                                                        echo "<option value='" . $cod_mon . "'>" . $nom_mon . "</option>";
                                                                    }
                                                                    ?>
                                                                </select>
                                                                <input name="monto" type="text" id="monto" size="10" maxlength="10" style="margin-left:2px;" oninput="validarMonto(this)">

                                                                <span class="Estilo100" style="margin-right:30px;">*</span>
                                                                T.C<input type="text" placeholder="Tipo Cambio" id="tipo_cambio" size="10" maxlength="10" disabled style="margin-left:10px;" oninput="validarNumero(this)">
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
                                                                    echo "<option value='" . $cod_cat . "'>" . $nom_cat . "</option>";
                                                                }
                                                                ?>
                                                            </select>
                                                            <button name="boton" onClick="agregarNuevaCategoria()" class="button2" type="button" id="btnCrearCategoria">
                                                                <span id="textoBtnCategoria"><img width="15" height="14" src="images/add.png" align="absmiddle">&nbsp;Crear</span>
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
                                                                    echo "<option value='" . $cod_con . "'>" . $nom_con . "</option>";
                                                                }
                                                                ?>
                                                            </select>
                                                            <button name="boton" onClick="agregarNuevoConcepto()" class="button2" type="button" id="btnCrearConcepto">
                                                                <span id="textoBtnConcepto"><img width="15" height="14" src="images/add.png" align="absmiddle">&nbsp;Crear</span>
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
                                                            <textarea name="comentario" id="comentario" rows="2" cols="45"></textarea>
                                                        </td>
                                                    </tr>
                                                    <!-- Fecha y Boton Adjuntar -->
                                                    <tr>
                                                        <td width="115" height="23" valign="middle">
                                                            &nbsp;<img src="images/dhtmlgoodies_minus.gif" width="18" height="13" align="absbottom"> Fecha
                                                        </td>
                                                        <td width="340" valign="middle" style="display:flex;">
                                                            <div>
                                                                <input type="date" name="fecha_recibo" id="fecha_recibo" value="<?= date('Y-m-d') ?>" disabled required>
                                                            </div><span class="Estilo100">*</span> &nbsp;&nbsp;&nbsp;
                                                            <button id="adjuntarArchivos" onclick="" class="button2" type="button">📎&nbsp;Adjuntar</button>
                                                        </td>
                                                    </tr>

                                                    <!-- Botones -->
                                                    <tr>
                                                        <td height="10"></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3" align="center" valign="middle" height="39">

                                                            <button name="boton" onClick="guardarReciboEntrega()" class="button2" type="button" id="btn_guardar">
                                                                <img width="15" height="14" src="images/btn_guardar.png" align="absmiddle">&nbsp;Guardar
                                                            </button>
                                                            &nbsp;&nbsp;&nbsp;
                                                            <button name="boton" onClick="imprimirReciboEntrega()" class="button2" type="button" id="btn_imprimir" disabled>
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
                    </div>
                </td>
                <td valign="top">
                    <!------------------------ ULTIMOS MOVIMIENTOS EN CAJA CHICA --------------------->
                    <table width="100%" border="0" cellpadding="0" cellspacing="0" class="borderTabla1">
                        <tr>
                            <td width="100%" height="26" valign="middle" background="images/fhbg.gif">
                                <strong>&nbsp;
                                    <img src="images/table.png" width="22" height="22" align="absmiddle" />
                                    ULTIMOS MOVIMIENTOS EN CAJA CHICA - <span id="mov_empresa"></span>
                                </strong>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                        </tr>
                        <tr>
                            <td height="100%" valign="top">
                                <table width="100%" border="0" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td width="100%" height="24" valign="top">
                                            <table width="100%" border="0" cellpadding="0" cellspacing="1" class="borderTabla">
                                                <tr>
                                                    <td width="100" height="24" align="center" valign="middle" background="images/bg.gif"><strong>FECHA</strong></td>
                                                    <td width="150" height="24" align="center" valign="middle" background="images/bg.gif"><strong>RESPONSABLE</strong></td>
                                                    <td width="130" align="center" valign="middle" background="images/bg.gif"><strong>CATEGORIA</strong></td>
                                                    <td width="150" align="center" valign="middle" background="images/bg.gif"><strong>CONCEPTO</strong></td>
                                                    <td width="80" align="center" valign="middle" background="images/bg.gif"><strong>MONTO</strong></td>
                                                    <td width="110" align="center" valign="middle" background="images/bg.gif"><strong>OPCIONES</strong></td>
                                                </tr>

                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>

                        <tr>
                            <td height="100%" valign="top">
                                <div id="ultimos_movimientos" style="height:439; width:100%; overflow:auto">
                                    <?
                                    // ✅MOSTRAR ULTIMOS MOVIMIENTOS POR EMPRESA
                                    // $cajaChica = new cajaChica();
                                    // $cajaChica->ver_movimientos($cod_emp);
                                    ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="15" bgcolor="#C0D8E0" height="1"></td>
                        </tr>
                        <tr>
                            <td height="30" valign="top" style="display: flex; align-items:center">&nbsp;::&nbsp;
                                <img src="images/template.gif" width="18" height="14"><strong>Ver</strong>&nbsp;&nbsp;&nbsp;
                                <img src="images/docs.png" width="18" height="14"><strong>Rendición</strong>&nbsp;&nbsp;&nbsp;
                                <img src="images/publish_x.png" width="12" height="12"><strong>Eliminar</strong>
                            </td>
                        </tr>
                    </table>


                    <table width="100%" border="0">
                        <tr>
                            <td height="2"><!-- --></td>
                        </tr>
                    </table>

                    <table width="100%" border="0" cellpadding="0" cellspacing="0" class="borderTabla1" bgcolor="<?= $GLOBALS[color][18] ?>">
                        <tr>
                            <td width="480" height="26" valign="middle" background="images/fhbg.gif">&nbsp;<img src="images/b_search.png" width="16" height="16"> <strong>BÚSQUEDA DE RECIBO</strong> </td>
                        </tr>
                        <tr>
                            <td height="34" valign="middle">&nbsp;<img src="images/dhtmlgoodies_minus.gif" width="16" height="16" align="absmiddle"> Ingresar nombre de Receptor o Concepto:
                                <input name="ultimosMovimientos" id="filtrarMovimientos" type="text" size="50" onKeyUp="filtrarMovimientos()" style="text-transform: uppercase;" oninput="this.value = this.value.toUpperCase();">
                            </td>
                        </tr>
                    </table>
                </td>

                <!----------------- FIN DE RECIBO ENTREGA EFECTIVO -------------->

                <!----------------- INGRESO DE CAJA CHICA -------------->
                <!----------------- FIN DE TOTALES -------------->
            </tr>
            <tr>
                <td height=" 8" align="center">
                </td>
            </tr>
        </table>
        <br>

        <br>
        </td>
        <td valign="top">&nbsp;</td>
        </tr>
        </table>
    </form>
</body>