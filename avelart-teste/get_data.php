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

// Verifica se os filtros foram aplicados
$filtro_aplicado = !empty($mes) || !empty($ano);

if ($action == 'metricas') {
    // Total de Agendamentos
    $sql_total_agendamentos = "SELECT COUNT(*) as total_agendamentos FROM agendamentos WHERE status = 'ativo'";
    
    if ($filtro_aplicado) {
        $sql_total_agendamentos .= " AND ";
        if (!empty($mes)) {
            $sql_total_agendamentos .= "MONTH(data) = '$mes'";
        }
        if (!empty($ano)) {
            if (!empty($mes)) {
                $sql_total_agendamentos .= " AND ";
            }
            $sql_total_agendamentos .= "YEAR(data) = '$ano'";
        }
    } else {
        // Se não houver filtros, retorna todos do ano atual
        $sql_total_agendamentos .= " AND YEAR(data) = '".date('Y')."'";
    }

    $result_total_agendamentos = $conn->query($sql_total_agendamentos);
    $total_agendamentos = $result_total_agendamentos->fetch_assoc()['total_agendamentos'];

    // Total Faturado
    $sql_total_faturado = "SELECT SUM(valor) as total_faturado FROM agendamentos WHERE status = 'ativo'";
    
    if ($filtro_aplicado) {
        $sql_total_faturado .= " AND ";
        if (!empty($mes)) {
            $sql_total_faturado .= "MONTH(data) = '$mes'";
        }
        if (!empty($ano)) {
            if (!empty($mes)) {
                $sql_total_faturado .= " AND ";
            }
            $sql_total_faturado .= "YEAR(data) = '$ano'";
        }
    } else {
        // Se não houver filtros, retorna todos do ano atual
        $sql_total_faturado .= " AND YEAR(data) = '".date('Y')."'";
    }

    $result_total_faturado = $conn->query($sql_total_faturado);
    $total_faturado = $result_total_faturado->fetch_assoc()['total_faturado'];
    if (is_null($total_faturado)) { $total_faturado = 0; }

    // Total de Cancelamentos
    $sql_total_cancelamentos = "SELECT COUNT(*) as total_cancelamentos FROM agendamentos WHERE status = 'inativo'";
    
    if ($filtro_aplicado) {
        $sql_total_cancelamentos .= " AND ";
        if (!empty($mes)) {
            $sql_total_cancelamentos .= "MONTH(data) = '$mes'";
        }
        if (!empty($ano)) {
            if (!empty($mes)) {
                $sql_total_cancelamentos .= " AND ";
            }
            $sql_total_cancelamentos .= "YEAR(data) = '$ano'";
        }
    } else {
        // Se não houver filtros, retorna todos do ano atual
        $sql_total_cancelamentos .= " AND YEAR(data) = '".date('Y')."'";
    }

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
                            WHERE status = 'ativo'";
    
    if ($filtro_aplicado) {
        $sql_agendamentos .= " AND ";
        if (!empty($mes)) {
            $sql_agendamentos .= "MONTH(data) = '$mes'";
        }
        if (!empty($ano)) {
            if (!empty($mes)) {
                $sql_agendamentos .= " AND ";
            }
            $sql_agendamentos .= "YEAR(data) = '$ano'";
        }
    } else {
        // Se não houver filtros, retorna todos do ano atual
        $sql_agendamentos .= " AND YEAR(data) = '".date('Y')."'";
    }

    $sql_agendamentos .= " GROUP BY mes_agendamento ORDER BY mes_agendamento ASC";
    
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
}  elseif ($action == 'graficos_tatuadores') {
    // Obter agendamentos agrupados por tatuador e mês
    $sql_agendamentos_tatuador = "SELECT CONCAT(UPPER(LEFT(u.nome, 1)), LOWER(SUBSTRING(u.nome, 2))) AS tatuador, DATE_FORMAT(a.data, '%m/%Y') AS mes_agendamento, COUNT(*) AS total_agendamentos 
                                    FROM agendamentos a 
                                    JOIN usuarioEstudio u ON a.usuario_id = u.id 
                                    WHERE a.status = 'ativo'
                                    AND u.id NOT IN (1, 2, 13, 14)"; //Alterar produção

    if ($filtro_aplicado) {
        $sql_agendamentos_tatuador .= " AND ";
        if (!empty($mes)) {
            $sql_agendamentos_tatuador .= "MONTH(a.data) = '$mes'";
        }
        if (!empty($ano)) {
            if (!empty($mes)) {
                $sql_agendamentos_tatuador .= " AND ";
            }
            $sql_agendamentos_tatuador .= "YEAR(a.data) = '$ano'";
        }
    } else {
        // Se não houver filtros, retorna todos do ano atual
        $sql_agendamentos_tatuador .= " AND YEAR(a.data) = '".date('Y')."'";
    }

    $sql_agendamentos_tatuador .= " GROUP BY tatuador, mes_agendamento ORDER BY mes_agendamento ASC";

    $result_agendamentos_tatuador = $conn->query($sql_agendamentos_tatuador);

    $labels_tatuadores = [];
    $tatuadores_data = [];

    while ($row = $result_agendamentos_tatuador->fetch_assoc()) {
        $labels_tatuadores[] = $row['mes_agendamento'] . ' - ' . $row['tatuador'];
        $tatuadores_data[] = $row['total_agendamentos'];
    }

    // Retornar os dados em JSON
    echo json_encode([
        'labels' => $labels_tatuadores,
        'tatuadores' => $tatuadores_data
    ]);
} else {
    echo json_encode(['error' => 'Ação inválida']);
}

$conn->close();
