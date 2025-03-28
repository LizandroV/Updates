<?php 
include "head.php";
session_start();
// error_reporting(E_ALL);
unset($_SESSION['carroV']);
$bd="DESARROLLO";
$_SESSION['igvnum1_'] = $igvnum1;

if (!isset($_SESSION['logged'])){
    session_destroy();
    echo '<script>document.location.href="../index.php?e=24";</script>';
    exit;
}

//SI SE VALIDA EL DOCUMENTO
if(isset($_POST['enviado']) && (!empty($_SESSION['nro_doc'])) && strlen($_FILES['img_adjunta']['name'])>0)
{
    //$prueba=$_FILES['img_adjunta']['tmp_name'];
    $docimagen=$_FILES['img_adjunta']['name'];
    $tipodoc= trim($_SESSION['tipo_doc']);
    $nrodoc= trim($_SESSION['nro_doc']);
    $cod_notificacion= trim($_SESSION['get_not']);
    
    date_default_timezone_set('America/Lima');
    $fechahora = date("d-m-Y H:i:s");
    $codusu=trim($_SESSION['micodigo']);
    $nomusu=trim($_SESSION['minombre']);
    
    $sql_control="insert into ".$bd.".dbo.control_vigilancia values('$tipodoc', '$nrodoc', '$fechahora', '$nrodoc$docimagen', '$codusu', '$nomusu')";
    db_query($sql_control);

    //ELIMINAR NOTIFICACIONES RELACIONADAS
    $sql_delete = "EXEC dbo.sp_EliminarNotificacion_Relacion ?, ?";
    $result = db_exec_sp($sql_delete, array($tipodoc, $nrodoc));

    $_SESSION['validar']='1';

    move_uploaded_file($_FILES['img_adjunta']['tmp_name'],'../sistema/doc_control_vigilancia/'.$nrodoc.$docimagen);
    unset($_POST['enviado']);
    unset($_SESSION['tipo_doc']);
    unset($_SESSION['nro_doc']);
    unset($_SESSION['get_not']);

}else{
    unset($_SESSION['validar']);
}

?>
<h3 class="controlTitle"> CONTROL DE VIGILANCIA </h3>
<form action="control_vigilancia.php" method="POST" enctype="multipart/form-data" name="formbuscar" id="formbuscar">
        <div class="form-group">
            <label><b>BUSCAR X </b></label>
            <input type="radio" value="1" name="tipo" checked>𝄃𝄂𝄂𝄀𝄁𝄃𝄂𝄂𝄃
            <input type="text" name="cotiza" id="cotiza" placeholder="Escaneé el código...">
            <button type="submit" id="btn-buscar" class="btn2" hidden>🔍 Buscar</button>
            <button type="button" id="btn-limpiar" class="btn2" onclick="limpiar_control();">🗑 Limpiar</button>
        </div>

<!--</form>-->
<?php

echo '<br>';

// Si hay datos en en INPUT "cotiza"
if (!empty($_POST['cotiza'])) {
    $cotiza = trim($_POST['cotiza']);
    $get_not = 0;
} else {
    // Si viene desde la notificación
    $get_doc = trim($_POST['tipo_doc']);
    $get_num = trim($_POST['num_doc']);
    $get_ord = trim($_POST['cod_ord']);
    $get_not = trim($_POST['cod_not']);
    //echo "$get_doc $get_num $get_ord $get_not";

    if ($get_ord == 0) {
        $cotiza = "$get_doc|$get_num";
    } else {
        $cotiza = "$get_doc|$get_num|$get_ord";
    }

    // Marcar la notificación como leída
    if (!empty($get_not)) {
        $sql_notView="update {$bd}.dbo.notificacion_vigilancia set estado = 1 where cod_notificacion = $get_not";
        db_query($sql_notView);
    }
}

//echo "BUSCAR: $cotiza";

