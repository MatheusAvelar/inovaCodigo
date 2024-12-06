<?php
include 'utils.php';

// Habilitar exibição de erros para debug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    $conn = conectaBanco();
} catch (Exception $e) {
    die("Erro ao conectar ao banco: " . $e->getMessage());
}

// Inicializar variáveis para filtros
$filterDate = isset($_GET['filter_date']) ? $_GET['filter_date'] : '';
$filterMaca = isset($_GET['filter_maca']) ? $_GET['filter_maca'] : '';
$filterTatuador = isset($_GET['filter_tatuador']) ? $_GET['filter_tatuador'] : '';
$filterMonth = isset($_GET['filter_month']) ? $_GET['filter_month'] : '';
$inicio = isset($_GET['inicio']) ? $_GET['inicio'] : '';
$fim = isset($_GET['fim']) ? $_GET['fim'] : '';

// Criar query base
$query = "SELECT 
                CONCAT(UCASE(LEFT(ue.nome, 1)), LCASE(SUBSTRING(ue.nome, 2)), ' ', 
                        UCASE(LEFT(ue.sobrenome, 1)), LCASE(SUBSTRING(ue.sobrenome, 2))) AS nome_completo,
                SUM(a.valor) AS valor
            FROM usuarioEstudio ue
            JOIN agendamentos a ON ue.id = a.usuario_id
            WHERE a.status = 1";

// Adicionar filtros dinâmicos
$params = [];
/*if ($filterMonth) {
    $query .= " AND MONTH(a.data) = ?";
    $params[] = $filterMonth;
}*/
if ($filterMaca) {
    $query .= " AND a.maca_id = ?";
    $params[] = $filterMaca;
}
if ($filterTatuador) {
    $query .= " AND a.usuario_id = ?";
    $params[] = $filterTatuador;
}
if ($inicio) {
    $query .= " AND a.data >= ?";
    $params[] = $inicio;
}
if ($fim) {
    $query .= " AND a.data <= ?";
    $params[] = $fim;
}

//$query .= " ORDER BY a.nome DESC";

try {
    $stmt = $conn->prepare($query);
    
    // Vincular parâmetros dinamicamente
    if ($params) {
        $types = str_repeat('s', count($params)); // Define o tipo como string para todos os parâmetros
        $stmt->bind_param($types, ...$params);
    }

    // Executar consulta
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['nome_completo']) . "</td>";
            echo "<td> R$ " . number_format($row['valor'], 2, ',', '.') . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='2'>Nenhum agendamento encontrado. Verifique os filtros aplicados.</td></tr>";
    }
    
} catch (Exception $e) {
    die("Erro ao executar consulta: " . $e->getMessage());
}

// Fechar a conexão
$conn->close();
?>
