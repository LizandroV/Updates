<?php
session_start();
include('funciones/funciones.php');
?>

<body onLoad="llamadaCodNotaCredito_prof();">

  <form name="form1" method="post" action="" target="_blank">
    <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="<?= $GLOBALS[color][12] ?>">
      <tr>
        <td width="2%" valign="top">&nbsp;</td>
        <td width="96%" align="right" valign="top">
          <?php
          $sql = "	select	count(*) as conteo
			from	DETORDFAC_ASOCIADAS a
			inner join NEGOCIO n on n.NegCod = a.CodFacNeg
			where	estado ='F' and isnull(SaldoParaNota,0) > 0 and EstadoNota = 1	";
          $dsl_datos = $_SESSION['dbmssql']->getAll($sql);
          foreach ($dsl_datos as $val => $value) {
            $conteo      = trim($value['conteo']);
          }

          if ($conteo > 0) {
          ?>
            <img src="images/almacen2.png" alt="" width="16" height="16" align="absmiddle"><a onClick="ver_notas_pendientes()" href="#" style=" cursor:pointer; color:#66C">NOTAS PENDIENTES</a>
          <?php } ?>

        </td>
        <td width="2%" valign="top">&nbsp;</td>
      </tr>
      <tr>
        <td height="436">&nbsp;</td>
        <td valign="top">

          <table width="100%" cellpadding="0" cellspacing="0" border="0" class="borderTabla1" align="center">
            <tr background="images/bg_topbar.gif">
              <td width="920" height="23" colspan="2" background="images/bg_topbar.gif">&nbsp;
                <img src="images/n_nota_de_credito.png" alt="" width="16" height="16" align="absmiddle">
                <strong> NOTA DE CREDITO DE PROFORMA</strong>
                <strong><span id="nota_credito_prof">N.C. N&ordm;</span></strong>
                <input name="usuario" type="hidden" id="usuario" value="<?= $_SESSION['percod'] ?>">
                <input name="CodOrdComp" type="hidden" id="CodOrdComp" value="">
                <input name="CodObra" type="hidden" id="CodObra" value="">
                <input name="vorden" type="hidden" id="vorden" value="">
                <input name="nivelUsu" type="hidden" id="nivelUsu" value="<?= $_SESSION['nivcod'] ?>">
                <input name="txt_total_factura" type="hidden" id="txt_total_factura" value="">
              </td>
            </tr>
            <tr>
              <td height="5" colspan="2"></td>
            </tr>
            <tr valign="middle">
              <td height="11">
                <table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="<?= $GLOBALS[color][18] ?>">
                  <tr>
                    <td valign="middle" height="23">&nbsp;</td>
                    <td valign="middle" height="23"><img src="images/dhtmlgoodies_minus.gif" alt="" width="18" height="13"> Negocio</td>
                    <td height="23" valign="middle">: <label>
                        <select name="codNegocio" class="smalltext" id="codNegocio" onChange="pinta_obra_codif_nc_prof(this.value)">
                          <?php
                          $sql_listarobra = " execute BF_Lista_Negocio_Logeo '1','1'";
                          $conex_listarobra = $_SESSION['dbmssql']->getAll($sql_listarobra);
                          echo '<option value="0">ELEGIR</option>';
                          foreach ($conex_listarobra as $v => $value) {
                            echo "<option value='" . rtrim($value['negcod']) . "'>" . rtrim($value['negdes']) . "</option>";
                          }  ?>
                        </select>
                      </label>
                    </td>
                    <td height="23" valign="middle"><img src="images/dhtmlgoodies_minus.gif" alt="" width="18" height="13"> Motivo Emisión</td>
                    <td height="23" valign="middle">: <span>
                        <?php
                        $sql_entidad = "	select	MotCod, codigoSunat+' '+ MotNom MotNom
							from	motivo
							where	estado=1 and nota='credito'
							order by MotNom asc";
                        $conex_entidad = $_SESSION['dbmssql']->getAll($sql_entidad);
                        echo "<select name=\"cod_motivo\" class=\"smalltext\" id=\"cod_motivo\" style=\"width:150px;\" >";
                        echo '<option value="0"></option>';
                        foreach ($conex_entidad as $v => $valueEnt) {
                          echo "<option value='" . trim($valueEnt['MotCod']) . "'>" . trim($valueEnt['MotNom']) . "</option>";
                        }
                        echo "</select>";
                        ?>
                      </span>
                    </td>
                  </tr>
                  <tr>
                    <td valign="middle" height="23">&nbsp;</td>
                    <td valign="middle" height="23"><img src="images/dhtmlgoodies_minus.gif" alt="" width="18" height="13"> Orden Proforma</td>
                    <td height="23" valign="middle">:&nbsp;<input name="order_factura" onKeyUp="numeros(this.id)" style="text-align:right;" type="text" id="order_factura" size="30"></td>
                    <td height="23" valign="middle"><img src="images/dhtmlgoodies_minus.gif" alt="" width="18" height="13"> Nota Física</td>
                    <td height="23" valign="middle">:&nbsp;<input name="numero_nota" style="text-align:right;" type="text" id="numero_nota" size="13">&nbsp;<span style="color:#F00; font-size:9px;">N.C. Física. Ej.: 005-00158571 | N.C. Elec. Ej. F002-3</span></td>
                  </tr>
                  <tr>
                    <td valign="middle" width="15" height="23">&nbsp;</td>
                    <td valign="middle" width="146" height="23"><img src="images/dhtmlgoodies_minus.gif" alt="" width="18" height="13"> Empresa </td>
                    <td height="23" valign="middle">:&nbsp;<span id="espacio_empresa">Ingrese Ord. Proforma.</span></td>
                    <td height="23" valign="middle"><img src="images/dhtmlgoodies_minus.gif" alt="" width="18" height="13"> Cliente </td>
                    <td height="23" valign="middle">:&nbsp;<span id="espacio_cliente">Ingrese Ord. Proforma.</span></td>
                  </tr>
                  <tr>
                    <td height="23">&nbsp;</td>
                    <td height="23"><img src="images/dhtmlgoodies_minus.gif" alt="" width="18" height="13"> Fecha Emisión</td>
                    <td width="301">: <input type="date" name="txtInicio" id="txtInicio" placeholder="dd-mm-yyyy" /></td>
                    <td width="143"><img src="images/dhtmlgoodies_minus.gif" alt="" width="18" height="13"> Proforma. y Fecha </td>
                    <td width="413">:&nbsp;<span id="espacio_factura_fisica">Ingrese Ord. Proforma.</span></td>
                  </tr>

                  <!--inicio tipo de almacen -->
                  <tr>
                    <td valign="middle" width="15" height="23">&nbsp;</td>
                    <td valign="middle" width="146" height="23">
                      <img src="images/dhtmlgoodies_minus.gif" alt="" width="18" height="13"> Tipo de Almacen
                    </td>
                    <td>:
                      <select name="cbtipo_almacen" id="cbtipo_almacen" class="smalltext" style="width: 205px;">
                        <option value="0">ELEGIR</option>
                        <?php
                        $sql_tipoa = " select codtipoalmacen, nomtipo from alm.tipoalmacen where codtipoalmacen not in('11') order by 1 ";
                        $dsql_tipoa = $_SESSION['dbmssql']->getAll($sql_tipoa);
                        foreach ($dsql_tipoa as $k_tp => $val) {
                          echo "<option value='" . trim($val['codtipoalmacen']) . "'>" . trim($val['nomtipo']) . "</option>";
                        }
                        ?>
                      </select>
                    </td>
                  </tr>
                  <!--fin tipo de almacen -->

                  <tr>
                    <td height="5" colspan="5"><!--&nbsp; --></td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>

          <table width="100%" cellpadding="0" cellspacing="0" border="0" align="center">
            <tr>
              <td width="100%" height="10" align="center">
                <div id="validar_SI_ORD_PAGO"></div>
              </td>
            </tr>
            <tr>
              <td height="20" align="center">
                <table width="100%" cellpadding="0" cellspacing="0" border="0" class="borderTabla1" align="center" bgcolor="<?= $GLOBALS[color][18] ?>">
                  <tr>
                    <td height="8" colspan="2"><!--&nbsp; --></td>
                  </tr>
                  <tr>
                    <td align="center" height="15">
                      <button name="boton" id="bus_ingreso" onClick="buscar_datos_orden_proforma()" class="button2" type="button"><img width="15" height="16" src="images/b_view.png" align="absmiddle">&nbsp;Buscar</button>&nbsp;&nbsp;
                      <!-- <button name="boton" id="model_nc" disabled onClick="print_nc_web()" class="button2" type="button"><img width="15" height="16" src="images/text.gif" align="absmiddle">&nbsp;Fisico</button>&nbsp;&nbsp; -->
                      <button name="btn_ncE" id="btn_ncE" onClick="imprimir_ncE()" class="button2" type="button"><img width="15" height="16" src="images/text.gif" align="absmiddle">&nbsp;Imprimir</button>&nbsp;&nbsp;
                      <button name="boton" id="nuevo" onClick="nuevo_documento_nc_prof()" class="button2" type="button"><img width="15" height="15" src="images/new_docu.png" align="absmiddle">&nbsp;Nuevo Documento</button>&nbsp;&nbsp;
                      <button name="boton" id="item" onClick="insertar_fila_detalle_nota_credito_prof()" class="button2" type="button"><img width="15" height="15" src="images/new_filas.png" align="absmiddle">&nbsp;Nuevo Item</button>&nbsp;&nbsp;
                      <button name="boton" id="guardar" onClick="registrar_nota_credito_prof()" class="button2" type="button"><img width="15" height="15" src="images/btn_guardar.png" align="absmiddle">&nbsp;Guardar Documento</button>&nbsp;&nbsp;
                      <button name="boton" disabled id="eliorden" onClick="eliminar_nota_nc_prof()" class="button2" type="button"><img width="15" height="15" src="images/del_orden.png" align="absmiddle">&nbsp;Eliminar Nota</button>
                    </td>
                  </tr>
                  <tr>
                    <td height="8"><!--&nbsp; --></td>
                  </tr>
                </table>
              </td>
            </tr>
            <tr>
              <td height="8" align="center"><!--&nbsp; --><span style="color:#F00; font-size:9px;">Evitar las comas y comillas simples en el campo Descripci&oacute;n</span></td>
            </tr>
          </table>
          <!-- Detalle de ingresos -->
          <table width="100%" cellpadding="0" cellspacing="0" border="0" class="borderTabla1" align="center">
            <tr width=100%>
              <td>
                <table width="100%" border="0" cellpadding="0" cellspacing="1" class="borderTabla">
                  <tr>
                    <td width="88" height="19" align="center" valign="middle" background="images/bg.gif"><strong class="Estilo2">N&ordm;</strong></td>
                    <td width="208" align="center" valign="middle" background="images/bg.gif"><strong>C A N T.</strong></td>
                    <td width="667" align="center" valign="middle" background="images/bg.gif"><strong>D E S C R I P C I O N</strong></td>
                    <td width="169" align="center" valign="middle" background="images/bg.gif"><strong>P. U N I T A R I O</strong></td>
                    <td width="168" align="center" valign="middle" background="images/bg.gif"><strong>I M P O R T E</strong></td>
                  </tr>
                </table>
                <div style="width:100%; overflow:auto; height:120px;" id="detalle_orden_nota_credito">
                  <div style="width:100%;" id="divreg0"></div>
                </div>
                <div id="PRUEBA"></div>
              </td>
            </tr>
          </table>
          <!-- fin de detalle de ingresos-->
          <table width="100%" cellpadding="0" cellspacing="0" border="0" align="center">
            <tr>
              <td width="100%" height="10" align="center"><!--&nbsp; --></td>
            </tr>
          </table>
          <!-- Detalle de ingresos    -->
          <table width="100%" cellpadding="0" cellspacing="0" border="0" class="borderTabla1" align="center">
            <tr width="100%">
              <td>
                <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="<?= $GLOBALS[color][18] ?>">
                  <tr>
                    <td width="20" height="22" align="left" valign="middle">&nbsp;</td>
                    <td width="338" valign="middle"><img src="images/dhtmlgoodies_minus.gif" alt="" width="18" height="13"> Comentarios:</td>
                    <td width="164" valign="middle">&nbsp;</td>
                    <td width="54" valign="middle">&nbsp;</td>
                    <td width="142" align="center" valign="middle"></td>
                    <td colspan="2" align="right" valign="middle">SubTotal </td>
                    <td width="158" align="center" valign="middle">:
                      <input class="smalltext" name="txt_subtotal" type="text" id="txt_subtotal" style="text-align:right;" value="0" size="10" readonly>
                    </td>
                  </tr>
                  <tr>
                    <td height="22" align="left" valign="middle">&nbsp;</td>
                    <td colspan="2" rowspan="3" valign="middle"><textarea class="smalltext" onKeyUp="agrega_comilla_factura(event)" name="txt_comen_fac" rows="4" id="txt_comen_fac" style="width:80%;">*</textarea></td>
                    <td valign="middle">&nbsp;</td>
                    <td align="center" valign="middle">
                      <!--<input name="txt_parametro_igv" type="text" id="txt_parametro_igv" value="0.18" size="4">-->
                    </td>
                    <td align="right" valign="middle"><strong>Monto Total</strong></td>
                    <td align="center" valign="middle"><strong>
                        <div style="width:22px;" id="simbolo_moneda_factura"> </div>
                      </strong></td>
                    <td align="center" valign="middle">:
                      <input name="txt_total" type="text" id="txt_total" style="text-align:right;" value="0" size="10" class="smalltext" readonly>
                    </td>

                  </tr>
                  <tr>
                    <td height="22" align="left" valign="middle">&nbsp;</td>
                    <td valign="middle">&nbsp;</td>
                    <td align="center" valign="middle">T.C. de la Proforma:</td>
                    <td align="right" valign="middle"></td>
                    <td align="right" valign="middle"></td>
                    <td align="center" valign="middle"></td>
                  </tr>
                  <tr>
                    <td height="22" align="left" valign="middle">&nbsp;</td>
                    <td valign="middle">&nbsp;</td>
                    <td align="center" valign="middle"><input name="txt_parametro_cambio" type="text" id="txt_parametro_cambio" value="" size="4" readonly></td>
                    <td align="right" valign="middle">Monto al Cambio</td>
                    <td width="20" align="center" valign="middle">
                      <div style="width:22px;" id="simbolo_moneda"> </div>
                    </td>
                    <td align="center" valign="middle">:
                      <label>
                        <input type="text" name="monto_al_cambio" style="text-align:right;" readonly class="smalltext" size="10" id="monto_al_cambio">
                      </label>
                    </td>
                  </tr>
                  <tr>
                    <td height="24" align="left" valign="middle"><input name="moneda_factura" type="hidden" id="moneda_factura" value=""></td>
                    <td valign="middle">&nbsp;</td>
                    <td valign="middle">&nbsp;</td>
                    <td valign="middle"><label></label></td>
                    <td align="center" valign="middle"></td>
                    <td align="right" valign="middle"></td>
                    <td align="right" valign="middle"></td>
                    <td align="center" valign="middle"></td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
          <!-- fin de pie de ingresos-->
          <!-- saparado -->
          <table width="100%" cellpadding="0" cellspacing="0" border="0" align="center">
            <tr>
              <td width="100%" height="10" align="center"><!--&nbsp; --></td>
            </tr>
          </table>
          <!-- fin de separador align="center"-->
          <!-- contenedor de busqueda -->
          <table width="100%" cellpadding="0" cellspacing="0" border="0" class="borderTabla1" align="center">
            <tr width="100%">
              <td>
                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                  <tr>
                    <td background="images/fhbg.gif" height="26" colspan="7">&nbsp;<img src="images/b_search.png" alt="" width="14" height="16" align="absmiddle"><strong> B&Uacute;SQUEDA DE NOTAS DE CREDITO POR NEGOCIO</strong></td>
                  </tr>
                  <tr bgcolor="<?= $GLOBALS[color][18] ?>">
                    <td width="164" height="23"><img src="images/dhtmlgoodies_minus.gif" alt="" width="18" height="13">Negocio</td>
                    <td colspan="3" valign="middle">: <label>
                        <select class="smalltext" name="cmbcodobra" id="cmbcodobra" onChange="">
                          <?php
                          $sql_listarobra = " execute BF_Lista_Negocio_Logeo '1','1'";
                          $conex_listarobra = $_SESSION['dbmssql']->getAll($sql_listarobra);
                          echo '<option value="0">ELEGIR</option>';
                          foreach ($conex_listarobra as $v => $value) {
                            $cod = rtrim($value['negcod']);
                            $desc = rtrim($value['negdes']);
                            echo "<option value='" . $cod . "'>" . $desc . "</option>";
                          }
                          ?>
                        </select>
                      </label>&nbsp;&nbsp;<button name="boton" id="car" onClick="buscar_ordenes_nc_prof()" class="button2" type="button">Cargar</button></td>
                    <td width="162" rowspan="3" valign="middle"><button name="boton" id="verorden" disabled onClick="ver_orden_nc_prof()" class="button2" type="button"><img width="15" height="16" src="images/template.gif" align="absmiddle">&nbsp;Ver Orden</button></td>
                    <td width="234" rowspan="3" valign="middle"><button name="boton" id="editorden" disabled onClick="editar_orden_nc_prof()" class="button2" type="button"><img width="15" height="16" src="images/img/b_edit.png" align="absmiddle">&nbsp;Editar Orden</button></td>
                  </tr>
                  <tr bgcolor="<?= $GLOBALS[color][18] ?>">
                    <td height="23"><img src="images/dhtmlgoodies_minus.gif" alt="" width="18" height="13"> A&ntilde;o</td>
                    <td width="102" valign="middle">: <label>
                        <select name="cmbus_anio" id="cmbus_anio" onChange="">
                          <?php
                          $sql_verANIO = "select distinct YEAR(fecreg) aaaa from dbo.CABORDSERV where Estado not in('C','E','F') order by 1 desc";
                          $dsl_verANIO = $_SESSION['dbmssql']->getAll($sql_verANIO);
                          foreach ($dsl_verANIO as $v => $anio) {
                            $cod_anio = $anio['aaaa'];
                            if ($cod_anio == $anio_actual) {
                              $sel = 'selected';
                            } else {
                              $sel = '';
                            }
                            echo "<option " . $sel . " value='" . $cod_anio . "'>" . $cod_anio . "</option>";
                          }
                          ?>
                        </select>
                      </label></td>
                    <td width="68" valign="middle"><img src="images/dhtmlgoodies_minus.gif" alt="" width="18" height="13"> Mes:</td>
                    <td width="288" valign="middle">: <label><select name="cmbus_mes" id="cmbus_mes" onChange="">
                          <?php
                          for ($cod_mes = 1; $cod_mes <= 12; $cod_mes++) {
                            if ($cod_mes == $mes_actual) {
                              $sel = 'selected';
                            } else {
                              $sel = '';
                            }
                            echo "<option " . $sel . " value='" . $cod_mes . "'>" . sacar_mes($cod_mes) . "</option>";
                          }
                          ?></select></label></td>
                  </tr>
                  <tr bgcolor="<?= $GLOBALS[color][18] ?>">
                    <td height="23"><img src="images/dhtmlgoodies_minus.gif" alt="" width="18" height="13"> Código Nota Crédito</td>
                    <td colspan="3" valign="middle">: <label>
                        <select name="cmbordenes" id="cmbordenes" onChange="activar_verorden_nc_prof(this.value)" style="width:200px;">
                          <option value="0"></option>
                        </select>
                      </label></td>
                  </tr>
                  <tr bgcolor="<?= $GLOBALS[color][18] ?>">
                    <td height="23">&nbsp;</td>
                    <td colspan="3" valign="middle">&nbsp;</td>
                    <td valign="middle">&nbsp;</td>
                    <td valign="middle">&nbsp;</td>
                  </tr>
                </table>

              </td>
            </tr>
          </table>
          <!-- fin de contenedor de busqueda -->
          <br>
        </td>
        <td valign="top">&nbsp;</td>
      </tr>
    </table>
  </form>
</body>