if($cotiza !== "")
{
    $partes     = explode("|",trim($cotiza));
    $tipo_doc   = trim($partes[0]);//TIPO DOCUMENTO
    $nro_doc    = trim($partes[1]);//NUMERO DOCUMENTO
    $codordserv = trim($partes[2]);//CODORDSERV
    
    //echo "<br>";
    //echo "$tipo_doc $nro_doc $codordserv";

    switch($tipo_doc){
        case 'PT': $query="select a.CodPL, convert(varchar(10), a.FechaReg,103)as FechaReg, a.Total as TotalKg, b.CliRaz, b.CliRaz, emp.EmpRaz, 
                    count(dt.codpldeta)as cantidad, 
                    (select count(cv.cod_control) from ".$bd.".dbo.control_vigilancia cv 
                        where cv.numero_doc=a.CodPL and cv.tipo_doc='PT')as control_vigila 
                    from ".$bd.".des.PLIST_CAB_TIENDA a 
                    left join ".$bd.".dbo.CLIENTE b on a.CodCli=b.CliCod
                    left join ".$bd.".dbo.EMPRESA emp on a.codemp_origen=emp.EmpCod and emp.EmpEst='A' 
                    left join ".$bd.".des.PLIST_DET_TIENDA dt on a.CodPL=dt.CodPL 
                    where a.CodPL='".$nro_doc."' and a.EstadoGeneral=0 
                    group by a.CodPL, a.FechaReg, a.Total, b.CliRaz, emp.EmpRaz ";
                    $documento="PACKING LIST TIENDA";
                    $medida_cant="ROLLOS";
                    $medida_kg="KG";
                    break;
        case 'GR': $query="select d.GuiaDespacho, convert(varchar(10), d.FecReg,103)as FechaReg, e.EmpRaz, c.CliRaz,
                    SUM(dd.cantidadrecep)as TotalKg, med.MedAbrev as medida, SUM(dd.CantRef)as cantidad, 
                    (select count(cv.cod_control) from ".$bd.".dbo.control_vigilancia cv 
                        where cv.numero_doc=d.GuiaDespacho and cv.tipo_doc='GR')as control_vigila   
                    from ".$bd.".dbo.CABORDDESP d 
                    left join ".$bd.".dbo.CABORDSERV s on d.CodOrdServ=s.CodOrdServ and d.CodDespNeg=s.CodServNeg
                    left join ".$bd.".dbo.EMPRESA e on s.CodServEmp=e.EmpCod
                    left join ".$bd.".dbo.CLIENTE c on s.CodServCli=c.CliCod
                    left join ".$bd.".dbo.DETORDDESP dd on d.CodOrdDesp=dd.CodOrdDesp and d.CodDespNeg=dd.CodDespNeg and d.CodOrdServ=dd.CodOrdServ
                    left join ".$bd.".dbo.MEDIDA med on dd.Medida=med.MedCod 
                    where d.GuiaDespacho='".$nro_doc."' and d.Estado not in('C')
                    group by d.GuiaDespacho, d.FecReg, e.EmpRaz, c.CliRaz, med.MedAbrev ";
                    $documento="GUIA REMITENTE";
                    break;
        case 'FT': $query="select f.Factura, convert(varchar(10),f.FecReg,103)as FechaReg, e.EmpRaz, c.CliRaz, 
                    IIF(f.TipMoneda='S','S/','$')as moneda, f.MontTotal, sum(df.CantRecep)as TotalKg, md.MedAbrev as medida,
                    (select count(cv.cod_control) from ".$bd.".dbo.control_vigilancia cv 
                        where cv.numero_doc=f.Factura and cv.tipo_doc='FT')as control_vigila,
                        
                    (select SUM(dd.CantRef) from ".$bd.".dbo.CABORDDESP d
                        left join ".$bd.".dbo.CABORDSERV s on d.CodOrdServ=s.CodOrdServ and d.CodDespNeg=s.CodServNeg
                        left join ".$bd.".dbo.DETORDDESP dd on d.CodOrdDesp=dd.CodOrdDesp and d.CodDespNeg=dd.CodDespNeg and d.CodOrdServ=dd.CodOrdServ 
                        where d.CodOrdServ=f.CodFacServ and d.CodDespNeg=f.CodFacNeg and d.Estado not in('C'))as cantidad 

                    from ".$bd.".dbo.CABORDFAC f 
                    left join ".$bd.".dbo.CLIENTE c on f.CodFacCli=c.CliCod and c.CliEst='A'
                    left join ".$bd.".dbo.EMPRESA e on f.CodFacEmp=e.EmpCod and e.EmpEst='A'
                    left join ".$bd.".dbo.DETORDFAC df on f.CodOrdFac=df.CodOrdFac and f.CodFacNeg=df.CodFacNeg and f.CodFacServ=df.CodFacServ and df.Estado not in('C')
                    left join ".$bd.".dbo.MEDIDA md on df.Medida=md.MedCod  
                    where f.Factura='".$nro_doc."' and f.Estado not in('C') 
                    group by f.Factura, f.FecReg, e.EmpRaz, c.CliRaz, f.TipMoneda, f.MontTotal, md.MedAbrev, f.CodFacServ, f.CodFacNeg";
                    $documento="FACTURA";
                    break;
        case 'BV': $query="select f.Factura, convert(varchar(10),f.FecReg,103)as FechaReg, e.EmpRaz, c.CliRaz, 
                    IIF(f.TipMoneda='S','S/','$')as moneda, f.MontTotal, sum(df.CantRecep)as TotalKg, md.MedAbrev as medida, 
                    (select count(cv.cod_control) from ".$bd.".dbo.control_vigilancia cv 
                        where cv.numero_doc=f.Factura and cv.tipo_doc='BV')as control_vigila,

                    (select SUM(dd.CantRef) from ".$bd.".dbo.CABORDDESP d 
                        left join ".$bd.".dbo.CABORDSERV s on d.CodOrdServ=s.CodOrdServ and d.CodDespNeg=s.CodServNeg
                        left join ".$bd.".dbo.DETORDDESP dd on d.CodOrdDesp=dd.CodOrdDesp and d.CodDespNeg=dd.CodDespNeg and d.CodOrdServ=dd.CodOrdServ 
                        where d.CodOrdServ=f.CodFacServ and d.CodDespNeg=f.CodFacNeg and d.Estado not in('C'))as cantidad     
                    
                    from ".$bd.".dbo.CABORDFAC f 
                    left join ".$bd.".dbo.CLIENTE c on f.CodFacCli=c.CliCod and c.CliEst='A'
                    left join ".$bd.".dbo.EMPRESA e on f.CodFacEmp=e.EmpCod and e.EmpEst='A'
                    left join ".$bd.".dbo.DETORDFAC df on f.CodOrdFac=df.CodOrdFac and f.CodFacNeg=df.CodFacNeg and f.CodFacServ=df.CodFacServ and df.Estado not in('C')
                    left join ".$bd.".dbo.MEDIDA md on df.Medida=md.MedCod 
                    where f.Factura='".$nro_doc."' and f.Estado not in('C') 
                    group by f.Factura, f.FecReg, e.EmpRaz, c.CliRaz, f.TipMoneda, f.MontTotal, md.MedAbrev, f.CodFacServ, f.CodFacNeg";
                    $documento="BOLETA";
                    break;
        case 'PF': $query="select p.Proforma, convert(varchar(10),p.FecReg,103)as FechaReg, e.EmpRaz, c.CliRaz, 
                    IIF(p.TipMoneda='S','S/','$')as moneda, p.MontTotal, sum(dP.CantRecep)as TotalKg, md.MedAbrev as medida, 
                    (select count(cv.cod_control) from ".$bd.".dbo.control_vigilancia cv 
                        where cv.numero_doc=p.Proforma and cv.tipo_doc='PF')as control_vigila 
                    from ".$bd.".dbo.CABORDPROF p 
                    left join ".$bd.".dbo.CLIENTE c on p.CodProfCli=c.CliCod and c.CliEst='A' 
                    left join ".$bd.".dbo.EMPRESA e on p.CodProfEmp=e.EmpCod and e.EmpEst='A' 
                    left join ".$bd.".dbo.DETORDPROF dp on p.CodOrdProf=dp.CodOrdProf and p.CodprofNeg=dp.CodProfNeg and p.CodProfServ=dp.CodProfServ and dp.Estado not in('C') 
                    left join ".$bd.".dbo.MEDIDA md on dp.Medida=md.MedCod 
                    where p.Proforma='".$nro_doc."' and p.CodProfServ='".$codordserv."' and p.Estado not in('C') 
                    group by p.Proforma, P.FecReg, e.EmpRaz, c.CliRaz, p.TipMoneda, p.MontTotal, md.MedAbrev ";
                    $documento="PROFORMA";
                    break;
        case 'GD': $query="select d.GuiaDespacho, convert(varchar(10), d.FecReg,103)as FechaReg, e.EmpRaz, c.CliRaz,
                    SUM(dd.cantidadrecep)as TotalKg, med.MedAbrev as medida, SUM(dd.CantRef)as cantidad, 
                    (select count(cv.cod_control) from ".$bd.".dbo.control_vigilancia cv 
                        where cv.numero_doc=d.GuiaDespacho and cv.tipo_doc='GD')as control_vigila  
                    from ".$bd.".dbo.CABORDDESP d 
                    left join ".$bd.".dbo.CABORDSERV s on d.CodOrdServ=s.CodOrdServ and d.CodDespNeg=s.CodServNeg
                    left join ".$bd.".dbo.EMPRESA e on s.CodServEmp=e.EmpCod
                    left join ".$bd.".dbo.CLIENTE c on s.CodServCli=c.CliCod
                    left join ".$bd.".dbo.DETORDDESP dd on d.CodOrdDesp=dd.CodOrdDesp and d.CodDespNeg=dd.CodDespNeg and d.CodOrdServ=dd.CodOrdServ
                    left join ".$bd.".dbo.MEDIDA med on dd.Medida=med.MedCod 
                    where d.GuiaDespacho='".$nro_doc."' and d.CodOrdServ='".$codordserv."' and d.Estado not in('C')
                    group by d.GuiaDespacho, d.FecReg, e.EmpRaz, c.CliRaz, med.MedAbrev ";
                    $documento="GUIA DESPACHO";
                    break;
        case 'PL': $query="select a.CodPL, convert(varchar(10), a.FechaReg,103)as FechaReg, a.Total as TotalKg, b.CliRaz, b.CliRaz, 
                    '&nbsp;' as EmpRaz, count(dt.codpldeta)as cantidad, 
                     (select count(cv.cod_control) from ".$bd.".dbo.control_vigilancia cv 
                        where cv.numero_doc=a.CodPL and cv.tipo_doc='PL')as control_vigila 
                    from ".$bd.".des.PLIST_CAB a 
                    left join ".$bd.".dbo.CLIENTE b on a.CodCli=b.CliCod 
                    left join ".$bd.".des.PLIST_DET dt on a.CodPL=dt.CodPL  
                    where a.CodPL='".$nro_doc."' and a.EstadoGeneral=0 
                    group by a.CodPL, a.FechaReg, a.Total, b.CliRaz ";
                    $documento="PACKING LIST";
                    $medida_cant="ROLLOS";
                    $medida_kg="KG";
                    break;
        case 'NC': $query="select f.NotaCredito, convert(varchar(10),f.FecReg,103)as FechaReg, e.EmpRaz, c.CliRaz, 
                    f.MontTotal, sum(df.Cantidad)as TotalKg, md.MedAbrev as medida, '&nbsp;' as moneda,
                    (select count(cv.cod_control) from ".$bd.".dbo.control_vigilancia cv 
                    where cv.numero_doc=f.NotaCredito and cv.tipo_doc='NC')as control_vigila
                    from ".$bd.".dbo.CABNOTACREDITO f 
                    left join ".$bd.".dbo.CLIENTE c on f.CodNotaCli=c.CliCod and c.CliEst='A' 
                    left join ".$bd.".dbo.EMPRESA e on f.CodNotaEmp=e.EmpCod and e.EmpEst='A' 
                    left join ".$bd.".dbo.DETNOTACREDITO df on f.CodOrdNotaCre=df.CodOrdNotaCre and f.CodNotaNeg=df.CodNotaNeg and df.Estado not in('C') 
                    left join ".$bd.".dbo.MEDIDA md on df.MedCod=md.MedCod 
                    where f.NotaCredito='".$nro_doc."' and f.CodNotaNeg='".$codordserv."' and f.Estado not in('C') 
                    group by f.NotaCredito, f.FecReg, e.EmpRaz, c.CliRaz, f.MontTotal, md.MedAbrev";
                    $documento="NOTA DE CREDITO";
                    break;
    }

//echo $query;
}
$contador=0;
//echo "<br>";
//echo $query;
$result = db_query($query);

