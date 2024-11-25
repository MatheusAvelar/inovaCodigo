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

// Adicionando plano de fundo timbrado
$pdf->Image('background.png', 0, 0, 297, 210); // Imagem de fundo (ajustar caminho e tamanho)

// Adicionando o título do certificado
$pdf->SetFont('Arial', 'B', 24);
$pdf->SetTextColor(0, 0, 128); // Azul escuro
$pdf->Cell(0, 40, 'CERTIFICADO DE PARTICIPACAO', 0, 1, 'C');

// Adicionando o corpo do certificado
$pdf->SetFont('Arial', '', 14);
$pdf->SetTextColor(0, 0, 0); // Preto
$pdf->Ln(10);
$pdf->MultiCell(0, 10, utf8_decode("
Certificamos que o(a) Sr(a). $nome participou do evento \"$evento\", 
realizado em $endereco, com carga horária total de $carga_horaria horas.
"), 0, 'C');

// Adicionando carimbo ou assinatura
$pdf->Image('carimbo.png', 230, 150, 50, 50); // Imagem do carimbo (ajustar caminho e tamanho)

// Adicionando a data e assinatura
$pdf->Ln(40);
$pdf->SetFont('Arial', 'I', 12);
$pdf->Cell(0, 10, utf8_decode("Emitido em: " . date('d/m/Y')), 0, 1, 'R');
$pdf->Ln(20);
$pdf->Cell(0, 10, utf8_decode("________________________"), 0, 1, 'C');
$pdf->Cell(0, 10, utf8_decode("Assinatura do Responsável"), 0, 1, 'C');

// Saída do PDF
$pdf->Output('I', 'certificado.pdf');