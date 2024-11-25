<?php
require('../../avelart/fpdf/fpdf.php');

// Recebendo os dados do formulário
$nome = $_POST['nome'] ?? '';
$carga_horaria = $_POST['carga_horaria'] ?? '';
$endereco = $_POST['endereco'] ?? '';
$evento = $_POST['evento'] ?? '';

// Configurando o PDF
$pdf = new FPDF('L', 'mm', 'A4'); // Paisagem, mm, A4
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);

// Adicionando o título do certificado
$pdf->Cell(0, 20, 'CERTIFICADO DE PARTICIPACAO', 0, 1, 'C');

// Adicionando o corpo do certificado
$pdf->SetFont('Arial', '', 12);
$pdf->Ln(10);
$pdf->MultiCell(0, 10, utf8_decode("
Certificamos que o(a) Sr(a). $nome participou do evento \"$evento\", 
realizado em $endereco, com carga horária total de $carga_horaria horas.
"), 0, 'C');

// Adicionando a data e assinatura
$pdf->Ln(20);
$pdf->SetFont('Arial', 'I', 10);
$pdf->Cell(0, 10, utf8_decode("Emitido em: " . date('d/m/Y')), 0, 1, 'R');
$pdf->Ln(20);
$pdf->Cell(0, 10, utf8_decode("________________________"), 0, 1, 'C');
$pdf->Cell(0, 10, utf8_decode("Assinatura do Responsável"), 0, 1, 'C');

// Saída do PDF
$pdf->Output('I', 'certificado.pdf');
