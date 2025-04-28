<?php
session_start();
//header('Content-Type: text/xml; charset=ISO-8859-1');
require('../../../includes/dbmssql_cfg.php');
// Muestra los datos	
$codNegocio = "";
$codNegocio		= $_REQUEST['codNegocio'];
$codEmpresa		= $_REQUEST['codEmpresa'];
$codCliente		= $_REQUEST['codCliente'];
$valorProceso	= $_REQUEST['valorProceso'];
$valorCobro		= $_REQUEST['valorCobro'];

if ($valorProceso == 'F') {
	$sigla		= 'Fac';
	$tabla_cab	= 'CABORDFAC';
	$fisico		= 'factura';
	$documento	= 'FACTURAS, CREDITO Y DEBITO';
}
if ($valorProceso == 'P') {
	$sigla		= 'Prof';
	$tabla_cab	= 'CABORDPROF';
	$fisico		= 'proforma';
	$documento	= 'PROFORMAS';
}


$sql_datosCliente = "select 	p.cliraz
						from 	cliente p
						where 	p.clicod='" . $codCliente . "' ";
$dsl_datosCliente = $_SESSION['dbmssql']->getAll($sql_datosCliente);
foreach ($dsl_datosCliente as $val => $value) {
	$cliraz	= trim($value['cliraz']);
}

$sql_datosEmpresa = "select 	p.empraz
						from 	empresa p
						where 	p.empcod='" . $codEmpresa . "' ";
$dsl_datosEmpresa = $_SESSION['dbmssql']->getAll($sql_datosEmpresa);
foreach ($dsl_datosEmpresa as $val => $value) {
	$empraz	= trim($value['empraz']);
}

$sql_datosNegocio = "select 	p.negdes
						from 	negocio p
						where 	p.negcod='" . $codNegocio . "' ";
$dsl_datosNegocio = $_SESSION['dbmssql']->getAll($sql_datosNegocio);
foreach ($dsl_datosNegocio as $val => $value) {
	$negdes	= trim($value['negdes']);
}

