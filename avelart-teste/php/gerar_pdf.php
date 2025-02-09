<?php
require('../fpdf/fpdf.php');
include 'utils.php';

class PDF extends FPDF
{
    /*function Header()
    {
        $this->Image('../img/tatto.jpeg', 10, 6, 25);  // Caminho relativo para a imagem
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 10, 'Termo de Autorizacao - Estudio Avelart Tattoo', 0, 1, 'C');
        $this->Ln(10);
    }*/
    function Header()
    {
        // Centralizar a imagem
        $this->SetXY(($this->GetPageWidth() - 25) / 2, 10); // A largura da imagem é 50
        $this->Image('../img/tatto.jpeg', null, null, 25);  // Centraliza e ajusta a largura da imagem para 50

        // Espaço após a imagem
        $this->Ln(10); // Ajuste para criar um espaçamento adequado após a imagem

        // Título
        $this->SetFont('Arial', 'B', 14);
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
$isMenor = $_POST['isMenor'];
$rg_responsavel = $_POST['rg_responsavel'];
$cpf_responsavel = $_POST['cpf_responsavel'];
$nascimento_responsavel = $_POST['nascimento_responsavel'];
$nome_cliente = $_POST['nome_cliente'];
$email_cliente = $_POST['email_cliente'];
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
$hepatite_tipo = $_POST['hepatite_tipo'];
$medicamento_nome = $_POST['medicamento_nome'];
$alergia_nome = $_POST['alergia_nome'];
$doencaPele = $_POST['pele'];
$nomeDoencaPele = $_POST['doenca_nome'];
$queloide = $_POST['queloide'];
$hipertensao = $_POST['hipertensao'];
$cardiaco = $_POST['cardiaco'];
$pressaoBaixa = $_POST['pressaoBaixa'];
$doencaCronica = $_POST['doencaCronica'];
$nomeDoencaCronica = $_POST['doencaCronica_nome'];
$diabetes = $_POST['diabetes'];
$diabetesTipo = $_POST['diabetes_nome'];
$corticoide = $_POST['corticoide'];
$reacao = $_POST['reacao'];
$gravida = $_POST['gravida'];
$bebidaDroga = $_POST['bebidaDroga'];
$jejum = $_POST['jejum'];
$alergiaLatex = $_POST['alergiaLatex'];

// Adicionar conteúdo ao PDF
$pdf->ChapterTitle('Declaração de Autorização');
$pdf->ChapterBody(
    "Eu, abaixo identificado, declaro que no gozo pleno de minhas faculdades mentais e psíquicas pelo presente e na melhor forma de direito, autorizo o(a) artista a executar sobre meu corpo ou de meu/minha filho(a) menor nascido, abaixo identificado, que em minha companhia reside e pelo qual sou inteiramente responsável a prática da tatuagem.\n" .
    "Assumo na qualidade de genitor(a) do(a) menor, plena responsabilidade pelo trabalho ora autorizado. É de minha livre vontade declarar que isento de responsabilidade civil ou criminal ao tatuador(a), seja de ordem médica, estética ou ainda defeitos da própria inscrição, salvo aquelas decorrentes de imperícia técnica. Ficando ainda plenamente ciente de que o procedimento da tatuagem tem caráter permanente, não podendo ser removida.\n" .
    "Declaro ainda, ser do meu conhecimento as técnicas a serem executadas, os materiais a serem utilizados, bem como fui informado e tenho total ciência dos procedimentos e cuidados que devem ser executados por mim ou por meu/minha filho(a) durante o período recomendado pelo tatuador, com a finalidade de evitar qualquer complicação no período de cicatrização do local. Apesar de todos os cuidados tomados pelo estúdio com a devida biossegurança durante o procedimento da tattoo, como o uso de materiais descartáveis e esterilizados, o procedimento pode apresentar riscos, tais como: reações alérgicas a tinta; infecções caso os cuidados solicitados pelo seu tatuador não sejam seguidos corretamente; irritação ou sensibilidade na pele; riscos de cicatrização irregular. Reconheço finalmente que a tatuagem se trata de um processo artesanal e como tal, não comporta perfeição.\n" . 
    "Autorizo o estúdio Avelart Tattoo, e o/a tatuador(a) a utilizar imagens da tatuagem realizada e, se necessário, de partes do meu corpo que apareçam nas fotografias/filmagens, para fins de divulgação e marketing, incluindo, mas não se limitando, a publicações em redes sociais, portfólio, site oficial e materiais promocionais do estúdio. Esta autorização é concedida de forma gratuita e por prazo indeterminado. E, declaro também que não há qualquer vínculo comercial ou de exclusividade decorrente desta autorização."
);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, utf8_decode('Informações do Cliente'), 0, 1, 'L');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, utf8_decode('Nome: ' . $nome_cliente), 0, 1);
$pdf->Cell(0, 10, utf8_decode('RG: ' . $rg_cliente), 0, 1);
$pdf->Cell(0, 10, utf8_decode('CPF: ' . $cpf_cliente), 0, 1);
$pdf->Cell(0, 10, utf8_decode('Data de Nascimento: ' . date('d/m/Y', strtotime($nascimento_cliente))), 0, 1);

