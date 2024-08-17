<?php
require('../fpdf/fpdf.php');  // Caminho relativo para a biblioteca FPDF

class PDF extends FPDF
{
    function Header()
    {
        $this->Image('../img/tatto.jpeg', 10, 6, 30);  // Caminho relativo para a imagem
        $this->SetFont('Arial', 'B', 16);
        $this->Cell(0, 10, 'Termo de Autorizacao - Estudio de Tatuagem', 0, 1, 'C');
        $this->Ln(10);
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Pagina ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

    function ChapterTitle($title)
    {
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, $title, 0, 1, 'L');
        $this->Ln(4);
    }

    function ChapterBody($body)
    {
        $this->SetFont('Arial', '', 12);
        $this->MultiCell(0, 10, utf8_decode($body));
        $this->Ln();
    }
}

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();

// Coletar dados do formulário
$nome_responsavel = $_POST['nome_responsavel'];
$rg_responsavel = $_POST['rg_responsavel'];
$cpf_responsavel = $_POST['cpf_responsavel'];
$nascimento_responsavel = $_POST['nascimento_responsavel'];
$isMenor = $_POST['isMenor'];
$nome_menor = $_POST['nome_menor'];
$rg_menor = $_POST['rg_menor'];
$cpf_menor = $_POST['cpf_menor'];
$nascimento_menor = $_POST['nascimento_menor'];
$assinatura_responsavel = $_POST['assinatura_responsavel'];

// Adicionar conteúdo ao PDF
$pdf->ChapterTitle('Declaração de Autorização');
$pdf->ChapterBody("Eu, abaixo identificado, declaro que no gozo pleno de minhas faculdades mentais e psíquicas pelo presente e na melhor forma de direito, autorizo o(a) artista a executar sobre meu corpo ou de meu/minha filho(a) menor nascido, abaixo identificado, que em minha companhia reside e pelo qual sou inteiramente responsável a prática da tatuagem...");
$pdf->Ln(10);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'Informações do Responsável', 0, 1, 'L');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, 'Nome: ' . utf8_decode($nome_responsavel), 0, 1);
$pdf->Cell(0, 10, 'RG: ' . utf8_decode($rg_responsavel), 0, 1);
$pdf->Cell(0, 10, 'CPF: ' . utf8_decode($cpf_responsavel), 0, 1);
$pdf->Cell(0, 10, 'Data de Nascimento: ' . date('d/m/Y', strtotime($nascimento_responsavel)), 0, 1);

if ($isMenor === 'sim') {
    $pdf->Ln(10);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, 'Informações do Menor', 0, 1, 'L');
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, 'Nome: ' . utf8_decode($nome_menor), 0, 1);
    $pdf->Cell(0, 10, 'RG: ' . utf8_decode($rg_menor), 0, 1);
    $pdf->Cell(0, 10, 'CPF: ' . utf8_decode($cpf_menor), 0, 1);
    $pdf->Cell(0, 10, 'Data de Nascimento: ' . date('d/m/Y', strtotime($nascimento_menor)), 0, 1);
}

$pdf->Ln(10);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'Assinatura', 0, 1, 'L');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, 'Assinatura do Responsável: ' . utf8_decode($assinatura_responsavel), 0, 1);

$pdf->Output('D', 'termo_autorizacao.pdf');
?>
