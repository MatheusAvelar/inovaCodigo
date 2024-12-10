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

// Configuração de Paginação
$perPage = 50;
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($currentPage - 1) * $perPage;
$whereClause = "WHERE 1=1";

// Adiciona o filtro de status se o perfil não for 2
if ($perfilUsuario == 2) {
    $whereClause .= " AND status = 'ativo'";
} else {
    $whereClause .= " AND usuario_id = ?";
}

// Se houver um filtro, adicione uma condição na consulta
if (!empty($cliente_nome)) {
    $whereClause .= " AND nome_cliente LIKE ?";
}

$whereClause .= " GROUP BY nome_cliente, email_cliente, DATE(data_envio) ORDER BY data_envio DESC";

// Prepara a consulta principal
$sql = "SELECT 
            id, 
            CONCAT(UPPER(SUBSTRING(nome_cliente, 1, 1)), LOWER(SUBSTRING(nome_cliente, 2))) AS nome_cliente, 
            email_cliente, 
            data_envio 
        FROM termos_enviados
        $whereClause
        LIMIT ? OFFSET ?";

$stmt = $conn->prepare($sql);

// Se houver um filtro, vincule os parâmetros
if (!empty($cliente_nome)) {
    $param = "%" . $cliente_nome . "%";
    if ($perfilUsuario == 2) {
        $stmt->bind_param("ssssii", $param, $usuarioLogado, $param, $perPage, $offset);
    } else {
        $stmt->bind_param("ssii", $usuarioLogado, $param, $perPage, $offset);
    }
} else {
    if ($perfilUsuario == 2) {
        $stmt->bind_param("ssii", $usuarioLogado, $perPage, $offset);
    } else {
        $stmt->bind_param("ii", $usuarioLogado, $perPage, $offset);
    }
}

// Executa a consulta
$stmt->execute();
$result = $stmt->get_result();

// Obter a contagem total de registros
$total_records_sql = "SELECT COUNT(*) FROM termos_enviados WHERE 1=1";

if ($perfilUsuario == 2) {
    $total_records_sql .= " AND status = 'ativo'";
} else {
    $total_records_sql .= " AND usuario_id = ?";
}

if (!empty($cliente_nome)) {
    $total_records_sql .= " AND nome_cliente LIKE ?";
}

// Prepara a consulta para contagem de registros
$total_stmt = $conn->prepare($total_records_sql);

if (!empty($cliente_nome)) {
    $param = "%" . $cliente_nome . "%";
    if ($perfilUsuario == 2) {
        $total_stmt->bind_param("ss", $usuarioLogado, $param);
    } else {
        $total_stmt->bind_param("s", $param);
    }
} else {
    if ($perfilUsuario == 2) {
        $total_stmt->bind_param("s", $usuarioLogado);
    }
}

// Executa a consulta de contagem
$total_stmt->execute();
$total_result = $total_stmt->get_result();
$totalRecords = $total_result->fetch_row()[0];

// Calcular o número total de páginas
$totalPages = ceil($totalRecords / $perPage);

// Obtém o número de registros na página atual
$totalRecordsCurrentPage = $result->num_rows;

// Exibe os registros encontrados
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
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

$stmt->close();
$total_stmt->close();
$conn->close();