?>
<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<style type="text/css">
		.borderTabla1 {
			BORDER-LEFT: #3a79ad 1px solid;
			BORDER-RIGHT: #3a79ad 1px solid;
			BORDER-BOTTOM: #3a79ad 1px solid;
			BORDER-TOP: #3a79ad 1px solid
		}

		.smalltext {
			FONT-SIZE: 10px;
			COLOR: #333333;
			FONT-FAMILY: Arial;
		}

		.smallville {
			FONT-SIZE: 12px;
			COLOR: #000000;
			FONT-FAMILY: Arial;
		}

		.smalltext {
			FONT-SIZE: 10px;
			COLOR: #333333;
			FONT-FAMILY: Arial;
		}
	</style>

	<script type="text/javascript">
		document.oncontextmenu = function() {
			return false
		}

		function right(e) {
			var msg = "BLACK FLYS S.R.L.";
			//var input ="<input name=usrname id=usrname type=text class=inputbox1 size=20 />"
			if (navigator.appName == 'Netscape' && e.which == 3) {
				alert(msg); //- Si no quieres asustar a tu usuario entonces quita esta linea...
				return false;
			} else if (navigator.appName == "Microsoft Internet Explorer" && event.button == 2) {
				//    alert(msg); - Si no quieres asustar al usuario que utiliza IE,  entonces quita esta linea...
				//- Aunque realmente se lo merezca...
				return false;
			}
			return true;
		}
		document.onmousedown = right;



		function objetoAjax() {
			var xmlhttp = false;
			try {
				xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
			} catch (e) {
				try {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				} catch (E) {
					xmlhttp = false;
				}
			}

			if (!xmlhttp && typeof XMLHttpRequest != 'undefined') {
				xmlhttp = new XMLHttpRequest();
			}
			return xmlhttp;
		}


		function to_derecha(cod, des) {
			var nuevaOpcion = document.createElement("option");
			nuevaOpcion.value = cod;
			nuevaOpcion.innerHTML = des;
			nuevaOpcion.setAttribute("ondblclick", "to_izquierda(this.value,'" + des + "')");

			document.getElementById("list_servicio_2").appendChild(nuevaOpcion);

			var ind = document.getElementById("list_servicio_1").selectedIndex;
			var opcion = document.getElementById('list_servicio_1').options[ind];
			var lista = document.getElementById('list_servicio_1');
			lista.removeChild(opcion);
		}

		function to_izquierda(cod, des) {
			var nuevaOpcion = document.createElement("option");
			nuevaOpcion.value = cod;
			nuevaOpcion.innerHTML = des;
			nuevaOpcion.setAttribute("ondblclick", "to_derecha(this.value,'" + des + "')");
			document.getElementById("list_servicio_1").appendChild(nuevaOpcion);

			var ind = document.getElementById("list_servicio_2").selectedIndex;
			var opcion = document.getElementById('list_servicio_2').options[ind];
			var lista = document.getElementById('list_servicio_2');
			lista.removeChild(opcion);
		}


		function add_servicio() {
			var txt = "";
			var codfacs = "";
			var Lista = document.getElementById("list_servicio_2");
			var long = Lista.options.length;

			if (long == 0) {
				alert("Debe elegir como mínimo una Factura/Proforma.");
				return;
			}

			for (var i = 0; i < long; i++) {
				if (i == long - 1)
					txt = txt.concat(Lista.options[i].text);
				else
					txt = txt.concat(Lista.options[i].text, "-");
			}
			document.getElementById("texto_item").value = txt;

			////concatenando los codigos de factura
			for (var i = 0; i < long; i++) {
				if (i == long - 1)
					codfacs = codfacs.concat(Lista.options[i].value);
				else
					codfacs = codfacs.concat(Lista.options[i].value, "-");
			}
			document.getElementById("codsfacturas").value = codfacs;

		}


		function setNomenclatura() {
			var label = "";
			var codfacs = "";

			var Lista = document.getElementById("list_servicio_2");
			var long = Lista.options.length;
			if (long == 0) {
				alert("Debe elegir como mínimo una Factura, Nota de Credito, Nota de Debito, Proforma, Nota de Credito de Proforma O Nota de Debito de Proforma.");
				return;
			}
			for (var i = 0; i < long; i++) {
				if (i == long - 1)
					codfacs = codfacs.concat(Lista.options[i].value);
				else
					codfacs = codfacs.concat(Lista.options[i].value, "-");
			}
			document.getElementById("codsfacturas").value = codfacs;

			var label_facturas = document.getElementById("codsfacturas").value;

			//Capturar los input desde la otra ventana.
			var valorProceso = "";
			if (window.opener.document.getElementById("tipoProf").checked == true) {
				valorProceso = window.opener.document.getElementById("tipoProf").value;
			}
			if (window.opener.document.getElementById("tipoFac").checked == true) {
				valorProceso = window.opener.document.getElementById("tipoFac").value;
			}

			var codEmpresa = window.opener.document.getElementById("empcod").value;
			var codCliente = window.opener.document.getElementById("clicod").value;
			var codNegocio = window.opener.document.getElementById("codNegocio").value;

			//var codfacturas = window.opener.document.getElementById("codfacturas_s");	
			var info = "&valorProceso=" + valorProceso +
				"&codEmpresa=" + codEmpresa +
				"&codCliente=" + codCliente +
				"&codNegocio=" + codNegocio +
				"&facts=" + label_facturas;
			/////	alert(info);
			if (long > 0) {
				Carga_generar = objetoAjax();
				Carga_generar.open("POST", "../../../templates/cobranza/generar/filas_detalle_opago.php", true);
				Carga_generar.onreadystatechange = function() {
					if (Carga_generar.readyState == 1) {
						window.opener.document.getElementById("detalle_generar").innerHTML = img_carga2;
					} else if (Carga_generar.readyState == 4) {
						window.opener.document.getElementById("detalle_generar").innerHTML = Carga_generar.responseText;


						////COBRO TOTAL
						Carga_total_cob = objetoAjax();
						Carga_total_cob.open("POST", "../../../templates/cobranza/generar/suma_opago.php?totalCobrar=6", true);
						Carga_total_cob.onreadystatechange = function() {
							if (Carga_total_cob.readyState == 1) {
								window.opener.document.getElementById("txt_total_cobrar").value = "";
							} else if (Carga_total_cob.readyState == 4) {
								window.opener.document.getElementById("txt_total_cobrar").value = Carga_total_cob.responseText;


								////DETRACCION TOTAL
								Carga_total_det = objetoAjax();
								Carga_total_det.open("POST", "../../../templates/cobranza/generar/suma_opago.php?totalDetraccion=6", true);
								Carga_total_det.onreadystatechange = function() {
									if (Carga_total_det.readyState == 1) {
										window.opener.document.getElementById("txt_total_detrac").value = "";
									} else if (Carga_total_det.readyState == 4) {
										window.opener.document.getElementById("txt_total_detrac").value = Carga_total_det.responseText;


										////IGV TOTAL
										Carga_total_igv = objetoAjax();
										Carga_total_igv.open("POST", "../../../templates/cobranza/generar/suma_opago.php?totalImporte=6", true);
										Carga_total_igv.onreadystatechange = function() {
											if (Carga_total_igv.readyState == 1) {
												window.opener.document.getElementById("txt_acumulado").value = "";
											} else if (Carga_total_igv.readyState == 4) {
												window.opener.document.getElementById("txt_acumulado").value = Carga_total_igv.responseText;


												////RETENCION TOTAL
												Carga_total_retencion = objetoAjax();
												Carga_total_retencion.open("POST", "../../../templates/cobranza/generar/suma_opago.php?totalRetencion=6", true);
												Carga_total_retencion.onreadystatechange = function() {
													if (Carga_total_retencion.readyState == 1) {
														window.opener.document.getElementById("txt_total_reten").value = "";
													} else if (Carga_total_retencion.readyState == 4) {
														window.opener.document.getElementById("txt_total_reten").value = Carga_total_retencion.responseText;

														window.close();
													}

												}
												Carga_total_retencion.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
												Carga_total_retencion.send(info);


											}
										}
										Carga_total_igv.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
										Carga_total_igv.send(info);

									}
								}
								Carga_total_det.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
								Carga_total_det.send(info);



							}
						}
						Carga_total_cob.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
						Carga_total_cob.send(info);



					}
				}
				Carga_generar.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
				Carga_generar.send(info);



			} else {
				alert("El Modulo de Destino Ha Cambiado.");
				window.close();
			}


		}

		function todos_derecha() {
			var long = document.getElementById("list_servicio_1").options.length;
			for (var z = 0; z < long; z++) {
				//capturando el item y value de la fila N-sima
				var codigo = document.getElementById("list_servicio_1").options[z].value;
				var texto = document.getElementById("list_servicio_1").options[z].text;
				//pasando uno por uno al otro lado
				var cmbmaestro = document.getElementById("list_servicio_2");
				var nuevaOp = document.createElement("option");
				nuevaOp.value = codigo;
				nuevaOp.innerHTML = texto;
				nuevaOp.setAttribute("ondblclick", "to_izquierda(this.value,'" + texto + "')");
				document.getElementById("list_servicio_1").appendChild(nuevaOp);
				cmbmaestro.appendChild(nuevaOp);

			}
			//Borrando todo del lado inicial
			var cmbSelect = document.getElementById("list_servicio_1");
			cmbSelect.length = 0;
		}


		function todos_izquierda() {
			var long = document.getElementById("list_servicio_2").options.length;
			for (var z = 0; z < long; z++) {
				//capturando el item y value de la fila N-sima
				var codigo = document.getElementById("list_servicio_2").options[z].value;
				var texto = document.getElementById("list_servicio_2").options[z].text;
				//pasando uno por uno al otro lado
				var cmbmaestro = document.getElementById("list_servicio_1");
				var nuevaOp = document.createElement("option");
				nuevaOp.value = codigo;
				nuevaOp.innerHTML = texto;
				nuevaOp.setAttribute("ondblclick", "to_derecha(this.value,'" + texto + "')");
				document.getElementById("list_servicio_1").appendChild(nuevaOp);
				cmbmaestro.appendChild(nuevaOp);
			}
			//Borrando todo del lado inicial
			var cmbSelect = document.getElementById("list_servicio_2");
			cmbSelect.length = 0;
		}
	</script>
	<title>LISTADO DE DOCUMENTOS</title>
