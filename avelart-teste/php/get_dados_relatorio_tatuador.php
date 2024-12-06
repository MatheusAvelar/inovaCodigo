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
$filter_month = $_GET['filter_month'] ?? '';
$filter_maca = $_GET['filter_maca'] ?? '';
$filter_tatuador = $_GET['filter_tatuador'] ?? '';
$filter_status = $_GET['filter_status'] ?? '';
$inicio = $_GET['inicio'] ?? '';
$fim = $_GET['fim'] ?? '';

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
if ($filter_month) {
    $query .= " AND MONTH(a.data) = ?";
    $params[] = $filter_month;
}
if ($filter_maca) {
    $query .= " AND a.maca_id = ?";
    $params[] = $filter_maca;
}
if ($filter_tatuador) {
    $query .= " AND a.usuario_id = ?";
    $params[] = $filter_tatuador;
}
if ($inicio) {
    $query .= " AND a.data >= ?";
    $params[] = $inicio;
}
if ($fim) {
    $query .= " AND a.data <= ?";
    $params[] = $fim;
}
if ($filter_status !== '') {
    $query .= " AND a.status = ?";
    $params[] = $filter_status;
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

    // Coletar resultados em um array
    $results = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['nome_completo']) . "</td>";
            echo "<td>" . number_format($row['valor'], 2, ',', '.') . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='7'>Nenhum agendamento encontrado.</td></tr>";
    }
} catch (Exception $e) {
    die("Erro ao executar consulta: " . $e->getMessage());
}

// Fechar a conexão
$conn->close();
?>
