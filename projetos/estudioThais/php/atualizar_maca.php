<?php
session_start();

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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $maca = intval($_POST['maca']);
    $date1 = $_POST['date1'];
    $start_time1 = $_POST['start-time1'];
    $end_time1 = $_POST['end-time1'];
    $cliente = $_POST['cliente'];
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];
    $estilo = $_POST['estilo'];
    $tamanho = $_POST['tamanho'];
    $valor = floatval(str_replace(['R$', '.', ','], ['', '', '.'], $_POST['valor']));
    $pagamento = $_POST['pagamento'];
    $sinal_pago = $_POST['sinal_pago'];
    $descricao = $_POST['descricao'];

    $query = $conn->prepare("
        UPDATE agendamentos 
        SET maca_id = ?, data = ?, start_time = ?, end_time = ?,
            nome_cliente = ?, telefone_cliente = ?, email_cliente = ?, estilo = ?, 
            tamanho = ?, valor = ?, forma_pagamento = ?, sinal_pago = ?, descricao = ?
        WHERE id = ?
    ");
    
    $query->bind_param(
        "ssssssssssssss",
        $maca_id,
        $data,
        $start_time,
        $end_time,
        $nome_cliente,
        $telefone_cliente,
        $email_cliente,
        $estilo,
        $tamanho,
        $valor,
        $forma_pagamento,
        $sinal_pago,
        $descricao,
        $id
    );

    if ($query->execute()) {
        $_SESSION['status'] = 'success';
        $_SESSION['message'] = 'Agendamento atualizado com sucesso.';
    } else {
        $_SESSION['status'] = 'error';
        $_SESSION['message'] = 'Erro ao atualizar o agendamento.';
    }

    header('Location: ../horarios_agendados.php');
    exit();
}
