<?php
include 'utils.php';

// Configuração da conexão com o banco de dados
$servername = "127.0.0.1:3306";
$username = "u221588236_root";
$password = "Camila@307";
$dbname = "u221588236_controle_finan";

// Criando a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

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

    // Proteção contra injeção de SQL - Usando prepared statements
    $stmt = $conn->prepare("SELECT maca_id, start_time, end_time FROM agendamentos WHERE data = ? AND status = 1");
    $stmt->bind_param("s", $date); // "s" é o tipo da variável (string)
    $stmt->execute();
    $result = $stmt->get_result();

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
        // Envia a resposta JSON com os horários
        echo json_encode(['sucesso' => true, 'horarios' => $horarios]);
    } else {
        // Envia a resposta JSON informando que não há horários agendados
        echo json_encode(['sucesso' => false, 'mensagem' => 'Nenhum horário agendado nesta data.']);
    }
} else {
    // Envia a resposta JSON informando que o parâmetro 'date1' não foi fornecido
    echo json_encode(['erro' => 'Parâmetro "date1" não encontrado.']);
}

$conn->close();