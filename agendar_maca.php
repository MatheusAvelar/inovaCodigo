<?php
// Definindo variáveis para mensagem de retorno
$status = "";
$message = "";

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
$name = $_POST['name1'];
$maca = $_POST['maca'];
$date = $_POST['date1'];
$startTime = $_POST['start-time1'];
$endTime = $_POST['end-time1'];

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
if ($startTime >= $endTime) {
    $errors[] = "O horário final deve ser maior que o horário inicial.";
}

// Se não houver erros, inserir no banco de dados
if (empty($errors)) {
    // Prevenção contra SQL Injection
    $stmt = $conn->prepare("INSERT INTO agendamentos (nome_cliente, maca_id, data, start_time, end_time) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $maca, $date, $startTime, $endTime);
    
    if ($stmt->execute()) {
        $status = "success";
        $message = "Agendamento realizado com sucesso!";
    } else {
        $status = "error";
        $message = "Erro ao realizar o agendamento. Tente novamente.";
    }
    
    $stmt->close();
} else {
    $status = "error";
    $message = implode("<br>", $errors);
}

// Fechando a conexão
$conn->close();

// Redirecionando de volta para a página principal com a mensagem de status
header("Location: teste.php?status=$status&message=" . urlencode($message));
exit();
?>