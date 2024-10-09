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
$periodo = isset($_GET['periodo']) ? intval($_GET['periodo']) : 30;

// Função para sanitizar entrada (opcional, mas recomendada)
function sanitize($data) {
    return htmlspecialchars(strip_tags($data));
}

$periodo = sanitize($periodo);

if ($action == 'metricas') {
    // Total de Agendamentos
    $sql_total_agendamentos = "SELECT COUNT(*) as total_agendamentos FROM agendamentos WHERE status = 'ativo' AND data >= CURDATE() - INTERVAL $periodo DAY";
    $result_total_agendamentos = $conn->query($sql_total_agendamentos);
    $total_agendamentos = $result_total_agendamentos->fetch_assoc()['total_agendamentos'];
    
    // Total Faturado
    $sql_total_faturado = "SELECT SUM(valor) as total_faturado FROM agendamentos WHERE status = 'ativo' AND data >= CURDATE() - INTERVAL $periodo DAY";
    $result_total_faturado = $conn->query($sql_total_faturado);
    $total_faturado = $result_total_faturado->fetch_assoc()['total_faturado'];
    if (is_null($total_faturado)) { $total_faturado = 0; }
    
    // Agendamentos por Tatuador (exemplo: apenas um tatuador para simplificação)
    $sql_agendamentos_tatuador = "SELECT u.nome AS tatuador, COUNT(a.id) AS total FROM agendamentos a JOIN usuarioEstudio u ON a.usuario_id = u.id WHERE a.status = 'ativo' AND a.data >= CURDATE() - INTERVAL $periodo DAY GROUP BY a.usuario_id ORDER BY total DESC LIMIT 1";
    $result_agendamentos_tatuador = $conn->query($sql_agendamentos_tatuador);
    $agendamentos_tatuador = "Nenhum agendamento";
    if ($result_agendamentos_tatuador->num_rows > 0) {
        $row = $result_agendamentos_tatuador->fetch_assoc();
        $agendamentos_tatuador = $row['tatuador'] . ' (' . $row['total'] . ')';
    }
    
    // Total de Cancelamentos
    $sql_total_cancelamentos = "SELECT COUNT(*) as total_cancelamentos FROM agendamentos WHERE status = 'cancelado' AND data >= CURDATE() - INTERVAL $periodo DAY";
    $result_total_cancelamentos = $conn->query($sql_total_cancelamentos);
    $total_cancelamentos = $result_total_cancelamentos->fetch_assoc()['total_cancelamentos'];
    
    // Retornar os dados em JSON
    echo json_encode([
        'total_agendamentos' => $total_agendamentos,
        'total_faturado' => $total_faturado,
        'agendamentos_tatuador' => $agendamentos_tatuador,
        'total_cancelamentos' => $total_cancelamentos
    ]);
    
} elseif ($action == 'graficos') {
    // Obter agendamentos agrupados por data
    $sql_agendamentos = "SELECT DATE_FORMAT(data, '%d/%m') as data_agendamento, COUNT(*) as total_agendamentos 
                         FROM agendamentos 
                         WHERE status = 'ativo' AND data >= CURDATE() - INTERVAL $periodo DAY 
                         GROUP BY data_agendamento 
                         ORDER BY data_agendamento ASC";
    $result_agendamentos = $conn->query($sql_agendamentos);
    
    $labels = [];
    $agendamentos = [];
    
    while ($row = $result_agendamentos->fetch_assoc()) {
        $labels[] = $row['data_agendamento'];
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
?>
