<?php
session_start();
include 'send_email.php';

// Definindo variáveis para mensagem de retorno
$status = "";
$message = "";

// Configuração da conexão com o banco de dados
$servername = "127.0.0.1:3306";
$username = "u221588236_root";
$password = "Camila@307";
$dbname = "u221588236_controle_finan";

// Criando a conexão
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Capturando dados do formulário
$nomeCliente = $_POST['cliente'] ?? '';
$estilo = $_POST['estilo'] ?? '';
$tamanho = $_POST['tamanho'] ?? '';
$valor = str_replace(['R$ ', '.'], ['', ','], $_POST['valor']) ?? ''; // Remove R$ e formata para decimal
$valor = str_replace(',', '.', $valor); // Substitui a vírgula por ponto para garantir formato decimal
$valor = floatval($valor); // Converte para float
$formaPagamento = $_POST['pagamento'] ?? '';
$sinalPago = $_POST['sinal_pago'] ?? '';
$descricao = $_POST['descricao'] ?? '';
$maca = $_POST['maca'] ?? '';
$date = $_POST['date1'] ?? '';
$startTime = $_POST['start-time1'] ?? '';
$endTime = $_POST['end-time1'] ?? '';

// Validação dos dados
$errors = [];
if (empty($maca)) {
    $errors[] = "A maca é obrigatória.";
}
if (empty($date)) {
    $errors[] = "A data é obrigatória.";
}
if (empty($startTime)) {
    $errors[] = "O horário inicial é obrigatório.";
}
if (empty($endTime)) {
    $errors[] = "O horário final é obrigatório.";
}
if (!empty($startTime) && !empty($endTime) && $startTime >= $endTime) {
    $errors[] = "O horário final deve ser maior que o horário inicial.";
}
if (!is_numeric($valor) || $valor <= 0) {
    $errors[] = "O valor deve ser um número maior que R$ 0.";
}

if ($errors) {
    echo "Erros encontrados:<br>";
    foreach ($errors as $error) {
        echo "$error<br>";
    }
}

// Verificação de conflito de horário
if (empty($errors)) {
    $stmt = $conn->prepare("SELECT start_time, end_time FROM agendamentos WHERE maca_id = ? AND data = ? AND ((start_time <= ? AND end_time > ?) OR (start_time < ? AND end_time >= ?))");
    $stmt->bind_param("ssssss", $maca, $date, $startTime, $startTime, $endTime, $endTime);

    if (!$stmt->execute()) {
        echo "Erro na execução da consulta: " . $stmt->error . "<br>";
    } else {
        $stmt->store_result();
        echo "Número de linhas retornadas: " . $stmt->num_rows . "<br>";

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($existingStartTime, $existingEndTime);
            $stmt->fetch();
            $status = "error";
            $message = "Já existe um agendamento para a maca e data selecionadas com conflito de horário: de $existingStartTime às $existingEndTime.";
            $stmt->close();
        } else {
            // Inserir no banco de dados se não houver conflitos
            $usuarioId = $_SESSION['id']; // ID do usuário logado
            $stmt = $conn->prepare("INSERT INTO agendamentos (nome_cliente, estilo, tamanho, valor, forma_pagamento, sinal_pago, descricao, maca_id, data, start_time, end_time, usuario_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssssssss", $nomeCliente, $estilo, $tamanho, $valor, $formaPagamento, $sinalPago, $descricao, $maca, $date, $startTime, $endTime, $usuarioId);

            // Dados do e-mail
            $to = 'matheus_valladao@hotmail.com'; // Endereço de e-mail do cliente
            $subject = 'Confirmação de Agendamento de Tatuagem';
            $messages = "
                <html>
                <head>
                    <title>Confirmação de Agendamento</title>
                </head>
                <body>
                    <h1>Olá [Nome do Cliente],</h1>
                    <p>Seu agendamento foi confirmado com sucesso!</p>
                    <p><strong>Data:</strong> $date</p>
                    <p><strong>Hora:</strong> $startTime</p>
                    <p><strong>Estilo:</strong> $estilo</p>
                    <p><strong>Tamanho:</strong> $tamanho</p>
                    <p><strong>Valor:</strong> $valor</p>
                    <p>Por favor, chegue 15 minutos antes do horário agendado.</p>
                    <p>Obrigado por escolher nosso estúdio Avelart!</p>
                    <p>Atenciosamente,<br>Equipe do Estúdio Avelart</p>
                </body>
                </html>
            ";
            $headers = 'From: agendamentos@estudioavelart.com' . "\r\n" .
                    'Reply-To: contato@estudioavelart.com' . "\r\n" .
                    'Content-Type: text/html; charset=UTF-8' . "\r\n";

            if (!$stmt->execute()) {
                echo "Erro na inserção: " . $stmt->error . "<br>";
            } else {
                if ($result['success']) {
                    $status = "success";
                    $message = "Agendamento realizado com sucesso!"."\n"."Foi enviado um e-mail com os dados do agendamento para o cliente.";
                } else {
                    $message = 'Erro ao enviar e-mail: ' . $result['error'];
                }
            }

            // Enviar o e-mail
            $result = sendEmail($to, $subject, $messages, $headers);

            
            $stmt->close();
        }
    }
} else {
    $status = "error";
    $message = implode("<br>", $errors);
}

// Fechando a conexão
$conn->close();

// Armazenando a mensagem em sessionStorage e redirecionando
echo "<script>
    console.log('Mensagem de erro: " . addslashes($message) . "');
    sessionStorage.setItem('status', '" . addslashes($status) . "');
    sessionStorage.setItem('message', '" . addslashes($message) . "');
    window.location.href = '../agendamento.php';
</script>";
?>
