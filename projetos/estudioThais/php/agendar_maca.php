<?php
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

// Verificação de conflito de horário
if (empty($errors)) {
    $stmt = $conn->prepare("SELECT start_time, end_time FROM agendamentos WHERE maca_id = ? AND data = ? AND ((start_time <= ? AND end_time > ?) OR (start_time < ? AND end_time >= ?))");
    $stmt->bind_param("ssssss", $maca, $date, $startTime, $startTime, $endTime, $endTime);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($existingStartTime, $existingEndTime);
        $stmt->fetch();
        $status = "error";
        $message = "Já existe um agendamento para a maca e data selecionadas com conflito de horário: de $existingStartTime às $existingEndTime.";
        $stmt->close();
    } else {
        // Inserir no banco de dados se não houver conflitos
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
    }
} else {
    $status = "error";
    $message = implode("<br>", $errors);
}

// Busca de agendamentos existentes
$agendamentos = [];
$query = "SELECT nome_cliente, maca_id, data, start_time, end_time FROM agendamentos ORDER BY data, start_time";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $agendamentos[] = $row;
    }
}

// Fechando a conexão
$conn->close();

// Armazenando a mensagem em sessionStorage e redirecionando
echo "<script>
    sessionStorage.setItem('status', '" . addslashes($status) . "');
    sessionStorage.setItem('message', '" . addslashes($message) . "');
    window.location.href = 'projetos/estudioThais/agendamento.php';
</script>";
?>
