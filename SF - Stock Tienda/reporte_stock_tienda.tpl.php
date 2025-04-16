<?php
session_start();
?>
<style type="text/css">

#Layer1 {
  position:absolute;
  width:150px;
  height:21px;
  z-index:1;
  left: 344px;
  top: 333px;
}
.note_content 
{
  clear:both;
  padding:10px 0 0;
  width:860px;
  word-wrap:break-word;
  text-align: center ;
  direction:ltr;
  display:block;
}

</style>

<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="<?=$GLOBALS[color][12]?>">
  <tr>
    <td width="10" height="13"></td>
    <td width="982"></td>
    <td width="10"></td>
  </tr>
  <tr>
    <td height="24">&nbsp;</td>
    <td valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="borderTabla1">
    <tr>
      <td width="987" height="27" valign="middle" background="images/ft.gif"> &nbsp;<img src="images/blockdevice.png" width="22" height="22" align="absmiddle"> 
      <strong>REPORTE DE STOCK TIENDA</strong></td>
    </tr>
    </table></td>
  <td>&nbsp;</td>
  </tr>
  <tr>
    <td height="13"></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td height="59"></td>
    <td valign="top"><fieldset>
    <legend>Control de Stock Tienda</legend>
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="100" height="28" valign="middle"  align="right">&nbsp;<img src="images/external.png" width="10" height="10" /> ALMACEN</td>
        <td height="28" valign="middle" width="100">: 
          <select name="almacen" class="smalltext" id="almacen" style="width:175px;">
          <option value="0">ELEGIR</option>
            <? 
              $sql_alm="select CodAlmacen, Almacen from im.ALMACEN 
                where CodAlmacen in(10016,10017) 
                order by Almacen asc ";
              $dsl_alm=$_SESSION['dbmssql']->getAll($sql_alm);  
              foreach($dsl_alm as $v => $alm)
              { echo "<option value='".trim($alm['CodAlmacen'])."'>".trim($alm['Almacen'])."</option>";  }          
            ?>
          </select>
        </td>
        <!-- Agregar Producto -->
        <td height="28" valign="middle" width="100" align="right">&nbsp;<img src="images/external.png" width="10" height="10" /> PRODUCTO</td>
        <td height="28" valign="middle" width="170">: <input type="text" name="producto" id="producto" /></td>
        <!-- FIN -->
        <td height="28" valign="middle" width="100" align="right">&nbsp;<img src="images/external.png" width="10" height="10" /> COLOR</td>
        <td height="28" valign="middle">:&nbsp;&nbsp; <input type="text" name="txt_contenedor" id="txt_contenedor" /></td>
      </tr>

      <tr>
        <td width="100" height="28" valign="middle"  align="right">&nbsp;<img src="images/external.png" width="10" height="10" /> EMPRESA</td>
        <td height="28" valign="middle" width="100">: 
          <select name="entidad" class="smalltext" id="entidad" style="width:175px;">
            <option value="0">ELEGIR</option>
            <? 
              $sql_emp="select  EmpCod, EmpRaz from Empresa 
                where EmpEst='A' and EmpCod in(4,9) 
                order by EmpRaz asc ";
              $dsl_emp=$_SESSION['dbmssql']->getAll($sql_emp); 
              foreach($dsl_emp as $v => $valemp)
              { echo "<option value='".trim($valemp['EmpCod'])."'>".trim($valemp['EmpRaz'])."</option>";  }         
            ?>
          </select>
        </td>
        <!-- Mover Partida -->
        <td height="28" valign="middle" width="100"  align="right">&nbsp;<img src="images/external.png" width="10" height="10" /> PARTIDA</td>
        <td height="28" valign="middle" width="170">: <input type="text" name="partida" id="partida" /></td><!-- CAMBIO DE txt_prod -->
        <!-- FIN -->
        <td height="28" valign="middle" width="100"  align="right"></td>
        <td colspan="3" width="60" height="28" valign="middle"></td>
        <td height="28" valign="middle" width="100"></td>
        <td height="28" valign="middle" width="200"><input type="hidden" name="txt_lote" id="txt_lote" /></td>
		  </tr>

      <tr>
          <td width="100" height="10" valign="middle"></td>
      </tr> 

      <tr>
          <td width="100" height="28" valign="middle"></td>
          <td height="28" valign="middle" width="200"></td>
          <td height="28" valign="middle" width="100">
            <button name="btn_buscar" type="button" id="btn_buscar" onClick="buscar_reporte_stock_tienda()" 
            class="button2"><img src="images/document.png" width="16" height="16" align="absmiddle">&nbsp;Buscar</button>
          </td>
          <td height="28" valign="middle">
            <button name="btn_excel" type="button" id="btn_excel" onClick="stock_tienda_excel()" 
            class="button2"><img src="images/excel1.png" width="16" height="16" align="absmiddle">&nbsp;Exportar a Excel</button>
          </td>
  		</tr>	

    </table>
    <div id="mensajeResp"></div>
    </fieldset></td>
    <td></td>
  </tr>
  <tr>
    <td height="62"></td>
    <td valign="top">
  <div id="lista_stock_tienda">
	</div>

  </td>
  <td></td>
  </tr>
  <tr>
    <td height="15"></td>
    <td></td>
    <td></td>
  </tr>
</table>

<input name="usuario" type="hidden" id="usuario" value="<?=$_SESSION['percod']?>"/>
