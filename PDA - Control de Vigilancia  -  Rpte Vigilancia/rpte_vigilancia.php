<?php

include "head.php";
unset($_SESSION['carroV']);
$bd = "DESARROLLO";
//error_reporting(E_ALL);

if (!isset($_SESSION['logged'])) {
    session_destroy();
    echo '<script>document.location.href="../index.php?e=24";</script>';
    exit;
}

?>
<h3 class="controlTitle"> REPORTE DE VIGILANCIA </h3>
<form action="rpte_vigilancia.php" method="POST" action="" enctype="multipart/form-data" name="stForm-group" class="stForm-group" id="stForm-group">
    <div class="form-group">
        <label><b>BUSCAR X </b><input type="radio" value="1" name="tipo" checked> Dia</label>
        <input type="date" name="fecini" id="fecini" value="<?php echo isset($fecini) ? $fecini : date('Y-m-d'); ?>">
        <div class="buttons-row">
            <button type="submit" id="btn-buscar" name="submit" class="btn2">🔍&nbsp;Buscar</button>
            <!-- <button type="button" id="btn-limpiar" class="btn2" onclick="limpiar_control();">🗑&nbsp;Limpiar</button> -->
        </div>
    </div>
</form>
<div style="clear:both"></div>
<?php

// if (isset($_POST['submit'])) {
// echo '<center> <br>';
// echo $fecini;
if ($fecini === date("Y-m-d") || $fecini == "") {
    $fechareg = date("Y-m-d");
} else {
    $fechareg = $fecini;
}

$query = " SELECT p.CodPL, d.GuiaDespacho, f.Factura, pp.Proforma, count(v1.cod_control)as control_plist, 
        count(v2.cod_control)as control_guia, count(v3.cod_control)as control_factura, 
        count(v4.cod_control)as control_proforma, 
        convert(varchar,p.FechaReg,103)as fecha, CONVERT(varchar(5), p.FechaReg,108)as hora  
        from " . $bd . ".des.PLIST_CAB_TIENDA p 
        left join " . $bd . ".dbo.CABORDSERV s on convert(varchar(255),p.CodPL)=s.cod_hilo_quimico and s.TipOrdServ='N' and 
                s.CodServEmp in(4,9) and convert(date,s.FecReg)='" . $fechareg . "' and s.Estado not in('C') 
        left join " . $bd . ".dbo.CABORDDESP d on s.CodOrdServ=d.CodOrdServ and s.CodServNeg=d.CodDespNeg and 
                convert(date,d.FecReg)='" . $fechareg . "' and d.Estado not in('C') 
        left join " . $bd . ".dbo.CABORDFAC f on s.CodOrdServ=f.CodFacServ and s.CodServNeg=f.CodFacNeg and 
                s.CodServEmp=f.CodFacEmp and convert(date,f.FecReg)='" . $fechareg . "' and f.Estado not in('C') 
        left join " . $bd . ".dbo.CABORDPROF pp on s.CodOrdServ=pp.CodProfServ and s.CodServNeg=pp.CodProfNeg and 
                s.CodServEmp=pp.CodProfEmp and convert(date,pp.FecReg)='" . $fechareg . "' and pp.Estado not in('C') 
        left join " . $bd . ".dbo.control_vigilancia v1 on p.CodPL=v1.numero_doc and v1.tipo_doc='PT' 
        left join " . $bd . ".dbo.control_vigilancia v2 on d.GuiaDespacho=v2.numero_doc and v2.tipo_doc in('GR','GD') 
        left join " . $bd . ".dbo.control_vigilancia v3 on f.Factura=v3.numero_doc and v3.tipo_doc in('FT','BV') 
        left join " . $bd . ".dbo.control_vigilancia v4 on pp.Proforma=v4.numero_doc and v4.tipo_doc='PF' 

        where convert(date, p.FechaReg)=CONVERT(date,'" . $fechareg . "') and p.codemp_origen in(4,9) and p.EstadoGeneral=0 
        group by p.CodPL, d.GuiaDespacho, f.Factura, pp.Proforma, p.FechaReg 
        order by p.FechaReg asc ";

// echo $query;

$contador = 0;
$result = db_query($query);
echo '<center>
        <div id="conte_general">
        <b>DOCUMENTOS PENDIENTES DE VALIDACION</b><br>
        <font class=text>
        <br>
        <table class="texto tableM ">
        <thead><tr>
            <th width="0%"><b>PACKING</b></th>
            <th><b>GUIA REMISION</b></th>
            <th><b>FACTURA-BOLETA</b></th>  
            <th><b>PROFORMA</b></th>
            <th><b>FECHA PACKING</b></th>
            </tr></thead><tbody>';

// $num_rows = db_num_rows($result);
// echo $num_rows;
// echo $result;
while ($rows = db_fetch_array($result)) {
    $contador++;
    $suma = 0;
    $suma = $rows['control_plist'] + $rows['control_guia'] + $rows['control_factura'] + $rows['control_proforma'];
    if ($suma == "0") {
        $nro_proforma   = trim($rows['Proforma']);
        $nro_packing    = trim($rows['CodPL']);
        $nro_guia       = trim($rows['GuiaDespacho']);
        $nro_factura    = trim($rows['Factura']);
        $fecha_pl       = $rows['fecha'];
        $hora_pl        = $rows['hora'];

        if (is_null($nro_packing) || $nro_packing == "") {
            $nro_packing = '&nbsp;';
        }
        if (is_null($nro_guia) || $nro_guia == "") {
            $nro_guia = '&nbsp;';
        }
        if (is_null($nro_factura) || $nro_factura == "") {
            $nro_factura = '&nbsp;';
        }
        if (is_null($nro_proforma) || $nro_proforma == "") {
            $nro_proforma = '&nbsp;';
        }

        $link = "detalle_documento.php?nro_pack=" . urlencode(trim($nro_packing));
        $bg = $color[$contador % 2];
        $codPL = "PL N° " . str_pad($nro_packing, 7, "0", STR_PAD_LEFT);

        echo "<tr class='texto fila-click' bgcolor='$bg' data-href='$link' onmouseover=\"this.style.backgroundColor='#CCFF66'\" onmouseout=\"this.style.backgroundColor=''\">
                    <td data-label='Packing'><b>$codPL</b></td>
                    <td data-label='Guia Remision'>$nro_guia</td>
                    <td data-label='Factura-Boleta'>$nro_factura</td>
                    <td data-label='Proforma'>$nro_proforma</td>
                    <td data-label='Fecha Packing'>$fecha_pl $hora_pl</td>
                </tr>";
    }
}

if ($contador == '0') {
    echo '<tr><td colspan=9 style="font-size:14px; text-align:center;"><b>S I N&nbsp;&nbsp;D A T O S&nbsp;&nbsp;</b></td></tr>';
}
echo '</tbody></table></div>';
// }


?>
<script type="text/javascript">
    //📌NOTA: Abre el Calendario
    fecini.addEventListener('focus', () => {
        fecini.showPicker && fecini.showPicker();
    });

    //📌NOTA: Click en tr
    document.addEventListener('DOMContentLoaded', function() {
        var filas = document.querySelectorAll('.fila-click');
        filas.forEach(function(fila) {
            fila.addEventListener('click', function() {
                var url = this.getAttribute('data-href');
                if (url) {
                    window.location.href = url;
                }
            });
        });
    });
</script>

<hr>
<div class="t3">
</div>
</font>

<?php $xidform = "formbuscar";
include "pie.php" ?>