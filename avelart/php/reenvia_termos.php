<?php
include 'envia_email.php';
session_start();
// Definindo variáveis para mensagem de retorno
$_SESSION['status'] = "";
$_SESSION['message'] = "";

// Verifica se o ID do agendamento foi passado na URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die('ID do agendamento não fornecido.');
}

$agendamentoId = intval($_GET['id']);

// Configuração da conexão com o banco de dados
$servername = "127.0.0.1:3306";
$username = "u221588236_root";
$password = "Camila@307";
$dbname = "u221588236_controle_finan";

// Criando a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificando a conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Obtendo os dados do agendamento
$query = "SELECT a.id, a.maca_id, a.data, a.start_time, a.end_time, a.nome_cliente, a.telefone_cliente, a.email_cliente, a.estilo, a.tamanho, a.valor, a.forma_pagamento, a.sinal_pago, a.descricao
          FROM agendamentos AS a
          WHERE a.id = $agendamentoId
          AND status = '1'";
$result = $conn->query($query);

// Verificando se a consulta retornou resultados
if ($result->num_rows > 0) {
    // Obtendo os dados da linha retornada
    $row = $result->fetch_assoc();
    
    // Capturando dados do agendamento
    $nomeCliente = $row['nome_cliente'];
    $emailCliente = $row['email_cliente'];
    $estilo = $row['estilo'];
    $tamanho = $row['tamanho'];
    $valor = $row['valor'];
    $valorFormatado = 'R$ ' . number_format($valor, 2, ',', '.');
    $formaPagamento = $row['forma_pagamento'];
    $sinalPago = $row['sinal_pago'];
    $descricao = $row['descricao'];
    $maca = $row['maca_id'];
    $date = $row['data'];
    $dataFormatada = date('d/m/Y', strtotime($date));
    $startTime = $row['start_time'];
    $endTime = $row['end_time'];
    $status = '1';
    $telefoneCliente = $row['telefone_cliente'];

    // Envio de e-mail, se o e-mail do cliente estiver preenchido
    if (!empty($emailCliente)) {
        $to = $emailCliente;
        $subject = 'Confirmação de Agendamento de Tatuagem';
        
        $linkTermo = "https://avelart.inovacodigo.com.br/termo_responsabilidade.php";
        $linkTermo .= "?nome_cliente=" . urlencode($nomeCliente);
        $linkTermo .= "&telefone_cliente=" . urlencode($telefoneCliente);
        $linkTermo .= "&email_cliente=" . urlencode($emailCliente);
        $linkTermo .= "&id=" . $_SESSION['id'];

        $messages = "
            <html>
            <head>
                <title>Confirmação de Agendamento</title>
            </head>
            <body>
                <h1>Olá $nomeCliente,</h1>
                <p>Seu agendamento foi confirmado com sucesso!</p>
                <p><strong>Data:</strong> $dataFormatada</p>
                <p><strong>Hora:</strong> $startTime</p>
                <p><strong>Estilo:</strong> $estilo</p>
                <p><strong>Tamanho:</strong> $tamanho</p>
                <p><strong>Valor:</strong> $valorFormatado</p>
                <p><a href=\"$linkTermo\">Clique aqui para preencher o termo de responsabilidade</a></p>
                <p>Por favor, chegue 15 minutos antes do horário agendado.</p>
                <p>Obrigado por escolher nosso estúdio Avelart!</p>
                <p>Atenciosamente,<br>Equipe do Estúdio Avelart</p>
            </body>
            </html>
        ";

        $headers = 'From: agendamentos@estudioavelart.com' . "\r\n" .
                'Reply-To: contato@estudioavelart.com' . "\r\n" .
                'Content-Type: text/html; charset=UTF-8' . "\r\n";

        if (sendEmail($to, $subject, $messages, $headers)) {
            $_SESSION['status'] = "success";
            $_SESSION['message'] .= "\n"."Foi enviado um e-mail com os dados do agendamento para o cliente.";
        } else {
            $_SESSION['status'] = "error";
            $_SESSION['message'] .= "\n"."Ocorreu um erro ao enviar o e-mail para o cliente.";
        }
    } else {
        $_SESSION['status'] = "error";
        $_SESSION['message'] .= "\n"."Ocorreu um erro ao enviar o e-mail para o cliente, cliente não possui e-mail cadastrado.";
    }
}

// Redireciona para a página de erro ou sucesso
header("Location: ../horarios_agendados.php");
exit;
// Fechando a conexão com o banco de dados
$conn->close();
?>