if ($isMenor === 'sim') {
    $pdf->Ln(10);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, utf8_decode('Informações do Responsável'), 0, 1, 'L');
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, utf8_decode('Nome: ' . $nome_responsavel), 0, 1);
    $pdf->Cell(0, 10, utf8_decode('RG: ' . $rg_responsavel), 0, 1);
    $pdf->Cell(0, 10, utf8_decode('CPF: ' . $cpf_responsavel), 0, 1);
    $pdf->Cell(0, 10, utf8_decode('Data de Nascimento: ' . date('d/m/Y', strtotime($nascimento_responsavel))), 0, 1);
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
$pdf->Cell(0, 10, utf8_decode('Possui doenças de pele, como psoríase, dermatite, vitiligo..? ' . ($doencaPele === 'sim' ? 'Sim (Qual: ' . $_POST['doenca_nome'] . ')' : 'Não')), 0, 1);
$pdf->Cell(0, 10, utf8_decode('Tem tendência a queloide? ' . ($queloide === 'sim' ? 'Sim' : 'Não')), 0, 1);
$pdf->Cell(0, 10, utf8_decode('Possui hipertensão (pressão alta)? ' . ($hipertensao === 'sim' ? 'Sim' : 'Não')), 0, 1);
$pdf->Cell(0, 10, utf8_decode('Tem ou já teve alguma condição cardíaca? ' . ($cardiaco === 'sim' ? 'Sim' : 'Não')), 0, 1);
$pdf->Cell(0, 10, utf8_decode('Sofre com pressão baixa? ' . ($pressaoBaixa === 'sim' ? 'Sim' : 'Não')), 0, 1);
$pdf->Cell(0, 10, utf8_decode('Você possui alguma condição de saúde ou doença crônica? ' . ($doencaCronica === 'sim' ? 'Sim (Qual: ' . $_POST['doencaCronica_nome'] . ')' : 'Não')), 0, 1);
$pdf->Cell(0, 10, utf8_decode('Você tem ou já teve diabetes? ' . ($diabetes === 'sim' ? 'Sim (Qual: ' . $_POST['diabetes_nome'] . ')' : 'Não')), 0, 1);
$pdf->Cell(0, 10, utf8_decode('Faz ou fez uso de corticoide recentemente? ' . ($corticoide === 'sim' ? 'Sim' : 'Não')), 0, 1);
$pdf->Cell(0, 10, utf8_decode('Já teve alguma reação alérgica a tatuagens? ' . ($reacao === 'sim' ? 'Sim' : 'Não')), 0, 1);
$pdf->Cell(0, 10, utf8_decode('Está grávida ou amamentando? ' . ($gravida === 'sim' ? 'Sim' : 'Não')), 0, 1);

