<?php
require_once 'auth.php';
require_once 'config.php';
require_once 'fpdf/fpdf.php';
require_once 'ventas_filtros.php';

// Creamos el documento PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, utf8_decode('Reporte de Ventas'), 0, 1, 'C');
$pdf->Ln(5);

// Encabezados de tabla
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(20, 8, 'ID', 1);
$pdf->Cell(40, 8, 'Fecha', 1);
$pdf->Cell(80, 8, 'Cliente', 1);
$pdf->Cell(40, 8, 'Total (Q)', 1);
$pdf->Ln();

$pdf->SetFont('Arial', '', 10);
$res = $mysqli->query($sql);

while($v = $res->fetch_assoc()) {
    $pdf->Cell(20, 8, $v['id'], 1);
    $pdf->Cell(40, 8, $v['creado_en'], 1);
    $pdf->Cell(80, 8, utf8_decode($v['cliente']), 1);
    $pdf->Cell(40, 8, number_format($v['total'], 2), 1);
    $pdf->Ln();
}

$pdf->Output('I', 'reporte_ventas.pdf');
?>
