<?php
session_start();
extract($_REQUEST);
$afectoIGV = 'Si';
$descuentaPORCENTAJE = "Si";
error_reporting(0);
$bd = "DESARROLLO";
?>
<!DOCTYPE html>
<html class="no-js" lang="es">
<?php
if (!isset($_SESSION['logged'])) {
	session_destroy();
	echo '<script>document.location.href="../index.php?e=24";</script>';
	exit;
}
date_default_timezone_set('America/Bogota');
include "config.php";
include "funcion.php";
db_query("SET NAMES 'UTF8'");
$usuario = $_SESSION['logged'];
$niveles = $_SESSION['soportes'];
$sucursa = $_SESSION['sucursa'];
$fechahoy = date("Y-m-d");
$fecha_registro = date("Y-m-d H:i:s");
$afectoIGV = "Si";
date_default_timezone_set('America/Bogota');
$dia = date("l");
if ($dia == "Monday") $dia = "Lunes";
if ($dia == "Tuesday") $dia = "Martes";
if ($dia == "Wednesday") $dia = "Miercoles";
if ($dia == "Thursday") $dia = "Jueves";
if ($dia == "Friday") $dia = "Viernes";
if ($dia == "Saturday") $dia = "Sabado";
if ($dia == "Sunday") $dia = "Domingo";
// Obtenemos el nmero del da
$dia2 = date("d");
// Obtenemos y traducimos el nombre del mes
$mes = date("F");
if ($mes == "January") $mes = "Enero";
if ($mes == "February") $mes = "Febrero";
if ($mes == "March") $mes = "Marzo";
if ($mes == "April") $mes = "Abril";
if ($mes == "May") $mes = "Mayo";
if ($mes == "June") $mes = "Junio";
if ($mes == "July") $mes = "Julio";
if ($mes == "August") $mes = "Agosto";
if ($mes == "September") $mes = "Setiembre";
if ($mes == "October") $mes = "Octubre";
if ($mes == "November") $mes = "Noviembre";
if ($mes == "December") $mes = "Diciembre";

// Obtenemos el ao
$ano = date("Y");

// Imprimimos la fecha completa
$fechadehoy = "$dia $dia2 de $mes de $ano";
$fechawh = time();
$fechahoy = date("Y-m-d", $fechawh);
$Horahoy = date("H:i", $fechawh);
$diahoy = date("d", $fechawh);
$meshoy = date("m", $fechawh);
$anohoy = date("Y", $fechawh);

$usuario = $_SESSION['logged'];
$niveles = $_SESSION['soportes'];
$sucursa = $_SESSION['sucursa'];
?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>IDEAS WEB - ERP</title>
	<link rel="stylesheet" href="../css/ideasweb.css">
	<link rel="stylesheet" href="../css/normalize.css">
	<link rel="stylesheet" href="../css/sweetalert2.css">
	<link rel="stylesheet" href="../css/material.min.css">
	<link rel="stylesheet" href="../css/material-design-iconic-font.min.css">
	<link rel="stylesheet" href="../css/animate.min.css">
	<link rel="stylesheet" href="../css/jquery.mCustomScrollbar.css">
	<link rel="stylesheet" href="../css/main.css">
	<script src="../js/jquery-1.11.2.min.js"></script>
	<script src="../js/material.min.js"></script>
	<script src="../js/sweetalert2.min.js"></script>
	<script src="../js/jquery.mCustomScrollbar.concat.min.js"></script>
	<script src="../js/main.js"></script>
	<script src="../js/jquery.autoheight.js"></script>
	<script src="../js/jquery.min.js"></script>
	<script src="../js/jquery-ui.js"></script>
	<link rel="stylesheet" href="../css/jquery-ui.css">
</head>

