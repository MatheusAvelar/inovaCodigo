<?php
// Conexão com o banco de dados
$servername = "127.0.0.1:3306";
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
$maca_id = $_POST['maca_id'];
$data = $_POST['data'];
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
$conn->close();
?>
