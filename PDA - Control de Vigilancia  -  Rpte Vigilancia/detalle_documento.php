<?php
include "head.php"; ?>

<?php
$bd = "DESARROLLO";

$nro_pack = isset($_GET['nro_pack']) ? $_GET['nro_pack'] : null;
$codPL = "PL N° " . str_pad($nro_pack, 7, "0", STR_PAD_LEFT);

$queryw = "	select e.EmpRaz, pl.Fecha, c.CliRaz, c.CliRuc, c.CliDir 
from " . $bd . ".des.PLIST_CAB_TIENDA pl 
left join " . $bd . ".dbo.CLIENTE c ON pl.CodCli=c.CliCod and c.CliEst='A'
left join " . $bd . ".dbo.EMPRESA e ON pl.codemp_origen=e.EmpCod and e.EmpEst='A'
where CodPL='" . $nro_pack . "' ";
//echo $queryw;
$resultw = db_query($queryw);
$rowsw = db_fetch_array($resultw);
$fecha_formateada = date('d/m/Y', strtotime($rowsw['Fecha']));

?>
<div class="t8">PACKING LIST <?= $codPL ?></div>

<form name="formbuscar" method="POST" class="stForm-det">
	<div class="plSection">
		<div>
			<label><strong>Fecha:</strong></label><br>
			<input type="text" name="fecha" value="<?= $fecha_formateada ?>" readonly disabled>
		</div>
		<div>
			<label><strong>Empresa:</strong></label><br>
			<input type="text" name="empresa" value="<?= $rowsw['EmpRaz'] ?>" readonly disabled>
		</div>
		<div class="cliente-box">
			<label><strong>Cliente:</strong></label><br>
			<input type="text" name="cliente" value="<?= $rowsw['CliRaz'] ?>" readonly disabled>
		</div>
	</div>
	<div class="buttons">
		<a href="rpte_vigilancia.php" class="btn2">↩️ Volver</a>
	</div>
	<hr>

	<div id="totales-contenedor">
		<span>Total Rollos: <span id="total-rollos">0</span></span>
		<span>Total Kilos: <span id="total-kilos">0</span></span>
	</div>

	<hr>
	<table class="texto tableM no-zebra">
		<thead>
			<tr>
				<th><b>ROLLOS</b></th>
				<th><b>VOUCHER</b></th>
				<th><b>PRODUCTO</b></th>
				<th><b>COLOR</b></th>
				<th><b>KILOS</b></th>
			</tr>
		</thead>
		<tbody>
			<?php
			$color = array("#ffffff", "#F0F0F0");
			$contador = 1;
			$suma = 0;
			$suma_rollos = 0;
			$suma_kg = 0;

			$sql_detalle = " SELECT ROW_NUMBER() OVER( ORDER BY d.CodPL) as Item, d.CodPL, d.voucher, d.descrip, d.descolor, count(d.voucher)as cono, 
		sum(d.kneto) as kneto, ENTERPRISETEXTIL.dbo.CalclaPesoBalSal(d.voucher,d.cdgart,d.cdgcolor) as tkbruto, d.cdgcolor, d.ancho, d.densid, 
		d.cdgart, d.numordped, d.numot, d.orden, d.preciodetalle FROM $bd.des.PLIST_DET_TIENDA d where d.CodPL='$nro_pack' 
		group by d.voucher, d.descrip, d.cdgcolor, d.descolor, d.CodPL, d.ancho, d.densid, d.cdgart, d.numordped, d.numot, d.orden, 
		d.preciodetalle order by d.voucher asc ";
			// echo $sql_detalle;

			$row_det = db_query($sql_detalle);
			while ($val = db_fetch_array($row_det)) {
				$item	 = trim($val['Item']);
				$voucher = trim($val['voucher']);
				$descrip = trim($val['descrip']);
				$descolor = trim($val['descolor']);
				$rollos	 = trim($val['cono']);
				$kneto   = trim($val['kneto']);
				$tkbruto = trim($val['tkbruto']);
				$cdgcolor = trim($val['cdgcolor']);
				$ancho   = trim($val['ancho']);
				$densid  = trim($val['densid']);
				$cdgart  = trim($val['cdgart']);
				$numordped = trim($val['numordped']);
				$numot   = trim($val['numot']);
				$orden   = trim($val['orden']);
				$precio  = trim($val['preciodetalle']);

				$suma_rollos += $rollos;
				$suma_kg += $kneto;

			?>
				<tr class="mainRow">
					<td data-label="ROLLOS:"><b><?= $rollos; ?></b></td>
					<td data-label="VOUCHER:"><b><?= $voucher; ?></b></td>
					<td data-label=" PRODUCTO:"><b><?= $descrip; ?></b></td>
					<td data-label="COLOR:"><b><?= $descolor; ?></b></td>
					<td data-label="KILOS:"><b><?= $kneto; ?></b></td>
				</tr>

				<?php
				$sql_pdet = " SELECT ROW_NUMBER() OVER( ORDER BY voucher)as indice ,kneto, voucher 
			from $bd.des.PLIST_DET_TIENDA where codpl='$nro_pack' and voucher='$voucher' 
			and cdgart='$cdgart' and cdgcolor='$cdgcolor' order by voucher ";
				// echo "<br> $sql_pdet";

				$dsl_pdet = db_fetch_all($sql_pdet);
				foreach ($dsl_pdet as $m => $val) {
				?>
					<tr>
						<td colspan="3"></td>
						<td data-label="Item:" class="align-right"><?= $val['indice']; ?></>
						</td>
						<td data-label="Peso:" class="align-right"><?= $val['kneto']; ?></>
						</td>
					</tr>
				<?php
				} ?>
			<?php
			} ?>
		</tbody>
	</table>
	<input type="hidden" id="total_rollos" value="<?php echo number_format($suma_rollos, 2); ?>">
	<input type="hidden" id="total_kg" value="<?php echo number_format($suma_kg, 2) . " KG"; ?>">
</form>

<script>
	document.addEventListener("DOMContentLoaded", function() {
		const totalRollos = document.getElementById("total_rollos").value;
		const totalKg = document.getElementById("total_kg").value;

		document.getElementById("total-rollos").textContent = totalRollos;
		document.getElementById("total-kilos").textContent = totalKg;
	});
</script>

<?php $xidform = "formbuscar";
include "pie.php" ?>