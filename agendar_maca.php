<?php
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

// Capturando dados do formulário
$name = $_POST['name1'] ?? '';
$maca = $_POST['maca'] ?? '';
$date = $_POST['date1'] ?? '';
$startTime = $_POST['start-time1'] ?? '';
$endTime = $_POST['end-time1'] ?? '';

// Validação dos dados
$errors = [];
if (empty($name)) {
    $errors[] = "O nome é obrigatório.";
}
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

// Se não houver erros, verificar conflitos de horário
if (empty($errors)) {
    // Verificar se já existe um agendamento na mesma maca e data com conflito de horário
    $sql = "SELECT start_time, end_time FROM agendamentos 
            WHERE maca_id = ? AND data = ? 
            AND ((start_time < ? AND end_time > ?) 
            OR (start_time < ? AND end_time > ?)
            OR (start_time >= ? AND start_time < ?))";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issssss", $maca, $date, $endTime, $startTime, $startTime, $endTime, $startTime, $endTime);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Construir a mensagem de erro com os horários conflitantes
        $conflictingTimes = [];
        while ($row = $result->fetch_assoc()) {
            $conflictingTimes[] = $row['start_time'] . " às " . $row['end_time'];
        }
        $conflictingTimesList = implode(", ", $conflictingTimes);
        $status = "error";
        $message = "Já existe um agendamento para a maca e data selecionadas com conflito de horário: " . $conflictingTimesList . ".";
    } else {
        // Se não houver conflito, inserir o novo agendamento
        $stmt = $conn->prepare("INSERT INTO agendamentos (nome_cliente, maca_id, data, start_time, end_time) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $maca, $date, $startTime, $endTime);
        
        if ($stmt->execute()) {
            $status = "success";
            $message = "Agendamento realizado com sucesso!";
        } else {
            $status = "error";
            $message = "Erro ao realizar o agendamento. Tente novamente.";
        }
    }
    
    $stmt->close();
} else {
    $status = "error";
    $message = implode("<br>", $errors);
}

// Fechando a conexão
$conn->close();
?>