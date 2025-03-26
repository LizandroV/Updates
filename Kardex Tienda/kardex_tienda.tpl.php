<?php
session_start();
?>
<style type="text/css">
<!--
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
      <strong>KARDEX DE PRODUCTOS - TIENDA</strong></td>
    </tr>
    </table></td>
  <td>&nbsp;</td>
  </tr>
  <tr>
    <td height="13"></td>
  </tr>
  <tr>
    <td height="59"></td>
    <td valign="top"><fieldset>
    <legend>Control de Kardex de Productos - Tienda</legend>

    
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
      
    <tr>
    <td max-width="120" height="28" valign="middle" align="right">&nbsp;
          <img src="images/external.png" width="10" height="10" /> EMPRESA</td>
        <td height="28" valign="middle">:
          <select name="valEmpresa" id="valEmpresa" class="smalltext" style="width: 150px">
            <option value="0">ELEGIR</option>
            <?
              $sql_emp="select  EmpCod, EmpRaz from Empresa 
                where EmpEst='A' and EmpCod in(4,9) 
                order by EmpRaz asc";
              $dsl_emp=$_SESSION['dbmssql']->getAll($sql_emp);
              foreach($dsl_emp as $v => $valemp){ 
                echo "<option value='".trim($valemp['EmpCod'])."'>".trim($valemp['EmpRaz'])."</option>";
              }
            ?>
          </select>
        </td>

          <!-- Agregar Producto -->
          <td width="150" height="28" valign="middle" align="right">&nbsp;<img src="images/external.png" width="10" height="10" /> PRODUCTO</td>
          <td height="28" valign="middle" width="210">:&nbsp;&nbsp;<input type="text" name="valProducto" id="valProducto" /></td>
        <!-- FIN -->
      </tr>
      <tr>

      <td max-width="120" height="28" valign="middle" align="right">&nbsp;
          <img src="images/external.png" width="10" height="10" /> ALMACEN</td>
        <td height="28" valign="middle" width="170" >:
          <select name="valAlmacen" id="valAlmacen" class="smalltext" style="width: 150px">
            <option value="0">ELEGIR</option>
            <?
              $sql_almacen="select CodAlmacen, Almacen from im.almacen 
                  where CodAlmacen in(10016,10017) 
                  order by 1";
              $dsl_almacen=$_SESSION['dbmssql']->getAll($sql_almacen);
              foreach ($dsl_almacen as $ktip => $alm) {
                $codalm   = $alm['CodAlmacen'];
                $almacen  = $alm['Almacen'];

                echo "<option value='".$codalm."'>".$almacen."</option>";
              }
            ?>
          </select>
          </td>
          <td width="150" height="28" valign="middle" align="right">&nbsp;<img src="images/external.png" width="10" height="10" /> PARTIDA</td>
          <td height="28" valign="middle" width="210">:&nbsp;&nbsp;<input type="text" name="valPartida" id="valPartida" /></td>
          <td width="170" height="28" valign="middle" colspan="5"></td>
      </tr>
      
      <tr>
        <td width="120" height="28" valign="middle"  align="right">&nbsp;<img src="images/external.png" width="10" height="10" /> FECHA&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td colspan="3" width="60" height="28" valign="middle">
          DE: <input type="date" name="fecha_ini" id="fecha_ini" placeholder="dd-mm-yyyy"  />&nbsp;&nbsp;
          A: &nbsp;&nbsp;<input type="date" name="fecha_fin" id="fecha_fin" placeholder="dd-mm-yyyy"  />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

          <button name="btn_buscar" type="button" id="btn_buscar" onClick="buscar_kardex_tienda()" 
            class="button2"><img src="images/document.png" width="16" height="16" align="absmiddle">&nbsp;Buscar</button>
          <button name="btn_excel" type="button" id="btn_excel" onClick="kardex_productos_tienda_excel()" 
            class="button2"><img src="images/excel1.png" width="16" height="16" align="absmiddle">&nbsp;Exportar a Excel</button></td>
		  </tr>
      <tr>
        <td height="25"></td>
      </tr>	
    </table>
    <div id="mensajeResp"></div>
    </fieldset></td>
    <td></td>
  </tr>
  <tr>
    <td height="62"></td>
    <td valign="top">
  <div id="lista_kardex_tienda">
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