</head>

<body leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0">
	<form name="form_material" id="form_material" method="post">
		<table width="522" border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td width="10" height="5"><!-- &nbsp;--></td>
				<td width="641"><!-- &nbsp;--></td>
				<td width="10"><!--&nbsp; --></td>
			</tr>
			<tr>
				<td><!--&nbsp; --></td>
				<td>
					<table width="100%" border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td>
								<table width="100%" border="0" cellpadding="0" cellspacing="0" class="borderTabla1">
									<tr class="smallville">
										<td width="639" height="23" valign="middle" background="../../../images/bg_topbar.gif"><img src="../../../images/b_view.png" width="16" height="16" align="absmiddle">&nbsp;<strong>SELECCION DE <?= $documento ?> - <?= $negdes ?></strong>
											<input name="codsfacturas" type="hidden" id="codsfacturas" value="">
											<input name="NegCod" type="hidden" id="NegCod" value="<?= $codNegocio ?>">
										</td>
									</tr>
								</table>
								<table width="100%" border="0">
									<tr>
										<td height="2"><!-- --></td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td height="200">
								<table width="100%" border="0" cellpadding="0" cellspacing="0" class="borderTabla1">
									<tr>
										<td height="23" valign="middle">
											<table width="100%" border="0" cellpadding="0" cellspacing="1">

												<tr>
													<td width="14%" height="15" valign="middle" class="smallville">&nbsp;Cliente&nbsp;</td>
													<td width="86%" valign="middle" class="smallville">: <?= $cliraz ?></td>
												</tr>
												<tr>
													<td height="15" valign="middle" class="smallville">&nbsp;Empresa</td>
													<td height="15" valign="middle" class="smallville">: <?= $empraz ?></td>
												</tr>
												<tr>
													<td height="20" align="left" valign="middle" class="smallville">&nbsp;Leyenda</td>
													<td height="20" align="left" valign="middle" class="smallville">:&nbsp;
														F = Factura&nbsp;&nbsp;|&nbsp;&nbsp;
														P = Proforma&nbsp;&nbsp;|&nbsp;&nbsp;
														C = N. de Crédito&nbsp;&nbsp;|<br>&nbsp;&nbsp;
														D = N. de Débito&nbsp;&nbsp;|&nbsp;&nbsp;
														H = N. de Credito Proforma&nbsp;&nbsp;|&nbsp;&nbsp;
														I = N. de Debito Proforma
													</td>
												</tr>
											</table>
											<table width="100%" height="220" border="0" cellpadding="0" cellspacing="0">
												<tr class="smalltext">
													<td width="44%" rowspan="2" align="center" valign="middle"><label>
															<?php
															//NOTA CREDITO PROFORMA
															$sql_Tipo = "	select	'" . $valorProceso . "' as letra,convert(varchar,codord" . $sigla . ") as codigo,
									convert(varchar," . $fisico . ") as fisico
							from	" . $tabla_cab . "
							where	cod" . $sigla . "neg='" . $codNegocio . "' and 
									cod" . $sigla . "cli='" . $codCliente . "' and 
									cod" . $sigla . "emp='" . $codEmpresa . "' and
									estado not in ('C','E') and codord" . $sigla . " 
									not in 
									(	select	coddoc 
										from 	detordpago 
										where 	codpagneg='" . $codNegocio . "' and 
												codpagemp='" . $codEmpresa . "' and 
												codpagcli='" . $codCliente . "' and 
												tipodoc='" . $valorProceso . "' and 
												estado not in ('E','C')
									)								
							union all
							select	'C' as letra,convert(varchar,CodOrdNotaCre) as codigo, 
									convert(varchar,NotaCredito) as fisico 
							from	CABNOTACREDITO
							where	CodNotaNeg='" . $codNegocio . "' and 
									CodNotaCli = '" . $codCliente . "' and 
									CodNotaEmp='" . $codEmpresa . "' and
									estado not in ('C','E') and CodOrdNotaCre
									not in (	select	coddoc 
												from	detordpago 
												where	codpagneg='" . $codNegocio . "' and 
														codpagemp='" . $codEmpresa . "' and 
														codpagcli='" . $codCliente . "' and 
														tipodoc='C' and estado not in ('E','C')
											)
															
							union all
							select	'D' as letra,convert(varchar,CodOrdNotaDeb) as codigo, 
									convert(varchar,NotaDebito) as fisico 
							from	CABNOTADEBITO
							where	CodNotaNeg='" . $codNegocio . "' and 
									CodNotaCli = '" . $codCliente . "' and 
									CodNotaEmp='" . $codEmpresa . "' and
									estado not in ('C','E') and CodOrdNotaDeb
									not in (	select	coddoc 
												from	detordpago 
												where	codpagneg='" . $codNegocio . "' and 
														codpagemp='" . $codEmpresa . "' and 
														codpagcli='" . $codCliente . "' and 
														tipodoc='D' and estado not in ('E','C')
											)
							union all 
							select 'H' as letra,convert(varchar,CodOrdNotaCre) as codigo, convert(varchar,NotaCredito) as fisico 
							from CABNOTACREDITO_PROF 
							where CodNotaNeg='" . $codNegocio . "' and CodNotaCli='" . $codCliente . "' and CodNotaEmp='" . $codEmpresa . "' and estado not in('C','E') and 
							CodOrdNotaCre not in(select coddoc from detordpago 
												where codpagneg='" . $codNegocio . "' and codpagemp='" . $codEmpresa . "' and codpagcli='" . $codCliente . "' and 
												tipodoc='H' and estado not in ('E','C')
												)
							union all 
							select 'I' as letra,convert(varchar,CodOrdNotaDeb) as codigo, convert(varchar,NotaDebito) as fisico 
							from CABNOTADEBITO_PROF 
							where CodNotaNeg='" . $codNegocio . "' and CodNotaCli='" . $codCliente . "' and CodNotaEmp='" . $codEmpresa . "' and estado not in('C','E') and 
							CodOrdNotaDeb not in(select coddoc from detordpago 
												where codpagneg='" . $codNegocio . "' and codpagemp='" . $codEmpresa . "' and codpagcli='" . $codCliente . "' and 
												tipodoc='I' and estado not in ('E','C')
												) 
											
											";
															?>

															<select style="width:200px;" name="list_servicio_1" size="15" id="list_servicio_1" class="smalltext">
																<?php
																$dsl_Tipo = $_SESSION['dbmssql']->getAll($sql_Tipo);
																foreach ($dsl_Tipo as $Tipo => $inf) {
																	$letra = trim($inf['letra']);
																	$sercod = trim($letra . '_' . $inf['codigo']);
																	$serdes	= $letra . '_' . rtrim((string)str_pad($inf['codigo'], 7, '0', STR_PAD_LEFT) . ' (' . $inf['fisico'] . ')');
																	echo "<option value='" . $sercod . "' onDblClick=\"to_derecha(this.value,'" . $serdes . "')\">" . $serdes . "</option>";
																}
																?>
															</select><?php //echo $sql_Tipo;
																		?>
														</label></td>
													<td width="42%" rowspan="2" align="center" valign="middle"><label>
															<select name="list_servicio_2" size="15" id="list_servicio_2" style="width:200px;" class="smalltext">
															</select></label></td>
													<td width="14%" align="center" valign="middle">
														<a style="cursor:pointer;" onClick="setNomenclatura()"><img src="../../../images/derecha.png" width="32" height="32" border="0"></a>
													</td>
												</tr>
												<tr class="smalltext">
													<td align="center" valign="middle">&nbsp;</td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td></td>
						</tr>
					</table>
				</td>
				<td><!--&nbsp; --></td>
			</tr>
			<tr>
				<td height="5"><!--&nbsp; --></td>
				<td><!--&nbsp; --><strong class="smallville"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a onClick="todos_derecha()" style="cursor:pointer"><img src="../../../images/2rightarrow.png" width="16" height="16" align="absmiddle"></a>&nbsp;Seleccionar Todas &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a onClick="todos_izquierda()" style="cursor:pointer"><img src="../../../images/2leftarrow.png" width="16" height="16" border="0" align="absmiddle"></a>&nbsp;Deseleccionar Todas</strong> </td>
				<td><!--&nbsp; --></td>
			</tr>
		</table>
	</form>
</body>

</html>