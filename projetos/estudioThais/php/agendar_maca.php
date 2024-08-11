<?php
session_start();
include 'envia_email.php';
include 'envia_whatsapp.php';

// Definindo variáveis para mensagem de retorno
$_SESSION['status'] = "";
$_SESSION['message'] = "";

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
$emailCliente = $_POST['email'] ?? '';
$estilo = $_POST['estilo'] ?? '';
$tamanho = $_POST['tamanho'] ?? '';
$valor = str_replace(['R$ ', '.'], ['', ','], $_POST['valor']) ?? ''; // Remove R$ e formata para decimal
$valor = str_replace(',', '.', $valor); // Substitui a vírgula por ponto para garantir formato decimal
$valor = floatval($valor); // Converte para float
$valorFormatado = 'R$ ' . number_format($valor, 2, ',', '.');
$formaPagamento = $_POST['pagamento'] ?? '';
$sinalPago = $_POST['sinal_pago'] ?? '';
$descricao = $_POST['descricao'] ?? '';
$maca = $_POST['maca'] ?? '';
$date = $_POST['date1'] ?? '';
$dataFormatada = date('d/m/Y', strtotime($date));
$startTime = $_POST['start-time1'] ?? '';
$endTime = $_POST['end-time1'] ?? '';
$status = '1';

// Captura o telefone e remove a máscara
$telefoneCliente = $_POST['telefone'] ?? '';
$telefoneCliente = str_replace(['(', ')', ' ', '-'], '', $telefoneCliente); // Remove a máscara de telefone

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
    $_SESSION['status'] = "error";
    $_SESSION['message'] = implode("<br>", $errors);
} else {
    // Verificação de conflito de horário
    $stmt = $conn->prepare("SELECT start_time, end_time FROM agendamentos WHERE maca_id = ? AND status = '1' AND data = ? AND ((start_time <= ? AND end_time > ?) OR (start_time < ? AND end_time >= ?))");
    $stmt->bind_param("ssssss", $maca, $date, $startTime, $startTime, $endTime, $endTime);

    if (!$stmt->execute()) {
        $_SESSION['status'] = "error";
        $_SESSION['message'] = "Erro na execução da consulta: " . $stmt->error;
    } else {
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($existingStartTime, $existingEndTime);
            $stmt->fetch();
            $_SESSION['status'] = "error";
            $_SESSION['message'] = "Já existe um agendamento para a maca e data selecionadas com conflito de horário: de $existingStartTime às $existingEndTime.";
            $stmt->close();
        } else {
            // Inserir no banco de dados se não houver conflitos
            $usuarioId = $_SESSION['id']; // ID do usuário logado
            $stmt = $conn->prepare("INSERT INTO agendamentos (nome_cliente, estilo, tamanho, valor, forma_pagamento, sinal_pago, descricao, maca_id, data, start_time, end_time, usuario_id, email_cliente, status, telefone_cliente) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssssssssssss", $nomeCliente, $estilo, $tamanho, $valor, $formaPagamento, $sinalPago, $descricao, $maca, $date, $startTime, $endTime, $usuarioId, $emailCliente, $status, $telefoneCliente);

            if (!$stmt->execute()) {
                $_SESSION['status'] = "error";
                $_SESSION['message'] = "Erro na inserção: " . $stmt->error;
            } else {
                $_SESSION['status'] = "success";
                $_SESSION['message'] = "Agendamento realizado com sucesso!";

                // Envio de e-mail, se o e-mail do cliente estiver preenchido
                if (!empty($emailCliente)) {
                    $to = $emailCliente; // Endereço de e-mail do cliente
                    $subject = 'Confirmação de Agendamento de Tatuagem';
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
                        $_SESSION['message'] .= "\n"."Foi enviado um e-mail com os dados do agendamento para o cliente.";
                    } else {
                        $_SESSION['message'] .= "\n"."Ocorreu um erro ao enviar o e-mail para o cliente.";
                    }
                }

                // Envio de WhatsApp, se o telefone do cliente estiver preenchido
                if (!empty($telefoneCliente)) {
                    $toPhoneNumber = "+55".$telefoneCliente;
                    $parameters = [
                        ['type' => 'text', 'text' => $nomeCliente],
                        ['type' => 'text', 'text' => $dataFormatada],
                        ['type' => 'text', 'text' => $startTime],
                        ['type' => 'text', 'text' => $estilo],
                        ['type' => 'text', 'text' => $tamanho],
                        ['type' => 'text', 'text' => $valorFormatado]
                    ];
                    $response = sendWhatsAppMessage($toPhoneNumber, 'confirmacao_de_agendamento', 'pt_BR', $parameters);
                    $_SESSION['message'] .= "\n"."Mensagem de confirmação enviada via WhatsApp.";
                }
            }

            $stmt->close();
        }
    }
}

// Redireciona de volta para a página de agendamento
header("Location: ../agendamento.php");
exit();

// Fechando a conexão
$conn->close();
