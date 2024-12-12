<?php
include 'utils.php';

try {
    $conn = conectaBanco();
} catch (Exception $e) {
    die("Erro: " . $e->getMessage());
}

$usuarioLogado = $_SESSION['id'];
$perfilUsuario = $_SESSION['perfil_id'];

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Verifica se a data foi fornecida
if (empty($_POST['data'])) {
    echo json_encode(["erro" => "Data não fornecida"]);
    exit;
}

// Sanitiza o parâmetro
$data = $conn->real_escape_string($_POST['data']);

// Consulta os horários
$query = "SELECT start_time, end_time FROM agendamentos WHERE data = '$data' AND stats = 1";
$result = $conn->query($query);

if ($result) {
    $horarios = [];
    while ($row = $result->fetch_assoc()) {
        $horarios[] = "Inicio: {$row['start_time']} horas | Fim: {$row['end_time']} horas";
    }
    echo json_encode($horarios);
} else {
    echo json_encode(["erro" => "Erro ao consultar o banco de dados"]);
}

$conn->close();