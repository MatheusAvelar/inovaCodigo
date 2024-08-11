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
    $maca_id = intval($_POST['maca']);
    $data = $_POST['date1'];
    $start_time = $_POST['start-time1'];
    $end_time = $_POST['end-time1'];
    $nome_cliente = $_POST['cliente'];
    $telefone_cliente = $_POST['telefone'];
    $email_cliente = $_POST['email'];
    $estilo = $_POST['estilo'];
    $tamanho = $_POST['tamanho'];
    $valor = floatval(str_replace(['R$', '.', ','], ['', '', '.'], $_POST['valor']));
    $forma_pagamento = $_POST['pagamento'];
    $sinal_pago = $_POST['sinal_pago'];
    $descricao = $_POST['descricao'];

    // Verificar se há um conflito de horário
    $conflictQuery = $conn->prepare("
        SELECT start_time, end_time 
        FROM agendamentos 
        WHERE maca_id = ? 
        AND data = ? 
        AND id != ? 
        AND (
            (start_time <= ? AND end_time > ?) OR 
            (start_time < ? AND end_time >= ?) OR 
            (start_time >= ? AND start_time < ?)
        )
    ");
    
    $conflictQuery->bind_param(
        "sssssssss",
        $maca_id,
        $data,
        $id,
        $start_time,
        $start_time,
        $end_time,
        $end_time,
        $start_time,
        $end_time
    );
    
    $conflictQuery->execute();
    $conflictQuery->store_result();

    if ($conflictQuery->num_rows > 0) {
        $conflictQuery->bind_result($existingStartTime, $existingEndTime);
        $conflictQuery->fetch();
        
        $_SESSION['status'] = 'error';
        $_SESSION['message'] = "Já existe um agendamento para a maca e data selecionadas com conflito de horário: de $existingStartTime às $existingEndTime.";
    } else {
        // Não há conflito, realizar a atualização
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
    }

    header('Location: ../horarios_agendados.php');
    exit();
}
