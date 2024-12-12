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
if (empty($_POST['date1'])) {
    echo json_encode(["erro" => "Data não fornecida"]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['date1'])) {
    $date = $_POST['date1'];

    $query = "SELECT maca_id, start_time, end_time FROM agendamentos WHERE data = '$date' AND status = 1";
    $result = $conn->query($query);
    echo $query;
    if ($result->num_rows > 0) {
        $horarios = [];
        while ($row = $result->fetch_assoc()) {
            // Formata os horários
            $horarios[] = [
                'maca' => $row['maca_id'],
                'start_time' => date('H:i', strtotime($row['start_time'])),
                'end_time' => date('H:i', strtotime($row['end_time'])),
            ];
        }
        return json_encode(['sucesso' => true, 'horarios' => $horarios]);
    } else {
        return json_encode(['sucesso' => false, 'mensagem' => 'Nenhum horário agendado nesta data.']);
    }
} else {
    return json_encode(['erro' => 'Parâmetro "date1" não encontrado.']);
}


$conn->close();