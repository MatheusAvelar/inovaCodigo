<?php
include 'envia_email.php';
include 'envia_whatsapp.php';

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

// Definir a data de amanhã
$dataAmanha = date('Y-m-d', strtotime('+1 day'));

// Selecionar agendamentos para o dia seguinte
$sql = "SELECT nome_cliente, email_cliente, telefone_cliente, estilo, tamanho, valor, forma_pagamento, data, start_time, end_time FROM agendamentos WHERE data = ? AND status = '1'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $dataAmanha);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $nomeCliente = $row['nome_cliente'];
    $emailCliente = $row['email_cliente'];
    $telefoneCliente = "+55" . str_replace(['(', ')', ' ', '-'], '', $row['telefone_cliente']);
    $estilo = $row['estilo'];
    $tamanho = $row['tamanho'];
    $valor = 'R$ ' . number_format($row['valor'], 2, ',', '.');
    $dataFormatada = date('d/m/Y', strtotime($row['data']));
    $startTime = $row['start_time'];

    // Envio de e-mail
    if (!empty($emailCliente)) {
        $to = $emailCliente;
        $subject = 'Lembrete de Agendamento - Estúdio Avelart';
        $messages = "
            <html>
            <head>
                <title>Lembrete de Agendamento</title>
            </head>
            <body>
                <h1>Olá $nomeCliente,</h1>
                <p>Este é um lembrete do seu agendamento para amanhã!</p>
                <p><strong>Data:</strong> $dataFormatada</p>
                <p><strong>Hora:</strong> $startTime</p>
                <p><strong>Estilo:</strong> $estilo</p>
                <p><strong>Tamanho:</strong> $tamanho</p>
                <p><strong>Valor:</strong> $valor</p>
                <p>Por favor, chegue 15 minutos antes do horário agendado.</p>
                <p>Estamos ansiosos para vê-lo!</p>
                <p>Atenciosamente,<br>Equipe do Estúdio Avelart</p>
            </body>
            </html>
        ";
        $headers = 'From: lembretes@estudioavelart.com' . "\r\n" .
                    'Reply-To: contato@estudioavelart.com' . "\r\n" .
                    'Content-Type: text/html; charset=UTF-8' . "\r\n";

        sendEmail($to, $subject, $messages, $headers);
    }

    // Envio de WhatsApp
    if (!empty($telefoneCliente)) {
        $parameters = [
            ['type' => 'text', 'text' => $nomeCliente],
            ['type' => 'text', 'text' => $dataFormatada],
            ['type' => 'text', 'text' => $startTime],
            ['type' => 'text', 'text' => $estilo],
            ['type' => 'text', 'text' => $tamanho],
            ['type' => 'text', 'text' => $valor]
        ];
        sendWhatsAppMessage($telefoneCliente, 'lembrete_agendamento', 'pt_BR', $parameters);
    }
}

$stmt->close();
$conn->close();
