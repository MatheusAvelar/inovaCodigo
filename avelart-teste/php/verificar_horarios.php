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

// Receber a data do cliente via POST
$data = $_POST['date1'] ?? '';

if (!empty($data)) {
    $sql = "SELECT start_time, end_time FROM agendamentos WHERE data = ? AND status = 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $data);
    $stmt->execute();
    $result = $stmt->get_result();

    $horarios = [];
    while ($row = $result->fetch_assoc()) {
        $horarios[] = "Início: " . $row['start_time'] . " horas, Fim: " . $row['end_time'] . " horas";
    }

    // Retorna os horários formatados como JSON
    echo json_encode($horarios);
}

$conn->close();
?>