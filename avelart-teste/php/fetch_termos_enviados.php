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

// Verifica se há um filtro aplicado
$cliente_nome = isset($_GET['cliente_nome']) ? trim($_GET['cliente_nome']) : '';
$filter_status = isset($_GET['filter_status']) ? trim($_GET['filter_status']) : '';
$filter_tatuador = isset($_GET['filter_tatuador']) ? trim($_GET['filter_tatuador']) : '';
$filter_month = isset($_GET['filter_month']) ? trim($_GET['filter_month']) : '';

// Configuração de Paginação
$perPage = 50;
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($currentPage - 1) * $perPage;

$whereClause = "WHERE 1 = 1";

$sql = "SELECT 
            id, 
            CONCAT(UPPER(SUBSTRING(nome_cliente, 1, 1)), LOWER(SUBSTRING(nome_cliente, 2))) AS nome_cliente, 
            email_cliente, 
            data_envio 
        FROM termos_enviados 
        $whereClause";

if($perfilUsuario != 2){
    $whereClause .= "AND usuario_id = $usuarioLogado";
}

if (!empty($filter_status)) {
    $whereClause = "AND status = '" . $filter_status . "'";
}

// Adiciona paginação
$sql .= " LIMIT $perPage OFFSET $offset";
echo "Consulta 0: $sql";
// Prepara a declaração SQL
$stmt = $conn->prepare($sql);

// Vincula os parâmetros dinamicamente
$params = [];
$types = ""; // String que define os tipos dos parâmetros para o bind_param

if (!empty($cliente_nome)) {
    $params[] = "%" . $cliente_nome . "%";
    $types .= "s"; // Tipo string
}
if (!empty($filter_status)) {
    $params[] = $filter_status;
    $types .= "s"; // Tipo string
}
if (!empty($filter_tatuador)) {
    $params[] = "%" . $filter_tatuador . "%";
    $types .= "s"; // Tipo string
}
if (!empty($filter_month)) {
    $params[] = $filter_month;
    $types .= "i"; // Tipo inteiro
}

// Vincula os parâmetros, se existirem
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

// Obter a contagem total de registros
$total_records = $result->num_rows;

if ($total_records > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['nome_cliente']) . "</td>";
        echo "<td>" . htmlspecialchars($row['email_cliente']) . "</td>";
        echo "<td>" . htmlspecialchars(date('d/m/Y', strtotime($row['data_envio']))) . "</td>";
        echo "<td><a href='php/visualizar_termo.php?id=" . $row['id'] . "' target='_blank'>Visualizar</a></td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='5'>Nenhum termo encontrado.</td></tr>";
}

// Obter a contagem total de registros
$total_records_sql = "SELECT COUNT(*) FROM termos_enviados WHERE status = 'ativo'";
if ($perfilUsuario != 2) {
    $total_records_sql .= " AND usuario_id = $usuarioLogado";
}
if (!empty($cliente_nome)) {
    $total_records_sql .= " AND nome_cliente LIKE '%$cliente_nome%'";
}
if (!empty($filter_status)) {
    $total_records_sql .= " AND status = '$filter_status'";
}
if (!empty($filter_tatuador)) {
    $total_records_sql .= " AND nome_tatuador LIKE '%$filter_tatuador%'";
}
if (!empty($filter_month)) {
    $total_records_sql .= " AND MONTH(data_envio) = '$filter_month'";
}

echo "Consulta 2: $total_records_sql";
$total_result = $conn->query($total_records_sql);
$totalRecords = $total_result->fetch_row()[0];

// Calcular o número total de páginas
$totalPages = ceil($totalRecords / $perPage);

// Consulta principal para buscar os registros com paginação
$result = $conn->query($sql);

// Obtém o número de registros na página atual
$totalRecordsCurrentPage = $result->num_rows;

$stmt->close();
$conn->close();
?>