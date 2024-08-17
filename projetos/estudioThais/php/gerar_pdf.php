<?php
require('fpdf/fpdf.php'); 

class PDF extends FPDF
{
    // Cabeçalho do PDF
    function Header()
    {
        // Logo
        $this->Image('img/tatto.jpeg', 10, 6, 30);
        // Fonte do cabeçalho
        $this->SetFont('Arial', 'B', 12);
        // Título
        $this->Cell(0, 10, 'Termo de Autorizacao - Estudio de Tatuagem', 0, 1, 'C');
        // Espaço para a próxima linha
        $this->Ln(10);
    }

    // Rodapé do PDF
    function Footer()
    {
        // Posiciona a 1.5 cm do final da página
        $this->SetY(-15);
        // Fonte do rodapé
        $this->SetFont('Arial', 'I', 8);
        // Número da página
        $this->Cell(0, 10, 'Pagina ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

// Criação do PDF
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 12);

// Recebendo os dados do formulário
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

// Adicionando conteúdo ao PDF
$pdf->MultiCell(0, 10, utf8_decode("Eu, abaixo identificado, declaro que no gozo pleno de minhas faculdades mentais e psíquicas pelo presente e na melhor forma de direito, autorizo o(a) artista a executar sobre meu corpo ou de meu/minha filho(a) menor nascido, abaixo identificado, que em minha companhia reside e pelo qual sou inteiramente responsável a prática da tatuagem..."));
$pdf->Ln(10);

$pdf->Cell(0, 10, 'Nome do cliente/Responsavel: ' . utf8_decode($nome_responsavel), 0, 1);
$pdf->Cell(0, 10, 'RG: ' . utf8_decode($rg_responsavel), 0, 1);
$pdf->Cell(0, 10, 'CPF: ' . utf8_decode($cpf_responsavel), 0, 1);
$pdf->Cell(0, 10, 'Data de Nascimento: ' . date('d/m/Y', strtotime($nascimento_responsavel)), 0, 1);

if ($isMenor === 'sim') {
    $pdf->Ln(10);
    $pdf->Cell(0, 10, utf8_decode('Informações do Menor:'), 0, 1);
    $pdf->Cell(0, 10, 'Nome do menor: ' . utf8_decode($nome_menor), 0, 1);
    $pdf->Cell(0, 10, 'RG: ' . utf8_decode($rg_menor), 0, 1);
    $pdf->Cell(0, 10, 'CPF: ' . utf8_decode($cpf_menor), 0, 1);
    $pdf->Cell(0, 10, 'Data de Nascimento: ' . date('d/m/Y', strtotime($nascimento_menor)), 0, 1);
}

$pdf->Ln(20);
$pdf->Cell(0, 10, utf8_decode('Assinatura do Responsavel: ') . utf8_decode($assinatura_responsavel), 0, 1);

// Output do PDF
$pdf->Output('D', 'termo_autorizacao.pdf');
?>
