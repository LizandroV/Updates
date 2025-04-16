<?php
include "../config.php";
require "../code128.php";
// header('Content-Type: application/pdf ; charset=UTF-8');
// Obtener parámetros de la URL
$codemp = $_GET['codemp'];
$cod_recibo = $_GET['cod_recibo'];
$label = $_GET['label'];

if (empty($cod_recibo)) {
    die("Número de recibo no especificado");
}

$bd = "DESARROLLO";

// Consulta SQL para obtener datos del recibo
$sql_cab = "SELECT 
    r.codemp, r.cod_recibo, r.recibopago, 
    cli.CliRaz as cliente, emp.EmpRaz as empresa, 
    neg.NegDes as negocio, 
    CONVERT(VARCHAR(10), r.fecha_recibo, 103) as fecha_recibo, 
    m.SimMon as moneda, r.importe, 
    CONVERT(VARCHAR(205), r.obs) as obs, 
    CONVERT(VARCHAR(5), r.fechareg, 108) as hora
FROM $bd.dbo.CAB_RECIBOPAGO r 
LEFT JOIN $bd.dbo.CLIENTE cli ON r.codcli=cli.CliCod AND cli.CliEst='A' 
LEFT JOIN $bd.dbo.EMPRESA emp ON r.codemp=emp.EmpCod AND emp.EmpEst='A' 
LEFT JOIN $bd.dbo.NEGOCIO neg ON r.codneg=neg.NegCod AND neg.NegEst='A' 
LEFT JOIN $bd.dbo.MONEDA m ON r.codmone=m.CodMon AND m.Estado='A' 
LEFT JOIN $bd.dbo.grupo_clientes gc ON r.cod_grupocli=gc.cod_grupocli AND gc.estado=0 
WHERE r.codemp='$codemp' AND r.cod_recibo='$cod_recibo' AND r.Estado NOT IN('C')";

$dsl_cab = db_fetch_all($sql_cab);

if (empty($dsl_cab)) {
    die("No se encontró el recibo especificado");
}

// Obtener datos del recibo
$deta = $dsl_cab[0];
$recibopago = trim($deta['recibopago']);
$cliente = trim($deta['cliente']);
$empresa = trim($deta['empresa']);
$negocio = trim($deta['negocio']);
$fecha_recibo = trim($deta['fecha_recibo']);
$moneda = trim($deta['moneda']);
$importe = number_format(trim($deta['importe']), 2);
$obs = trim(utf8_decode($deta['obs']));
$hora = trim($deta['hora']);

// function limpiarTexto($texto)
// {
//     $buscar = ['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú', 'ñ', 'Ñ'];
//     $reemplazar = ['a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U', 'n', 'N'];

//     return str_replace($buscar, $reemplazar, $texto);
// }
// $comentario = limpiarTexto($obs);

// Crear PDF
$pdf = new PDF_Code128('P', 'mm', array(80, 190)); // Ticket (80mm de ancho)
$pdf->SetMargins(1, 2, 1);
$pdf->AddPage();

// Encabezado
$cabecera = "RECIBO $recibopago";
$pdf->Ln(2);
$pdf->SetFont('Arial', 'B', 15);
$pdf->Cell(78, 5, $cabecera, 0, 1, 'C');
$pdf->Ln(2);

// Código de barras con número de recibo
$pdf->Code128(5, $pdf->GetY(), "RE|" . $recibopago, 70, 10);
$pdf->Ln(15);

// Línea divisoria
// $pdf->SetLineWidth(0.3);
// $pdf->Line($pdf->GetX(), $pdf->GetY(), $pdf->GetX() + 78, $pdf->GetY());
// $pdf->Ln(5);

// Datos de EMPRESA y FECHA en la misma fila
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(43, 5, 'Empresa:', 0, 0, 'L');
$pdf->Cell(30, 5, 'Fecha Recibo:', 0, 1, 'R');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(43, 5, $empresa, 0, 'L', 0);
$pdf->SetXY(44, 31);
$pdf->Cell(30, 5, $fecha_recibo . ' ' . $hora, 0, 1, 'R');
$pdf->Ln(8);

// Datos del cliente
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 5, 'Cliente:', 0, 1, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 5, $cliente, 0, 1, 'L');
$pdf->Ln(3);

// Datos del Negocio
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 5, 'Negocio:', 0, 1, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(0, 5, $negocio, 0, 'L');
$pdf->Ln(3);

//Datos de Pago
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(0, 5, 'Orden de Pago:', 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(0, 5, 'Registro de Pago:', 0, 1, 'R');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 5, "", 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 5, "", 0, 1, 'R');
$pdf->Ln(3);

// Datos del Documento
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(0, 5, 'Documento:' . "", 0, 1, 'L');
$pdf->Ln(3);

//Datos de Importe
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 5, 'Importe:', 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 5, 'Fecha Abono:', 0, 1, 'R');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 5, $moneda . ' ' . $importe, 0, 0, 'L');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 5, "", 0, 1, 'R');
$pdf->Ln(3);

//Datos de Operacion
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(0, 5, '# Operacion:', 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(0, 5, 'Banco Emp:', 0, 1, 'R');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 5, "", 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 5, "", 0, 1, 'R');
$pdf->Ln(3);

// Datos del Pago
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(0, 5, 'Forma de Pago: ' . "", 0, 1, 'L');
$pdf->Ln(3);

// Comentarios
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(0, 5, 'Comentario:', 0, 1, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(0, 5, $obs, 0, 'L');
$pdf->Ln(10);

// Firma
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 5, '_______________', 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 5, '_______________', 0, 1, 'R');
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(40, 5, "Firma Cliente", 0, 0, 'C');
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(40, 5, "Firma Usuario", 0, 1, 'C');
$pdf->Ln(2);

// LABEL
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(55, 5, "", 0, 0, 'L');
$pdf->Cell(20, 5, $label, 'TLRB', 0, 'C');

// Pie de página
//$pdf->SetFont('Arial', '', 8);
//$pdf->Cell(0, 4, 'Fecha impresion: ' . date('d/m/Y H:i:s'), 0, 1, 'C');

// Generar PDF
$pdf->Output('Recibo_' . str_pad($recibopago, 8, "0", STR_PAD_LEFT) . '.pdf', 'I');