echo '<div id="conte_general">';
//$num_rows = db_num_rows($result);
while($rows = db_fetch_array($result))
{
    //CABECERAS
    echo '<b>LISTADO GENERAL</b><br>
    <font class=text>
    <br>
    <table class="texto tableM ">
    <thead><tr>
        <th width="0%"><b>Documento</b></th>
        <th><b>Numero</b></th>
        <th><b>Fecha</b></th>  
        <th><b>Empresa</b></th>
        <th><b>Cliente</b></th>';

    if($tipo_doc=='FT' || $tipo_doc=='BV' || $tipo_doc=='PT' || $tipo_doc=='GR' || $tipo_doc=='GD' || $tipo_doc=='PL'){echo '<th><b>Cantidad</b></th>';};
    if($tipo_doc=='FT' || $tipo_doc=='BV' || $tipo_doc=='PF' || $tipo_doc=='NC'){echo '<th><b>Monto</b></th>';};

    echo '<th><b>Total</b></th>
        <th><b>Adjuntar</b></th>
        <th width="17%"></th>
    </tr></thead><tbody>';

    //AGREGAR RESULTADOS
    if($tipo_doc=='PT' || $tipo_doc=='PL'){$nro_docu=htmlentities('P.L. N° ').(string)str_pad(trim($rows['CodPL']),7,'0',STR_PAD_LEFT);};
    if($tipo_doc=='GR' || $tipo_doc=='GD'){$nro_docu=trim($rows['GuiaDespacho']); $medida_cant='&nbsp;'; $medida_kg=trim($rows['medida']);};
    if($tipo_doc=='FT' || $tipo_doc=='BV'){$nro_docu=trim($rows['Factura']); $medida_cant='&nbsp;'; $medida_kg=trim($rows['medida']); $moneda=trim($rows['moneda']);};
    if($tipo_doc=='PF'){$nro_docu=trim($rows['Proforma']); $medida_cant='&nbsp;'; $medida_kg=trim($rows['medida']); $moneda=trim($rows['moneda']);};
    if($tipo_doc=='NC'){$nro_docu=trim($rows['NotaCredito']); $medida_cant='&nbsp;'; $medida_kg=trim($rows['medida']); $moneda=trim($rows['moneda']);};

    $_SESSION['tipo_doc']=trim($tipo_doc);
    $_SESSION['nro_doc']=trim($nro_doc);
    $_SESSION['get_not']=trim($get_not);

    $contador++;
    echo "<tr class=texto style='background:#CCFF66' onmouseover=style.backgroundColor='#CCFF66'>";
    echo "<td align=left data-label='Documento'><b>".$documento."</b></td>";
    echo "<td data-label='Numero'>".$nro_docu."</td>";
    echo "<td data-label='Fecha'>".$rows['FechaReg']."</td>";
    echo "<td data-label='Empresa'>".$rows['EmpRaz']."</td>";
    echo "<td data-label='Cliente'>".$rows['CliRaz']."</td>";
    
    if($tipo_doc=='FT' || $tipo_doc=='BV' || $tipo_doc=='PT' || $tipo_doc=='GR' || $tipo_doc=='GD' || $tipo_doc=='PL'){echo "<td data-label='Cantidad'>".$rows['cantidad']." ".$medida_cant."</td>";};
    if($tipo_doc=='FT' || $tipo_doc=='BV' || $tipo_doc=='PF' || $tipo_doc=='NC'){echo "<td data-label='Monto'>".$moneda." ".number_format($rows['MontTotal'],2)."</td>";};

    echo "<td data-label='Total'>".$rows['TotalKg']." ".$medida_kg."</td>";
    echo "<td data-label='Adjuntar' height=40 align=center><input type='file' id='img_adjunta' name='img_adjunta' style='width: 210px; height=auto;' accept='image/*' capture='camera'></td>";
    echo "<td height=50 style='text-align:center; vertical-align: middle' valign=middle>";

    if($rows['control_vigila']=='0')
    {
        echo '<button type="submit" name="enviado" id="enviado" style="width: 80%" class="botonNuevo"><i class="icon-search"></i>VALIDAR</button>&nbsp;';
    }else{
        echo '<p id="label_v" name="label_v" valign="middle"><b>DOCUMENTO VALIDADO</b></p>';
    }
    
    echo '</td></tr>';
}

