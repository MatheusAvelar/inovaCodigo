<?php
header('Content-Type: application/json');
$servername = "127.0.0.1:3306";
$username = "u221588236_root";
$password = "Camila@307";
$dbname = "u221588236_controle_finan";

$mysqli = new mysqli($servername, $username, $password, $dbname);

// Verificar conexão
if ($mysqli->connect_error) {
    echo json_encode(['status' => 'error', 'errors' => ['Erro na conexão com o banco de dados.']]);
    exit();
}

// Receber parâmetros
$nomeCliente = isset($_POST['name1']) ? $mysqli->real_escape_string($_POST['name1']) : '';
$macaId = isset($_POST['maca']) ? $mysqli->real_escape_string($_POST['maca']) : '';
$data = isset($_POST['date1']) ? $mysqli->real_escape_string($_POST['date1']) : '';
$startTime = isset($_POST['start-time1']) ? $mysqli->real_escape_string($_POST['start-time1']) : '';
$endTime = isset($_POST['end-time1']) ? $mysqli->real_escape_string($_POST['end-time1']) : '';

// Validar dados
$errors = [];
if (!$nomeCliente) $errors[] = 'O nome do cliente é obrigatório.';
if (!$macaId) $errors[] = 'O ID da maca é inválido.';
if (!$data) $errors[] = 'A data é obrigatória.';
if (!$startTime) $errors[] = 'O horário inicial é obrigatório.';
if (!$endTime) $errors[] = 'O horário final é obrigatório.';

// Verificar se o horário está disponível
if (empty($errors)) {
    $sql = "SELECT start_time FROM agendamentos WHERE maca_id = ? AND date = ? AND start_time = ?";
    $stmt = $mysqli->prepare($sql);
    if (!$stmt) {
        echo json_encode(['status' => 'error', 'errors' => ['Erro na preparação da consulta.']]);
        exit();
    }
    $stmt->bind_param("sss", $macaId, $data, $startTime);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $errors[] = 'O horário selecionado não está disponível.';
    }
    $stmt->close();
}

// Se não houver erros, inserir o agendamento
if (empty($errors)) {
    $sql = "INSERT INTO agendamentos (name, maca_id, date, start_time, end_time) VALUES (?, ?, ?, ?, ?)";
    $stmt = $mysqli->prepare($sql);
    if (!$stmt) {
        echo json_encode(['status' => 'error', 'errors' => ['Erro na preparação da inserção.']]);
        exit();
    }
    $stmt->bind_param("sssss", $nomeCliente, $macaId, $data, $startTime, $endTime);
    
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'errors' => ['Erro ao agendar.']]);
    }
    
    $stmt->close();
}

// Fechar conexão
$mysqli->close();
?>

// Conexão com o banco de dados
/*$servername = "127.0.0.1:3306";
$username = "u221588236_root";
$password = "Camila@307";
$dbname = "u221588236_controle_finan";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Recebe os dados do formulário
$nome_cliente = $_POST['name'];
$maca_id = $_POST['maca'];
$data = $_POST['date'];
$start_time = $_POST['start_time'];
$end_time = $_POST['end_time'];

// Validações básicas
$errors = [];
if (empty($nome_cliente)) {
    $errors[] = "O nome do cliente é obrigatório.";
}
if (empty($maca_id) || !is_numeric($maca_id)) {
    $errors[] = "O ID da maca é inválido.";
}
if (empty($data)) {
    $errors[] = "A data é obrigatória.";
}
if (empty($start_time)) {
    $errors[] = "O horário inicial é obrigatório.";
}
if (empty($end_time)) {
    $errors[] = "O horário final é obrigatório.";
}

// Verifica se existem erros
if (count($errors) > 0) {
    echo json_encode(["status" => "error", "errors" => $errors]);
    exit();
}

// Verifica se o horário final é posterior ao horário inicial
if (strtotime($end_time) <= strtotime($start_time)) {
    echo json_encode(["status" => "error", "message" => "O horário final deve ser posterior ao horário inicial."]);
    exit();
}

// Verifica se o horário está disponível
$sql = "SELECT * FROM agendamentos WHERE maca_id = ? AND data = ? AND ((start_time <= ? AND end_time > ?) OR (start_time < ? AND end_time >= ?))";
$stmt = $conn->prepare($sql);
$stmt->bind_param("isssss", $maca_id, $data, $start_time, $start_time, $end_time, $end_time);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(["status" => "error", "message" => "O horário selecionado não está disponível."]);
    exit();
}

// Insere o novo agendamento
$sql = "INSERT INTO agendamentos (nome_cliente, maca_id, data, start_time, end_time) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sisss", $nome_cliente, $maca_id, $data, $start_time, $end_time);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Agendamento realizado com sucesso."]);
} else {
    echo json_encode(["status" => "error", "message" => "Erro ao realizar o agendamento."]);
}

$stmt->close();
$conn->close();*/
?>
