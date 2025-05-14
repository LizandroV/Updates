<?
session_start();
require('includes/class_cliente.php');
?>

<style>
    /* Estilo de Tabs */
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
        padding: 14px 16px;
        transition: 0.3s;
        font-size: 17px;
    }

    /* Cambiar el color de fondo de los botones al pasar el mouse */
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
</style>

<div class="tab">
    <button class="tablinks active" onClick="muestracontenedor(event, 'HILO')">RECIBO ENTREGA EFECTIVO</button>
    <button class="tablinks" onClick="muestracontenedor(event, 'QUIMICOS')">INGRESO CAJA CHICA</button>
    <div id="mensajeExito" style=" background-color: green; display:none; color: white;font-size: 12px; width: 30%;  float: right; padding: 12px 14px; text-align: center; "></div>
    <div id="mensajeError" style=" background-color: red; display:none; color: white;font-size: 10px; width: 30%;  float: right; padding: 12px 14px; text-align: center; "></div>

    <!-- <div id='' style="display:none; color: red;font-size: 12px; width: 90%;margin: 0 auto; padding: 14px 16px; text-align: right;" ></div> -->
</div>

<body>
    <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="<?= $GLOBALS[color][12] ?>">
        <tr>
            <td width="4" height="19">&nbsp;</td>
            <td width="400">&nbsp;</td>
            <td width="620">&nbsp;</td>
            <td width="9">&nbsp;</td>
        </tr>

        <tr>
            <td height="100%">&nbsp;</td>

            <?php //📌 NOTA: FORMULARIO DE REGISTRO DE RECIBO ENTREGA EFECTIVO
            ?>
            <td valign="top">
                <table width="98%" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td width="100%" height="530" valign="top">
                            <table width="100%" border="0" cellpadding="4" cellspacing="0" class="borderTabla1" bgcolor="<?= $GLOBALS[color][18] ?>">
                                <!-- Empresa -->
                                <tr>
                                    <td width="115" height="23" valign="middle">
                                        &nbsp;<img src="images/dhtmlgoodies_minus.gif" width="18" height="13" align="absbottom"> Empresa
                                    </td>
                                    <td valign="middle">:
                                        <select name="cbempresa" id="cbempresa" style="width: 285px;">
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
                                    <td align="center" valign="middle">
                                        <p>SOLES: </p>
                                    </td>
                                    <td align="center" valign="middle">
                                        <p>DOLARES: </p>
                                    </td>
                                    <td align="center" valign="middle">
                                        <p>EUROS: </p>
                                    </td>
                                </tr>

                                <tr height="12"></tr>

                                <tr>
                                    <td colspan="3" height="26" valign="middle" background="images/fhbg.gif">
                                        <strong>&nbsp;<img src="images/license.png" width="16" height="16" align="absmiddle"> RECIBO DE ENTREGA DE EFECTIVO</strong>
                                        <input type="hidden" name="usuario" id="usuario" value="<?= $_SESSION['percod'] ?>" />
                                    </td>
                                </tr>
                                <!-- <tr>
                                    <td colspan="4" height="35" align="left" valign="middle">
                                        &nbsp;Los Casilleros con <span class="Estilo100">*</span> son Datos Obligatorios
                                    </td>
                                </tr> -->

                                <!-- NRO DE RECIBO -->
                                <tr>
                                    <td colspan="4" height="35" align="center" valign="middle">
                                        <h3>Recibo Nro G001-00001</h3>
                                    </td>
                                </tr>

                                <!-- TIPO -->
                                <tr>
                                    <td width="115" height="23" valign="middle">
                                        &nbsp;<img src="images/dhtmlgoodies_minus.gif" width="18" height="13" align="absbottom"> Tipo
                                    </td>
                                    <td valign="middle">:
                                        <label><input type="radio" name="rectipo" value="I" checked> Interno</label>&nbsp;&nbsp;
                                        <label><input type="radio" name="rectipo" value="E"> Externo</label>
                                    </td>
                                </tr>

                                <!-- DNI -->
                                <tr>
                                    <td height="23" valign="middle">
                                        &nbsp;<img src="images/dhtmlgoodies_minus.gif" width="18" height="13" align="absbottom"> DNI
                                    </td>
                                    <td valign="middle">:
                                        <input name="dni" id="dni" type="text" size="22">
                                        <button name="boton" onClick="ree_ver_datos_dni()" class="button2" type="button">
                                            <img width="15" height="14" src="images/b_search.png" align="absmiddle">&nbsp;Consultar
                                        </button>
                                        <button name="boton" onClick="ree_crear_nuevo_dni()" class="button2" type="button">
                                            <img width="15" height="14" src="images/add.png" align="absmiddle">&nbsp;Crear
                                        </button>
                                        <span class="Estilo100">*</span>
                                        <div id="nuevo_dni_container" style="margin: 8px 0 0 8px; display: none;">
                                            <input type="text" id="nuevo_dni_input" placeholder="Ingrese el Nombre Completo" size="33" maxlength="60">
                                            <button type="button" class="button2" onclick="guardarNuevoDNI()">
                                                <img width="15" height="14" src="images/btn_guardar.png" align="absmiddle">&nbsp;Guardar
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Receptor -->
                                <tr>
                                    <td width="115" height="23" valign="middle">
                                        &nbsp;<img src="images/dhtmlgoodies_minus.gif" width="18" height="13" align="absbottom"> Beneficiario
                                    </td>
                                    <td valign="middle">:
                                        <input name="idreceptor" type="text" id="idreceptor" hidden disabled>
                                        <input name="receptor" type="text" id="receptor" size="45" maxlength="60" disabled>
                                        <span class="Estilo100">*</span>
                                    </td>
                                </tr>


                                <!-- Monto -->
                                <tr>
                                    <td width="115" height="23" valign="middle">
                                        &nbsp;<img src="images/dhtmlgoodies_minus.gif" width="18" height="13" align="absbottom"> Monto
                                    </td>
                                    <td width="60" valign="middle">:
                                        <select name="cbtipomoneda" id="cbtipomoneda" style="width: 60px;">
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
                                        <input name="monto" type="text" id="monto" size="34" maxlength="60" style="margin-left:2px;">
                                        <span class="Estilo100">*</span>
                                    </td>
                                </tr>

                                <!-- Categoria -->
                                <tr>
                                    <td width="115" height="23" valign="middle">
                                        &nbsp;<img src="images/dhtmlgoodies_minus.gif" width="18" height="13" align="absbottom"> Categoría
                                    </td>
                                    <td width="60" valign="middle">:
                                        <select name="cbcategoria" id="cbcategoria" style="width: 225px;">
                                            <option value="0">Elegir</option>
                                            <?php
                                            $sql_categoria = "SELECT CodCat, Nombre FROM REE_CATEGORIAS WHERE Estado = 'A' ORDER BY Nombre";
                                            $conex_categoria = $_SESSION['dbmssql']->getAll($sql_categoria);
                                            foreach ($conex_categoria as $v => $valor) {
                                                $cod_cat = $valor['CodCat'];
                                                $nom_cat = $valor['Nombre'];
                                                echo "<option value='" . $cod_cat . "'>" . $nom_cat . "</option>";
                                            }
                                            ?>
                                        </select>
                                        <button name="boton" onClick="mostrarNuevaCategoriaInput()" class="button2" type="button" id="btn_crear_categoria">
                                            <img width="15" height="14" src="images/add.png" align="absmiddle">&nbsp;Crear
                                        </button>
                                        <span class="Estilo100">*</span>

                                        <div id="nueva_categoria_container" style="margin: 8px 0 0 8px; display: none;">
                                            <input type="text" id="nueva_categoria_input" placeholder="Ingrese nueva categoría" size="33" maxlength="60">
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
                                    <td width="360" valign="middle">:

                                        <input type="text" id="filtro_concepto_input" placeholder="Filtrar concepto..." size="45" maxlength="60" onkeyup="filtrarConceptos()">
                                        <select name="cbconcepto" id="cbconcepto" style="width: 225px; margin: 8px 0 0 8px;">
                                            <option value="0">Elegir</option>
                                            <?php
                                            $sql_concepto = "SELECT CodCon, Descripcion FROM REE_CONCEPTOS WHERE Estado = 'A' ORDER BY Descripcion";
                                            $conex_concepto = $_SESSION['dbmssql']->getAll($sql_concepto);
                                            foreach ($conex_concepto as $v => $valor) {
                                                $cod_con = $valor['CodCon'];
                                                $nom_con = $valor['Descripcion'];
                                                echo "<option value='" . $cod_con . "'>" . $nom_con . "</option>";
                                            }
                                            ?>
                                        </select>
                                        <button name="boton" onClick="mostrarNuevoConceptoInput()" class="button2" type="button" id="btn_crear_concepto">
                                            <img width="15" height="14" src="images/add.png" align="absmiddle">&nbsp;Crear
                                        </button>
                                        <span class="Estilo100">*</span>

                                        <div id="nuevo_concepto_container" style="margin: 8px 0 0 8px; display: none;">
                                            <input type="text" id="nuevo_concepto_input" placeholder="Ingrese nuevo concepto" size="33" maxlength="60">
                                            <button type="button" class="button2" onclick="guardarNuevoConcepto()">
                                                <img width="15" height="14" src="images/btn_guardar.png" align="absmiddle">&nbsp;Guardar
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Fecha -->
                                <tr>
                                    <td width="115" height="23" valign="middle">
                                        &nbsp;<img src="images/dhtmlgoodies_minus.gif" width="18" height="13" align="absbottom"> Fecha y Hora
                                    </td>
                                    <td width="360" valign="middle">:
                                        <input type="date" name="fecha_recibo" id="fecha_recibo" value="<?= date('Y-m-d') ?>" required>
                                        <input type="time" name="hora_recibo" id="hora_recibo" value="<?= date('H:i:s') ?>" step="2" required>
                                        <span class="Estilo100">*</span>
                                    </td>
                                </tr>

                                <!-- Botones -->
                                <tr>
                                    <td colspan="3" align="center" valign="middle" height="39">
                                        <button name="boton" onClick="new_cliente()" class="button2" type="button">
                                            <img width="15" height="14" src="images/btn_guardar.png" align="absmiddle">&nbsp;Guardar Cliente
                                        </button>
                                        &nbsp;&nbsp;
                                        <button name="boton" onClick="new_cliente()" class="button2" type="button">
                                            <img width="15" height="14" src="images/print.png" align="absmiddle">&nbsp;Imprimir Recibo
                                        </button>
                                        &nbsp;&nbsp;
                                        <button name="boton" onClick="new_cliente()" class="button2" type="button">
                                            <img width="15" height="14" src="images/print.png" align="absmiddle">&nbsp;Nuevo Recibo
                                        </button>
                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>
                </table>

            </td>

            <!-- QUITAR ROWSPAN 100% GRUPO DE CLIENTES -->
            <?php //📌 NOTA: LISTA DE ULTIMOS RECIBOS CREADOS 
            ?>

            <td valign="top">
                <table width="100%" border="0" cellpadding="0" cellspacing="0" class="borderTabla1">
                    <tr>
                        <td width="100%" height="26" valign="middle" background="images/fhbg.gif">
                            <strong>&nbsp;
                                <img src="images/table.png" width="22" height="22" align="absmiddle" />
                                ULTIMOS RECIBOS DE ENTREGA DE EFECTIVO CREADOS
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
                                                <td width="80" height="24" align="center" valign="middle" background="images/bg.gif"><strong>FECHA</strong></td>
                                                <td width="150" height="24" align="center" valign="middle" background="images/bg.gif"><strong>RECEPTOR</strong></td>
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
                            <div id="cliente" style="height:290;width:100%;overflow:auto">
                                <!-- MOSTRAR ULTIMOS RECIBOS DE LA SEMANA, INGRESOS Y SALIDAS -->
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="15" bgcolor="#C0D8E0" height="1"></td>
                    </tr>
                    <tr>
                        <td height="30" valign="top">&nbsp;::
                            <img src="images/template.gif" width="16" height="16"><strong>Ver</strong> &nbsp;&nbsp;
                            <img src="images/d1.gif" width="18" height="14"><strong>Editar</strong>&nbsp;&nbsp;
                            <img src="images/publish_x.png" width="12" height="12"><strong>Cancelar</strong> &nbsp;&nbsp;
                            <img src="images/print.png" width="16" height="16" border="0"><strong>Imprimir</strong>
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
                        <td width="480" height="26" valign="middle" background="images/fhbg.gif">&nbsp;<img src="images/b_search.png" width="16" height="16" /> <strong>B&Uacute;SQUEDA DE RECIBO</strong> </td>
                    </tr>
                    <tr>
                        <td height="34" valign="middle">&nbsp;<img src="images/add.png" width="16" height="16" align="absmiddle" /> Ingresar nombre de Receptor:
                            <input name="bus_clientes" id="bus_clientes" type="text" size="30" onKeyUp="busqueda_cliente()" />
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