$pdf->Ln(10);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, utf8_decode('Hábitos e cuidados'), 0, 1, 'L');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, utf8_decode('Consome bebidas alcoólicas ou drogas recreativas? ' . ($bebidaDroga === 'sim' ? 'Sim' : 'Não')), 0, 1);
$pdf->Cell(0, 10, utf8_decode('Está em jejum há mais de 4 horas? ' . ($jejum === 'sim' ? 'Sim' : 'Não')), 0, 1);
$pdf->Cell(0, 10, utf8_decode('Tem alergia a látex ou materiais descartáveis? ' . ($alergiaLatex === 'sim' ? 'Sim' : 'Não')), 0, 1);

$pdf->Ln(10);
$pdf->ChapterBody(
    "Confirmo que todas as informações acima são verdadeiras, e, assumo total responsabilidade por qualquer omissão ou erro nas mesmas.\n" .
    "Estou ciente dos cuidados necessários antes e após a realização da tatuagem e que os resultados podem variar dependendo de fatores individuais, como cicatrização e cuidados pessoais."
);

$pdf->Ln(10);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, utf8_decode('Assinatura'), 0, 1, 'L');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, utf8_decode('Assinatura do Responsável: ' . $assinatura_responsavel), 0, 1);

try {
    $conn = conectaBanco();
} catch (Exception $e) {
    die("Erro: " . $e->getMessage());
}

// Preparar a consulta SQL
$sql = "INSERT INTO termos_enviados (
            usuario_id, data_envio, status, 
            nome_responsavel, rg_responsavel, cpf_responsavel, nascimento_responsavel, 
            nome_cliente, email_cliente, rg_cliente, cpf_cliente, nascimento_cliente, 
            local_tatuagem, data_tatuagem, nome_tatuador, cicatrizacao, desmaio, 
            hemofilico, hepatite, hiv, autoimune, epileptico, medicamento, alergia, 
            assinatura_responsavel, hepatite_tipo, medicamento_nome, alergia_nome, doenca_pele, nome_doenca_pele, nome_doenca_cronica, diabetes_tipo, queloide, hipertensao, cardiaco, pressaoBaixa, doenca_cronica, diabetes, corticoide, reacao, gravida, bebida_droga, jejum, alergia_latex
        ) VALUES (
            ?, NOW(), 'ativo', 
            ?, ?, ?, ?, ?,
            ?, ?, ?, ?, 
            ?, ?, ?, ?, ?, 
            ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
            ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
            ?, ?, ?, ?, ?
        )";

// Preparar a declaração
$stmt = $conn->prepare($sql);

// Verifique se a preparação da declaração foi bem-sucedida
if ($stmt === false) {
    die("Erro na preparação da consulta: " . $conn->error);
}

$usuario_id = $_POST['id'];

// Vincular parâmetros
$stmt->bind_param(
    "ssssssssssssssssssssssssssssssssssssssssss", 
    $usuario_id, 
    $nome_responsavel, $rg_responsavel, $cpf_responsavel, $nascimento_responsavel, 
    $nome_cliente, $email_cliente, $rg_cliente, $cpf_cliente, $nascimento_cliente, 
    $local_tatuagem, $data_tatuagem, $nome_tatuador, $cicatrizacao, $desmaio, 
    $hemofilico, $hepatite, $hiv, $autoimune, $epileptico, $medicamento, $alergia, 
    $assinatura_responsavel, $hepatite_tipo, $medicamento_nome, $alergia_nome, $doencaPele, $nomeDoencaPele, $nomeDoencaCronica, $diabetesTipo, $queloide,$hipertensao,$cardiaco,$pressaoBaixa,$doencaCronica,$diabetes,$corticoide,$reacao,$gravida,$bebidaDroga,$jejum,$alergiaLatex);

$stmt->execute();

$pdf->Output('D', 'termo_autorizacao.pdf');
// Redireciona de volta para a página de agendamento
echo '<script type="text/javascript">';
echo 'window.location.href = "../termo_responsabilidade.php";';
echo '</script>';

// Fechar a declaração e a conexão
$stmt->close();
$conn->close();
?>
