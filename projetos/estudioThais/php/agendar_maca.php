<?php
session_start();

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
$valor = str_replace(['R$ ', '.'], ['', '.'], $_POST['valor']) ?? ''; // Remove R$ e formata para decimal
$formaPagamento = $_POST['pagamento'] ?? '';
$sinalPago = $_POST['sinal_pago'] ?? '';
$descricao = $_POST['descricao'] ?? '';
$maca = $_POST['maca'] ?? '';
$date = $_POST['date1'] ?? '';
$startTime = $_POST['start-time1'] ?? '';
$endTime = $_POST['end-time1'] ?? '';

echo "Nome Cliente: $nomeCliente<br>";
echo "Estilo: $estilo<br>";
echo "Tamanho: $tamanho<br>";
echo "Valor Recebido: " . $_POST['valor'] . "<br>";
echo "Valor Formatado: ".var_dump($valor)."<br>";

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

            if (!$stmt->execute()) {
                echo "Erro na inserção: " . $stmt->error . "<br>";
            } else {
                $status = "success";
                $message = "Agendamento realizado com sucesso!";
            }
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
    //window.location.href = '../agendamento.php';
</script>";
?>
