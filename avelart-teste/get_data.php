<?php
include 'php/utils.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    $conn = conectaBanco();
} catch (Exception $e) {
    die("Erro: " . $e->getMessage());
}

// Definir o tipo de ação
$action = isset($_GET['action']) ? $_GET['action'] : '';
$mes = isset($_GET['mes']) ? sanitize($_GET['mes']) : '';
$ano = isset($_GET['ano']) ? sanitize($_GET['ano']) : date('Y');

// Função para sanitizar entrada
function sanitize($data) {
    return htmlspecialchars(strip_tags($data));
}

if ($action == 'metricas') {
    // Total de Agendamentos
    $sql_total_agendamentos = "SELECT COUNT(*) as total_agendamentos FROM agendamentos WHERE status = 'ativo' AND MONTH(data) = '$mes' AND YEAR(data) = '$ano'";
    $result_total_agendamentos = $conn->query($sql_total_agendamentos);
    $total_agendamentos = $result_total_agendamentos->fetch_assoc()['total_agendamentos'];
    
    // Total Faturado
    $sql_total_faturado = "SELECT SUM(valor) as total_faturado FROM agendamentos WHERE status = 'ativo' AND MONTH(data) = '$mes' AND YEAR(data) = '$ano'";
    $result_total_faturado = $conn->query($sql_total_faturado);
    $total_faturado = $result_total_faturado->fetch_assoc()['total_faturado'];
    if (is_null($total_faturado)) { $total_faturado = 0; }
    
    // Total de Cancelamentos
    $sql_total_cancelamentos = "SELECT COUNT(*) as total_cancelamentos FROM agendamentos WHERE status = 'inativo' AND MONTH(data) = '$mes' AND YEAR(data) = '$ano'";
    $result_total_cancelamentos = $conn->query($sql_total_cancelamentos);
    $total_cancelamentos = $result_total_cancelamentos->fetch_assoc()['total_cancelamentos'];
    
    // Retornar os dados em JSON
    echo json_encode([
        'total_agendamentos' => $total_agendamentos,
        'total_faturado' => $total_faturado,
        'total_cancelamentos' => $total_cancelamentos
    ]);
    
} elseif ($action == 'graficos') {
    // Obter agendamentos agrupados por mês
    $sql_agendamentos = "SELECT DATE_FORMAT(data, '%m/%Y') as mes_agendamento, COUNT(*) as total_agendamentos 
                            FROM agendamentos 
                            WHERE status = 'ativo' AND MONTH(data) = '$mes' AND YEAR(data) = '$ano' 
                            GROUP BY mes_agendamento 
                            ORDER BY mes_agendamento ASC";
    $result_agendamentos = $conn->query($sql_agendamentos);

    $labels = [];
    $agendamentos = [];

    while ($row = $result_agendamentos->fetch_assoc()) {
        $labels[] = $row['mes_agendamento'];
        $agendamentos[] = $row['total_agendamentos'];
    }

    // Retornar os dados em JSON
    echo json_encode([
        'labels' => $labels,
        'agendamentos' => $agendamentos
    ]);
} else {
    echo json_encode(['error' => 'Ação inválida']);
}

$conn->close();