<?php
include 'utils.php';

try {
    $conn = conectaBanco('./.env');
} catch (Exception $e) {
    die("Erro: " . $e->getMessage());
}

$macaId = $_GET['maca'];
$date = $_GET['date'];

// Verifica se os parâmetros foram recebidos corretamente
if (!$macaId || !$date) {
    echo json_encode([]);
    exit();
}

// Consultar horários ocupados
$sql = "SELECT start_time, end_time FROM agendamentos WHERE maca_id = ? AND data = ? AND status = '1'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $macaId, $date);
$stmt->execute();
$result = $stmt->get_result();

$ocupados = [];
while($row = $result->fetch_assoc()) {
    $start = $row['start_time'];
    $end = $row['end_time'];
    $current = $start;

    while ($current != $end) {
        $ocupados[] = $current;
        // Incrementa a hora em 1
        $time = strtotime($current) + 3600;
        $current = date('H:i', $time);
    }
}

$stmt->close();
$conn->close();

// Retorna horários ocupados como JSON
header('Content-Type: application/json');
echo json_encode($ocupados);
?>