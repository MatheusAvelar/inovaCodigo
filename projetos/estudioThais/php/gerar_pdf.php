<?php
require('../fpdf/fpdf.php');  // Caminho relativo para a biblioteca FPDF

class PDF extends FPDF
{
    function Header()
    {
        $this->Image('../img/tatto.jpeg', 10, 0, 30);  // Caminho relativo para a imagem
        $this->SetFont('Arial', 'B', 16);
        $this->Cell(0, 10, 'Termo de Autorizacao - Estudio Avelart Tattoo', 0, 1, 'C');
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
        $this->Cell(0, 10, utf8_decode($title), 0, 1, 'L');
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
$nome_cliente = $_POST['nome_cliente'];
$rg_cliente = $_POST['rg_cliente'];
$cpf_cliente = $_POST['cpf_cliente'];
$nascimento_cliente = $_POST['nascimento_cliente'];
$assinatura_responsavel = $_POST['assinatura_responsavel'];
$local_tatuagem = $_POST['local_tatuagem'];
$data_tatuagem = $_POST['data_tatuagem'];
$nome_tatuador = $_POST['nome_tatuador'];
$cicatrizacao = $_POST['cicatrizacao'];
$desmaio = $_POST['desmaio'];
$hemofilico = $_POST['hemofilico'];
$hepatite = $_POST['hepatite'];
$hiv = $_POST['hiv'];
$autoimune = $_POST['autoimune'];
$epileptico = $_POST['epileptico'];
$medicamento = $_POST['medicamento'];
$alergia = $_POST['alergia'];

// Adicionar conteúdo ao PDF
$pdf->ChapterTitle('Declaração de Autorização');
$pdf->ChapterBody(
    "Eu, abaixo identificado, declaro que no gozo pleno de minhas faculdades mentais e psíquicas pelo presente e na melhor forma de direito, autorizo o(a) artista a executar sobre meu corpo ou de meu/minha filho(a) menor nascido, abaixo identificado, que em minha companhia reside e pelo qual sou inteiramente responsável a prática da tatuagem.\n" .
    "Assumo na qualidade de genitor(a) do(a) menor, plena responsabilidade pelo trabalho ora autorizado. É de minha livre vontade declarar que isento de responsabilidade civil ou criminal ao tatuador(a), seja de ordem médica, estética ou ainda defeitos da própria inscrição, salvo aquelas decorrentes de imperícia técnica. Ficando ainda plenamente ciente de que o procedimento da tatuagem tem caráter permanente, não podendo ser removida.\n" .
    "Declaro ainda, ser do meu conhecimento as técnicas a serem executadas, os materiais a serem utilizados, bem como fui informado e tenho total ciência dos procedimentos e cuidados que devem ser executados por mim ou por meu/minha filho(a) durante o período recomendado pelo tatuador, com a finalidade de evitar qualquer complicação no período de cicatrização do local. Reconheço finalmente que a tatuagem se trata de um processo artesanal e como tal não comporta perfeição."
);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, utf8_decode('Informações do Responsável'), 0, 1, 'L');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, utf8_decode('Nome: ' . $nome_responsavel), 0, 1);
$pdf->Cell(0, 10, utf8_decode('RG: ' . $rg_responsavel), 0, 1);
$pdf->Cell(0, 10, utf8_decode('CPF: ' . $cpf_responsavel), 0, 1);
$pdf->Cell(0, 10, utf8_decode('Data de Nascimento: ' . date('d/m/Y', strtotime($nascimento_responsavel))), 0, 1);

if ($isMenor === 'sim') {
    $pdf->Ln(10);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, utf8_decode('Informações do Menor'), 0, 1, 'L');
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, utf8_decode('Nome: ' . $nome_cliente), 0, 1);
    $pdf->Cell(0, 10, utf8_decode('RG: ' . $rg_cliente), 0, 1);
    $pdf->Cell(0, 10, utf8_decode('CPF: ' . $cpf_cliente), 0, 1);
    $pdf->Cell(0, 10, utf8_decode('Data de Nascimento: ' . date('d/m/Y', strtotime($nascimento_cliente))), 0, 1);
}

$pdf->Ln(10);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, utf8_decode('Local e desenho da tatuagem'), 0, 1, 'L');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, utf8_decode('Local: ' . $local_tatuagem), 0, 1);
$pdf->Cell(0, 10, utf8_decode('Data: ' . date('d/m/Y', strtotime($data_tatuagem))), 0, 1);
$pdf->Cell(0, 10, utf8_decode('Nome do Tatuador: ' . $nome_tatuador), 0, 1);

$pdf->Ln(10);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, utf8_decode('Questões de Saúde'), 0, 1, 'L');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, utf8_decode('Tem problemas de cicatrização? ' . ($cicatrizacao === 'sim' ? 'Sim' : 'Não')), 0, 1);
$pdf->Cell(0, 10, utf8_decode('Tem problemas de desmaio? ' . ($desmaio === 'sim' ? 'Sim' : 'Não')), 0, 1);
$pdf->Cell(0, 10, utf8_decode('É hemofílico? ' . ($hemofilico === 'sim' ? 'Sim' : 'Não')), 0, 1);
$pdf->Cell(0, 10, utf8_decode('Já contraiu hepatite? ' . ($hepatite === 'sim' ? 'Sim' : 'Não') . ($hepatite === 'sim' ? ' (Tipo e quando: ' . $_POST['hepatite_tipo'] . ')' : '')), 0, 1);
$pdf->Cell(0, 10, utf8_decode('Portador de HIV? ' . ($hiv === 'sim' ? 'Sim' : 'Não')), 0, 1);
$pdf->Cell(0, 10, utf8_decode('Tem doença autoimune? ' . ($autoimune === 'sim' ? 'Sim' : 'Não')), 0, 1);
$pdf->Cell(0, 10, utf8_decode('É epilético? ' . ($epileptico === 'sim' ? 'Sim' : 'Não')), 0, 1);
$pdf->Cell(0, 10, utf8_decode('Faz uso de algum medicamento? ' . ($medicamento === 'sim' ? 'Sim (Qual: ' . $_POST['medicamento_nome'] . ')' : 'Não')), 0, 1);
$pdf->Cell(0, 10, utf8_decode('É alérgico a algo? ' . ($alergia === 'sim' ? 'Sim (Qual: ' . $_POST['alergia_nome'] . ')' : 'Não')), 0, 1);

$pdf->Ln(10);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, utf8_decode('Assinatura'), 0, 1, 'L');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, utf8_decode('Assinatura do Responsável: ' . $assinatura_responsavel), 0, 1);

$pdf->Output('D', 'termo_autorizacao.pdf');
?>