if($contador=='0' && $cotiza!=="" && isset($_POST['submit'])) 
{
    echo '<tr><td colspan=9 style=font-size:14px><b>S I N&nbsp;&nbsp;D A T O S&nbsp;&nbsp;S O B R E&nbsp;&nbsp;E L&nbsp;&nbsp;D O C U M E N T O</b></td></tr>';
}

echo '</tbody></table></div>';
//echo "<script type='text/javascript'>document.getElementById('cotiza').value=''; document.getElementById('cotiza').focus();</script>";


//INGRESA AQUI CUANDO SE VALIDA
if($_SESSION['validar']=='1')
{
    echo "<p id='label_ok' name='label_ok' valign='middle' style='background-color:#CCFF66; color: #000000; height: 40px;'><b>DOCUMENTO VALIDADO</b></p>";
    echo "<script type='text/javascript'>setTimeout(function(){document.getElementById('conte_general').innerHTML=''; document.getElementById('label_ok').removeAttribute('style'); document.getElementById('label_ok').innerHTML='';}, 3000);</script>";
    unset($_SESSION['validar']);
}
?>
<script type="text/javascript">

    function limpiar_control()
    {
        document.getElementById("cotiza").value="";
        document.getElementById("conte_general").innerHTML="";
        document.getElementById("cotiza").focus();
    }

    document.getElementById("cotiza").focus();
    
    // VALIDAR IMAGEN
    document.addEventListener("DOMContentLoaded", function () {
    let validarBtn = document.getElementById("enviado");
    
    if (validarBtn) {
        validarBtn.addEventListener("click", function (event) {
            let fileImage = document.getElementById('img_adjunta');

            if (fileImage && fileImage.files.length === 0) {
                alert("Por favor, adjuntar foto antes de validar.");
                event.preventDefault();
            }
        });
    }
    });

</script>

<hr>
<div class="t3">
</div>
</font>
</form>	
<?php $xidform = "formbuscar";
include "pie.php";
?>