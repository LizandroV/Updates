<?php
include "head.php";
$id = $_GET['idsal'];

$query = "SELECT st.*, ao.Almacen as aorigen, eo.EmpRaz as eorigen, ad.Almacen as adestino, ed.EmpRaz as edestino, m.descrip
            FROM $bd.alm.cab_salidas_tienda st
            LEFT JOIN $bd.im.ALMACEN ao ON st.codalmacen_origen = ao.CodAlmacen
            LEFT JOIN $bd.im.ALMACEN ad ON st.codalmacen_destino = ad.CodAlmacen
            LEFT JOIN $bd.dbo.EMPRESA eo ON st.codemp_origen = eo.EmpCod
            LEFT JOIN $bd.dbo.EMPRESA ed ON st.codemp_destino = ed.EmpCod
            LEFT JOIN $bd.alm.motivo_traslado m ON st.cod_traslado = m.codtraslado
            WHERE st.codsal_tienda = $id";

//echo $query;
$row = db_fetch_array(db_query($query));
$codST = "ST " . str_pad($row['codsal_tienda'], 7, "0", STR_PAD_LEFT);
?>

<h4>Salida de Tienda N° <?= $codST ?></h4>

<form method="POST" action="" class="stForm-det">
    <input type="hidden" name="codsal_tienda" value="<?= $row['codsal_tienda'] ?>">

    <div class="stHead">
        <div>
            <label><strong>Motivo:</strong></label><br>
            <span><?= $row['descrip'] ?></span>
        </div>
        <div>
            <label><strong>Fecha:</strong></label><br>
            <span><?= $row['fechareg']->format('d/m/Y') ?></span>
        </div>
    </div>

    <div class="stTransfer">
        <div class="stSection">
            <h5>📦 Origen</h5>
            <div class="stFila">
                <div class="stAlmacen">
                    <label>Almacén</label>
                    <input type="text" name="aorigen" value="<?= $row['aorigen'] ?>" readonly disabled>
                </div>
                <div class="stAlmacen">
                    <label> Empresa</label>
                    <input type="text" name="eorigen" value="<?= $row['eorigen'] ?>" readonly disabled>
                </div>
            </div>
        </div>

        <div class="stSection">
            <h5>🏢 Destino</h5>
            <div class="stFila">
                <div class="stAlmacen">
                    <label> Almacén</label>
                    <input type="text" name="adestino" value="<?= $row['adestino'] ?>" readonly disabled>
                </div>
                <div class="stAlmacen">
                    <label>Empresa</label>
                    <input type="text" name="edestino" value="<?= $row['edestino'] ?>" readonly disabled>
                </div>
            </div>
        </div>
    </div>
    <div class="buttons">
        <a href="salida_tienda.php" class="btn2">↩️ Volver</a>
    </div>
</form>
<h4>Detalle de Rollos</h4>
<table class="texto tableM">
    <thead>
        <tr>
            <th>Item</th>
            <th>Cod Prod</th>
            <th>Cod Barra</th>
            <th>Partida</th>
            <th>Producto</th>
            <th>Proceso</th>
            <th>Color</th>
            <th>Cant Rollos</th>
            <th>Peso KG</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $contador = 0;
        $qdet = "SELECT cdgart, correl, voucher, descrip, proceso, descolor, cant_rollos_salida, kneto_salida 
                    FROM $bd.alm.det_salidas_tienda WHERE codsal_tienda = $id";
        $resdet = db_query($qdet);
        while ($det = db_fetch_array($resdet)) {
            $contador++;
            $suma_rollos += (float) trim($det['cant_rollos_salida']);
            $suma_kilos  += (float) trim($det['kneto_salida']);
            $total_kilos = number_format((float)$suma_kilos, 2, '.', '');

            // echo "<br> $suma_rollos - $suma_kilos <br>";
            echo "<tr>
                        <td data-label='Item'>{$contador}</td>
                        <td data-label='Cod Prod'>{$det['cdgart']}</td>
                        <td data-label='Cod Barra'>{$det['correl']}</td>
                        <td data-label='Partida'>{$det['voucher']}</td>
                        <td data-label='Producto'>{$det['descrip']}</td>
                        <td data-label='Proceso'>{$det['proceso']}</td>
                        <td data-label='Color'>{$det['descolor']}</td>
                        <td data-label='Cant Rollos'>{$det['cant_rollos_salida']}</td>
                        <td data-label='Peso KG'>{$det['kneto_salida']}</td>
                    </tr>";
        }
        echo "<br><br>
            <tr>
                <td id='totalTbl' colspan='7' style='text-align: right; font-size: 12px; font-weight: bold;' class='total'>Total</td>
                <td data-label='Total Rollos' class='total' align='center' valign='middle' style='font-size: 12px; font-weight: bold;'>$suma_rollos Rollos</td>
                <td data-label='Total KG' class='total' align='center' valign='middle' style='font-size: 12px; font-weight: bold;'>$total_kilos KG</td>
            </tr>";
        ?>
    </tbody>
</table>

<!-- <td><a href='eliminar_detalle.php?id={$det['coddet_salida']}&idsal=$id'>❌</a></td> -->
<!-- <a href="agregar_detalle.php?idsal=<?= $id ?>" class="btn2">➕ Agregar Rollo</a> -->
<?php $xidform = "formbuscar";
echo "<br><br>";
include "pie.php" ?>