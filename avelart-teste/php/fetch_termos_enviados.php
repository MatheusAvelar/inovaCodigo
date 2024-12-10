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
$filter_month = isset($_GET['filter_month']) ? $_GET['filter_month'] : '';
$filter_status = isset($_GET['filter_status']) ? $_GET['filter_status'] : 'ativo';

// Configuração de Paginação
$perPage = 50;
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($currentPage - 1) * $perPage;

$sql = "SELECT 
            id, 
            CONCAT(UPPER(SUBSTRING(nome_cliente, 1, 1)), LOWER(SUBSTRING(nome_cliente, 2))) AS nome_cliente, 
            email_cliente, 
            data_envio 
        FROM termos_enviados 
        WHERE 1"; // Começar com a condição genérica

// Se o perfil não for 2, adiciona a condição de usuario_id
if ($perfilUsuario != 2) {
    $sql .= " AND usuario_id = $usuarioLogado";
}
// Adiciona o filtro de status de acordo com a seleção do filtro
if (!empty($filter_status)) {
    $sql .= " AND status = ?";
} else {
    // Se não houver filtro de status, mostra ambos os status (ativo e inativo)
    $sql .= " AND (status = 'ativo' OR status = 'inativo')";
}
// Adiciona filtros adicionais
if (!empty($cliente_nome)) {
    $sql .= " AND nome_cliente LIKE ?";
}
if (!empty($filter_month)) {
    $sql .= " AND MONTH(data_envio) = ?";
}

$sql .= " LIMIT $perPage OFFSET $offset";

$stmt = $conn->prepare($sql);

$params = [];
$types = "";

// Se houver um filtro, vincule o parâmetro
if (!empty($cliente_nome)) {
    $params[] = "%" . $cliente_nome . "%";
    $types .= "s";
}
if (!empty($filter_month)) {
    $params[] = $filter_month;
    $types .= "i";
}
if (!empty($filter_status)) {
    $params[] = $filter_status;
    $types .= "s";
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
$total_records_sql = "SELECT COUNT(*) FROM termos_enviados WHERE 1 = 1";
if ($perfilUsuario != 2) {
    $total_records_sql .= " AND usuario_id = $usuarioLogado";
}
if (!empty($cliente_nome)) {
    $total_records_sql .= " AND nome_cliente LIKE '%$cliente_nome%'";
}
if (!empty($filter_month)) {
    $total_records_sql .= " AND MONTH(data_envio) = '" . $conn->real_escape_string($filter_month) . "'";
}
if (!empty($filter_status)) {
    $total_records_sql .= "AND status = '" . $conn->real_escape_string($filter_status) . "'";
}

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