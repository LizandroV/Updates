<?php
error_reporting(E_ALL);
include "head.php";
unset($_SESSION['carroV']);

$_SESSION['igvnum1_'] = $igvnum1;

if (!isset($_SESSION['logged'])) {
    session_destroy();
    echo '<script>document.location.href="../index.php?e=24";</script>';
    exit;
}

?>
<h3 class="controlTitle"> SALIDA DE TIENDA </h3>
<form name="stForm-group" id="stForm-group" action="" method="POST" class="stForm-group">
    <div class="stForm">
        <label for="formBuscar"><b>Nro de Salida ST N°:</b></label>
        <input type="text" name="formBuscar" id="formBuscar" value="<?php echo isset($formBuscar) ? $formBuscar : ''; ?>" placeholder="Ingresar texto...">
    </div>

    <div class="stForm">
        <label for="fecini">Fecha Inicio</label>
        <input type="date" name="fecini" id="fecini" value="<?php echo isset($fecini) ? $fecini : date('Y-m-d'); ?>">
    </div>

    <div class="stForm">
        <label for="fecfin">Fecha Fin</label>
        <input type="date" name="fecfin" id="fecfin" value="<?php echo isset($fecfin) ? $fecfin : date('Y-m-d'); ?>">
    </div>

    <div class="buttons">
        <button type="submit" id="btn-buscar" class="btn2">🔍&nbsp;Buscar</button>
        <button type="button" id="btn-nuevo" class="btn2">📄&nbsp;Nuevo</button>
    </div>
</form>

<?php

// echo "B: $formBuscar";
// echo "<br> FI: $fecini";
// echo "<br>FF: $fecfin";

// $busc = $_POST['formBuscar'];
// $feci = $_POST['fecini'];
// $fecf = $_POST['fecfin'];
// echo "<br>$busc - $feci - $fecf <br>";