<body>
	<?php

	if (($_SESSION['soportes'] == "Administrador") or ($_SESSION['soportes'] == "Cobranza")) {
		$xADMINvende = " and s.sucursal='$sucursa' ";
	}

	putenv("TZ=Etc/GMT+5");
	$fechahoy = date("Y-m-d", time());

	?>
	<?php
	$acT = "";
	?>

	<!-- Notifications area -->
	<section class="full-width container-notifications">
		<div class="full-width container-notifications-bg btn-Notification"></div>
		<section class="NotificationArea">
			<div class="full-width text-center NotificationArea-title tittles">
				Notificaciones<i class="zmdi zmdi-close btn-Notification"></i>
			</div>

			<!-- NOTIFICACIONES -->
			<div id="dv_notifica">
				<!-- Area de Notificaciones -->
			</div>
			<!-- NOTIFICACIONES FIN -->
		</section>
	</section>
	<!-- Notifications area End -->

	<!-- navBar -->
	<div class="full-width navBar">
		<div class="full-width navBar-options">
			<i class="zmdi zmdi-more-vert btn-menu" id="btn-menu"></i>
			<div class="mdl-tooltip" for="btn-menu">Menu</div>
			<nav class="navBar-options-list">
				<ul class="list-unstyle">
					<li class="btn-Notification" id="notifications">
						<i class="zmdi zmdi-notifications"></i>
						<span id="contador-notificaciones">0</span> <!-- contador -->
						<div class="mdl-tooltip" for="notifications">Notificaciones</div>
					</li>

					<li class="btn-exit" id="btn-exit">
						<i class="zmdi zmdi-power"></i>
						<div class="mdl-tooltip" for="btn-exit">Salir</div>
					</li>
				</ul>
			</nav>
		</div>
	</div>
	<!-- navBar end -->

	<!-- navLateral -->
	<section class="full-width navLateral">
		<div class="full-width navLateral-bg btn-menu"></div>
		<div class="full-width navLateral-body">
			<div class="full-width navLateral-body-logo text-center tittles">
				<i class="zmdi zmdi-close btn-menu"></i> IDEAS WEB
				<!--<img src="dibujo.png" width="20%" style="position: absolute;left: 70px;top: -10px;z-index: 9999">!-->
			</div>
			<figure class="full-width" style="height: 77px;">
				<figcaption class="navLateral-body-cr hide-on-tablet">
					<span style="font-size:18px; font-weight:bold; color:#7f7f7f">
						Acceso autorizado para:
					</span>
				</figcaption>
			</figure>

			<div class="full-width tittles navLateral-body-tittle-menu">
				<?php
				$selsucu = "SELECT EmpRaz FROM " . $bd . ".dbo.EMPRESA WHERE Empest='A' and EmpCod='4'";
				$rslt = db_query($selsucu);
				$rowsucul = db_fetch_array($rslt);
				?>
				<i class="zmdi zmdi-desktop-mac"></i><span class="hide-on-tablet">&nbsp;<a href="dashboard.php" style="color:#276873;"><?php echo $rowsucul['EmpRaz']; ?></a></span>
			</div>

			<nav class="full-width">
				<ul class="full-width list-unstyle menu-principal">
					<?php

					$sql2 = "SELECT b.id,b.nombre1 FROM master a LEFT JOIN login b on a.idmenu=b.id WHERE a.idcliente='$_SESSION[micodigo]' AND b.idt='0' GROUP by a.idcliente,b.id,b.nombre1";

					$sq0 = db_query($sql2);

					while ($rows = db_fetch_array($sq0)) {
					?>
						<li class="full-width divider-menu-h"></li>
						<li class="full-width">
							<a href="#!" class="full-width btn-subMenu">

								<div class="navLateral-body-cl">
									<i class="zmdi zmdi-view-dashboard"></i>
								</div>

								<div class="navLateral-body-cr hide-on-tablet">
									<?php echo $rows['nombre1']; ?>
								</div>

								<span class="zmdi zmdi-chevron-left"></span>
							</a>
							<ul class="full-width menu-principal sub-menu-options">
								<?php


								$sql2 = "SELECT b.script,b.nombre1 FROM master a LEFT JOIN login b on a.idmenu=b.id WHERE a.idcliente='$_SESSION[micodigo]' AND b.idt='$rows[id]' group by b.script,b.nombre1";

								$sq = db_query($sql2);
								while ($row1s = db_fetch_array($sq)) {
								?>
									<li class="full-width">
										<a href="<?php echo $row1s['script']; ?>" class="full-width">

											<div class="navLateral-body-cl">
												<i class="zmdi zmdi-minus"></i>
											</div>

											<div class="navLateral-body-cr hide-on-tablet">
												<?php echo $row1s['nombre1']; ?>
											</div>
										</a>
									</li>
								<?php
								}
								?>
							</ul>
						<?php
					}
						?>
						</li>
				</ul>
			</nav>
		</div>
	</section>
	<section class="full-width pageContent">
		<section class="full-width text-center" style="padding: 40px 0; padding-left: 5px;">
			<script src="../js/notificaciones.js"></script>