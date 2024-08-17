<?php
require('fpdf/fpdf.php');

// Captura os dados do formulário
$nome_responsavel = $_POST['nome_responsavel'];
$rg_responsavel = $_POST['rg_responsavel'];
$cpf_responsavel = $_POST['cpf_responsavel'];
$nascimento_responsavel = $_POST['nascimento_responsavel'];
$isMenor = $_POST['isMenor'];
$nome_menor = $_POST['nome_menor'] ?? '';
$rg_menor = $_POST['rg_menor'] ?? '';
$cpf_menor = $_POST['cpf_menor'] ?? '';
$nascimento_menor = $_POST['nascimento_menor'] ?? '';
$local_tatuagem = $_POST['local_tatuagem'];
$data_tatuagem = $_POST['data_tatuagem'];
$nome_tatuador = $_POST['nome_tatuador'];
$cicatrizacao = $_POST['cicatrizacao'];
$desmaio = $_POST['desmaio'];
$hemofilico = $_POST['hemofilico'];
$hepatite = $_POST['hepatite'];
$hepatite_tipo = $_POST['hepatite_tipo'] ?? '';
$hiv = $_POST['hiv'];
$autoimune = $_POST['autoimune'];
$epileptico = $_POST['epileptico'];
$medicamento = $_POST['medicamento'];
$medicamento_nome = $_POST['medicamento_nome'] ?? '';
$alergia = $_POST['alergia'];
$alergia_nome = $_POST['alergia_nome'] ?? '';
$assinatura_responsavel = $_POST['assinatura_responsavel'];

// Cria uma instância do FPDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 12);

// Adiciona o conteúdo ao PDF
$pdf->MultiCell(0, 10, utf8_decode("Termo de Autorização - Estúdio de Tatuagem\n\nDeclaração de Autorização\nEu, abaixo identificado, declaro que no gozo pleno de minhas faculdades mentais e psíquicas pelo presente e na melhor forma de direito, autorizo o(a) artista a executar sobre meu corpo ou de meu/minha filho(a) menor nascido, abaixo identificado, que em minha companhia reside e pelo qual sou inteiramente responsável a prática da tatuagem.\n\nAssumo na qualidade de genitor(a) do(a) menor, plena responsabilidade pelo trabalho ora autorizado. É de minha livre vontade declarar que isento de responsabilidade civil ou criminal ao tatuador(a), seja de ordem médica, estética ou ainda defeitos da própria inscrição, salvo aquelas decorrentes de imperícia técnica. Ficando ainda plenamente ciente de que o procedimento da tatuagem tem caráter permanente, não podendo ser removida.\n\nDeclaro ainda, ser do meu conhecimento as técnicas a serem executadas, os materiais a serem utilizados, bem como fui informado e tenho total ciência dos procedimentos e cuidados que devem ser executados por mim ou por meu/minha filho(a) durante o período recomendado pelo tatuador, com a finalidade de evitar qualquer complicação no período de cicatrização do local. Reconheço finalmente que a tatuagem se trata de um processo artesanal e como tal não comporta perfeição.\n"));

// Adiciona informações pessoais
$pdf->Ln(10);
$pdf->Cell(0, 10, utf8_decode("Nome do cliente/Responsável: " . $nome_responsavel), 0, 1);
$pdf->Cell(0, 10, utf8_decode("RG: " . $rg_responsavel), 0, 1);
$pdf->Cell(0, 10, utf8_decode("CPF: " . $cpf_responsavel), 0, 1);
$pdf->Cell(0, 10, utf8_decode("Data de Nascimento: " . $nascimento_responsavel), 0, 1);

if ($isMenor === 'sim') {
    $pdf->Ln(10);
    $pdf->Cell(0, 10, utf8_decode("Informações do Menor"), 0, 1);
    $pdf->Cell(0, 10, utf8_decode("Nome do menor: " . $nome_menor), 0, 1);
    $pdf->Cell(0, 10, utf8_decode("RG: " . $rg_menor), 0, 1);
    $pdf->Cell(0, 10, utf8_decode("CPF: " . $cpf_menor), 0, 1);
    $pdf->Cell(0, 10, utf8_decode("Data de Nascimento: " . $nascimento_menor), 0, 1);
}

$pdf->Ln(10);
$pdf->Cell(0, 10, utf8_decode("Local e desenho da tatuagem: " . $local_tatuagem), 0, 1);
$pdf->Cell(0, 10, utf8_decode("Data: " . $data_tatuagem), 0, 1);
$pdf->Cell(0, 10, utf8_decode("Nome do tatuador: " . $nome_tatuador), 0, 1);

$pdf->Ln(10);
$pdf->Cell(0, 10, utf8_decode("Tem problemas de cicatrização? " . ($cicatrizacao === 'sim' ? 'Sim' : 'Não')), 0, 1);
$pdf->Cell(0, 10, utf8_decode("Tem problemas de desmaio? " . ($desmaio === 'sim' ? 'Sim' : 'Não')), 0, 1);
$pdf->Cell(0, 10, utf8_decode("É hemofílico? " . ($hemofilico === 'sim' ? 'Sim' : 'Não')), 0, 1);

if ($hepatite === 'sim') {
    $pdf->Cell(0, 10, utf8_decode("Já contraiu hepatite? Sim - Tipo e quando: " . $hepatite_tipo), 0, 1);
} else {
    $pdf->Cell(0, 10, utf8_decode("Já contraiu hepatite? Não"), 0, 1);
}

$pdf->Cell(0, 10, utf8_decode("Portador de HIV? " . ($hiv === 'sim' ? 'Sim' : 'Não')), 0, 1);
$pdf->Cell(0, 10, utf8_decode("Tem doença autoimune? " . ($autoimune === 'sim' ? 'Sim' : 'Não')), 0, 1);
$pdf->Cell(0, 10, utf8_decode("É epilético? " . ($epileptico === 'sim' ? 'Sim' : 'Não')), 0, 1);

if ($medicamento === 'sim') {
    $pdf->Cell(0, 10, utf8_decode("Faz uso de algum medicamento? Sim - Qual: " . $medicamento_nome), 0, 1);
} else {
    $pdf->Cell(0, 10, utf8_decode("Faz uso de algum medicamento? Não"), 0, 1);
}

if ($alergia === 'sim') {
    $pdf->Cell(0, 10, utf8_decode("É alérgico a algo? Sim - Qual: " . $alergia_nome), 0, 1);
} else {
    $pdf->Cell(0, 10, utf8_decode("É alérgico a algo? Não"), 0, 1);
}

$pdf->Ln(10);
$pdf->Cell(0, 10, utf8_decode('Assinatura do Responsável: ' . $assinatura_responsavel), 0, 1);

$pdf->Output('D', 'termo_autorizacao.pdf');
?>
