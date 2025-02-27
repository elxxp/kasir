<?php
ob_end_clean();

require('../lib/fpdf/fpdf.php');

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(40, 10, 'Hello, PDF dengan FPDF!', 1, 0, 'C');
$pdf->Output();
?>

