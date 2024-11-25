<?php
require('../fpdf/fpdf.php');

// Conectar ao banco de dados
$servername = "127.0.0.1:3306";
$username = "u221588236_root";
$password = "Camila@307";
$dbname = "u221588236_controle_finan";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Obter o ID passado pela URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id === 0) {
    die("ID inválido.");
}

// Recuperar os dados da tabela 'termos_enviados' para o ID especificado
$sql = "SELECT * FROM termos_enviados WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if (!$data) {
    die("Nenhum dado encontrado para o ID: " . $id);
}

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
$nome_responsavel = $data['nome_responsavel'];
$rg_responsavel = $data['rg_responsavel'];
$cpf_responsavel = $data['cpf_responsavel'];
$nascimento_responsavel = $data['nascimento_responsavel'];
$nome_cliente = $data['nome_cliente'];
$email_cliente = $data['email_cliente'];
$rg_cliente = $data['rg_cliente'];
$cpf_cliente = $data['cpf_cliente'];
$nascimento_cliente = $data['nascimento_cliente'];
$assinatura_responsavel = $data['assinatura_responsavel'];
$local_tatuagem = $data['local_tatuagem'];
$data_tatuagem = $data['data_tatuagem'];
$nome_tatuador = $data['nome_tatuador'];
$cicatrizacao = $data['cicatrizacao'];
$desmaio = $data['desmaio'];
$hemofilico = $data['hemofilico'];
$hepatite = $data['hepatite'];
$hiv = $data['hiv'];
$autoimune = $data['autoimune'];
$epileptico = $data['epileptico'];
$medicamento = $data['medicamento'];
$alergia = $data['alergia'];
$doencaPele = $data['doenca_pele'];
$nomeDoencaPele = $data['nome_doenca_pele'];
$queloide = $data['queloide'];
$hipertensao = $data['hipertensao'];
$cardiaco = $data['cardiaco'];
$pressaoBaixa = $data['pressaoBaixa'];
$doencaCronica = $data['doenca_cronica'];
$nomeDoencaCronica = $data['nome_doenca_cronica'];
$diabetes = $data['diabetes'];
$diabetesTipo = $data['diabetes_tipo'];
$corticoide = $data['corticoide'];
$reacao = $data['reacao'];
$gravida = $data['gravida'];
$bebidaDroga = $data['bebida_droga'];
$jejum = $data['jejum'];
$alergiaLatex = $data['alergia_latex'];

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

if ($nome_responsavel != '') {
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
$pdf->Cell(0, 10, utf8_decode('Já contraiu hepatite? ' . ($hepatite === 'sim' ? 'Sim' : 'Não') . ($hepatite === 'sim' ? ' (Tipo e quando: ' . $data['hepatite_tipo'] . ')' : '')), 0, 1);
$pdf->Cell(0, 10, utf8_decode('Portador de HIV? ' . ($hiv === 'sim' ? 'Sim' : 'Não')), 0, 1);
$pdf->Cell(0, 10, utf8_decode('Tem doença autoimune? ' . ($autoimune === 'sim' ? 'Sim' : 'Não')), 0, 1);
$pdf->Cell(0, 10, utf8_decode('É epilético? ' . ($epileptico === 'sim' ? 'Sim' : 'Não')), 0, 1);
$pdf->Cell(0, 10, utf8_decode('Faz uso de algum medicamento? ' . ($medicamento === 'sim' ? 'Sim (Qual: ' . $data['medicamento_nome'] . ')' : 'Não')), 0, 1);
$pdf->Cell(0, 10, utf8_decode('É alérgico a algo? ' . ($alergia === 'sim' ? 'Sim (Qual: ' . $data['alergia_nome'] . ')' : 'Não')), 0, 1);
$pdf->Cell(0, 10, utf8_decode('Possui doenças de pele, como psoríase, dermatite, vitiligo..? ' . ($doencaPele === 'sim' ? 'Sim (Qual: ' . $nomeDoencaPele . ')' : 'Não')), 0, 1);
$pdf->Cell(0, 10, utf8_decode('Tem tendência a queloide? ' . ($queloide === 'sim' ? 'Sim' : 'Não')), 0, 1);
$pdf->Cell(0, 10, utf8_decode('Possui hipertensão (pressão alta)? ' . ($hipertensao === 'sim' ? 'Sim' : 'Não')), 0, 1);
$pdf->Cell(0, 10, utf8_decode('Tem ou já teve alguma condição cardíaca? ' . ($cardiaco === 'sim' ? 'Sim' : 'Não')), 0, 1);
$pdf->Cell(0, 10, utf8_decode('Sofre com pressão baixa? ' . ($pressaoBaixa === 'sim' ? 'Sim' : 'Não')), 0, 1);
$pdf->Cell(0, 10, utf8_decode('Você possui alguma condição de saúde ou doença crônica? ' . ($doencaCronica === 'sim' ? 'Sim (Qual: ' . $nomeDoencaCronica . ')' : 'Não')), 0, 1);
$pdf->Cell(0, 10, utf8_decode('Você tem ou já teve diabetes? ' . ($diabetes === 'sim' ? 'Sim (Qual: ' . $diabetesTipo . ')' : 'Não')), 0, 1);
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


// Fechar a conexão
$stmt->close();
$conn->close();

// Exibir o PDF no navegador
$pdf->Output('I', 'termo_autorizacao.pdf');
?>