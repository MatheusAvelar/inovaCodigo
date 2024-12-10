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

// Calculando a página atual e a quantidade de registros por página
$records_per_page = 50; // Quantidade de registros por página
$page_number = isset($_GET['page']) ? $_GET['page'] : 1; // Página atual, default é 1
$offset = ($page_number - 1) * $records_per_page; // Deslocamento para o LIMIT

// Contagem total de registros com GROUP BY
$total_records_sql = "SELECT COUNT(*) 
                      FROM termos_enviados 
                      WHERE status = 'ativo'";

if ($perfilUsuario != 2) {
    $total_records_sql .= " AND usuario_id = $usuarioLogado";
}

if (!empty($cliente_nome)) {
    $total_records_sql .= " AND nome_cliente LIKE '%$cliente_nome%'";
}

// Aqui, o GROUP BY é importante para contabilizar os registros agrupados por cliente e data
$total_records_sql .= " GROUP BY nome_cliente, email_cliente, DATE(data_envio)";
$total_result = $conn->query($total_records_sql);
var_dump($total_result);
$totalRecords = $total_result->num_rows; // Contagem dos grupos

// Consulta para exibir os termos enviados com base no filtro, incluindo a paginação
$sql = "SELECT id, 
               CONCAT(UPPER(SUBSTRING(nome_cliente, 1, 1)), LOWER(SUBSTRING(nome_cliente, 2))) AS nome_cliente, 
               email_cliente, 
               data_envio 
        FROM termos_enviados 
        WHERE status = 'ativo'";

if ($perfilUsuario != 2) {
    $sql .= " AND usuario_id = $usuarioLogado";
}

if (!empty($cliente_nome)) {
    $sql .= " AND nome_cliente LIKE '%$cliente_nome%'";
}

/*$sql .= " GROUP BY nome_cliente, email_cliente, DATE(data_envio) 
          ORDER BY data_envio DESC 
          LIMIT $offset, $records_per_page";*/ // Aplicando LIMIT para a paginação

$result = $conn->query($sql);

// Obter a contagem de registros na página atual
$totalRecordsCurrentPage = $result->num_rows;

// Exibir os registros
if ($totalRecordsCurrentPage > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['nome_cliente']) . "</td>";
        echo "<td>" . htmlspecialchars($row['email_cliente']) . "</td>";
        echo "<td>" . htmlspecialchars(date('d/m/Y', strtotime($row['data_envio']))) . "</td>";
        echo "<td><a href='php/visualizar_termo.php?id=" . $row['id'] . "' target='_blank'>Visualizar</a></td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='4'>Nenhum termo encontrado.</td></tr>";
}

$stmt->close();
$conn->close();
?>