if ($formBuscar <> "" and $fecini == "" and $fecfin == "") {
    $query = "set dateformat ymd select st.codsal_tienda, ao.Almacen as aorigen, eo.EmpRaz as eorigen, 
                ad.Almacen as adestino, ed.EmpRaz as edestino, st.total_rollos, st.total_pesokg, st.fechareg 
                from $bd.alm.cab_salidas_tienda st
                left join $bd.im.ALMACEN ao on st.codalmacen_origen = ao.CodAlmacen
                left join $bd.im.ALMACEN ad on st.codalmacen_destino = ad.CodAlmacen
                left join $bd.dbo.EMPRESA eo on st.codemp_origen = eo.EmpCod
                left join $bd.dbo.EMPRESA ed on st.codemp_destino = ed.EmpCod
                left join $bd.alm.motivo_traslado m on st.cod_traslado = m.codtraslado
                where st.estado not in ('C')
                and st.codsal_tienda = '$formBuscar' 
                order by codsal_tienda desc";
} elseif ($formBuscar <> "" and $fecini <> "" and $fecfin <> "") {
    $query = "set dateformat ymd select st.codsal_tienda, ao.Almacen as aorigen, eo.EmpRaz as eorigen, 
                ad.Almacen as adestino, ed.EmpRaz as edestino, st.total_rollos, st.total_pesokg, st.fechareg 
                from $bd.alm.cab_salidas_tienda st
                left join $bd.im.ALMACEN ao on st.codalmacen_origen = ao.CodAlmacen
                left join $bd.im.ALMACEN ad on st.codalmacen_destino = ad.CodAlmacen
                left join $bd.dbo.EMPRESA eo on st.codemp_origen = eo.EmpCod
                left join $bd.dbo.EMPRESA ed on st.codemp_destino = ed.EmpCod
                left join $bd.alm.motivo_traslado m on st.cod_traslado = m.codtraslado
                where st.estado not in ('C') and convert(date,st.fechareg) >= '$fecini' and convert(date,st.fechareg) <= '$fechahoy'
                and st.codsal_tienda = '$formBuscar' 
                order by codsal_tienda desc";
} elseif ($formBuscar == "" and $fecini <> "" and $fecfin <> "") {
    $query = "set dateformat ymd select st.codsal_tienda, ao.Almacen as aorigen, eo.EmpRaz as eorigen,
                ad.Almacen as adestino, ed.EmpRaz as edestino, st.total_rollos, st.total_pesokg, st.fechareg 
                from $bd.alm.cab_salidas_tienda st
                left join $bd.im.ALMACEN ao on st.codalmacen_origen = ao.CodAlmacen
                left join $bd.im.ALMACEN ad on st.codalmacen_destino = ad.CodAlmacen
                left join $bd.dbo.EMPRESA eo on st.codemp_origen = eo.EmpCod
                left join $bd.dbo.EMPRESA ed on st.codemp_destino = ed.EmpCod
                left join $bd.alm.motivo_traslado m on st.cod_traslado = m.codtraslado
                where st.estado not in ('C') and convert(date,st.fechareg) >= '$fecini' and convert(date,st.fechareg) <= '$fechahoy' 
                order by codsal_tienda desc";
} else {
    $query = "set dateformat ymd select st.codsal_tienda, ao.Almacen as aorigen, eo.EmpRaz as eorigen,
                ad.Almacen as adestino, ed.EmpRaz as edestino, st.total_rollos, st.total_pesokg, st.fechareg 
                from $bd.alm.cab_salidas_tienda st
                left join $bd.im.ALMACEN ao on st.codalmacen_origen = ao.CodAlmacen
                left join $bd.im.ALMACEN ad on st.codalmacen_destino = ad.CodAlmacen
                left join $bd.dbo.EMPRESA eo on st.codemp_origen = eo.EmpCod
                left join $bd.dbo.EMPRESA ed on st.codemp_destino = ed.EmpCod
                left join $bd.alm.motivo_traslado m on st.cod_traslado = m.codtraslado
                where st.estado not in ('C') and convert(date,st.fechareg) >= '$fechahoy' and convert(date,st.fechareg) <= '$fechahoy' 
                order by codsal_tienda desc";
}
// echo "$query <br> ";
$contador = 0;
$result = db_query($query);
//echo "$result <br> ";
echo '<br><b>LISTADO DE SALIDAS DE TIENDA</b><br>
<font class=text>
<br> <table class="texto tableM"><thead><tr>
    <th width="0%"><b>Nro ST</b></th>
    <th><b>Alm Origen</b></th>    
    <th><b>Emp Origen</b></th>
    <th><b>Alm Destino</b></th>
    <th><b>Emp Destino</b></th>
    <th><b>Rollos</b></th>
    <th><b>Peso KG</b></th>
    <th><b>Fecha</b></th>
</tr></thead><tbody>';

$num_rows = db_num_rows($result);

while ($rows = db_fetch_array($result)) {
    $contador++;
    $link = "salida_tienda_ver.php?idsal=" . $rows['codsal_tienda'];
    $bg = $color[$contador % 2];
    $codST = "ST " . str_pad($rows['codsal_tienda'], 7, "0", STR_PAD_LEFT);


    echo "<tr class='texto fila-click' bgcolor='$bg' data-href='$link' onmouseover=\"this.style.backgroundColor='#CCFF66'\" onmouseout=\"this.style.backgroundColor=''\">
        <td data-label='Nro ST'><b>{$codST}</b></td>
        <td data-label='Alm Origen'>{$rows['aorigen']}</td>
        <td data-label='Emp Origen'>{$rows['eorigen']}</td>
        <td data-label='Alm Destino'>{$rows['adestino']}</td>
        <td data-label='Emp Destino'>{$rows['edestino']}</td>
        <td data-label='Rollos'>{$rows['total_rollos']}</td>
        <td data-label='Peso KG'>{$rows['total_pesokg']}</td>
        <td data-label='Fecha'>{$rows['fechareg']->format('d/m/Y')}</td>
    </tr>";
}

echo '</tbody></table>';

?>

<script>
    //📌NOTA: Abre el Calendario
    fecini.addEventListener('focus', () => {
        fecini.showPicker && fecini.showPicker();
    });

    fecfin.addEventListener('focus', () => {
        fecfin.showPicker && fecfin.showPicker();
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
    // 📌 NOTA: Crear nuevo
    document.getElementById("btn-nuevo").addEventListener("click", function() {
        window.location.href = "salida_tienda_crear.php";
    });
</script>


<?php $xidform = "formbuscar";
echo "<br><br>";
include "pie.php" ?>