<?php
include 'utils.php';
// Conexão com o banco de dados
$servername = "127.0.0.1:3306";
$username = "u221588236_root";
$password = "Camila@307";
$dbname = "u221588236_controle_finan";

$conn = new mysqli($servername, $username, $password, $dbname);

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

if($perfilUsuario == 2){
    $sql = "SELECT 
                id, 
                CONCAT(UPPER(SUBSTRING(nome_cliente, 1, 1)), LOWER(SUBSTRING(nome_cliente, 2))) AS nome_cliente, 
                email_cliente, 
                data_envio 
            FROM termos_enviados 
            WHERE status = 'ativo'";
} else {
    $sql = "SELECT 
                id,
                CONCAT(UPPER(SUBSTRING(nome_cliente, 1, 1)), LOWER(SUBSTRING(nome_cliente, 2))) AS nome_cliente, 
                email_cliente,
                data_envio 
            FROM termos_enviados 
            WHERE status = 'ativo' 
            AND usuario_id = $usuarioLogado";
}

// Se houver um filtro, adicione uma condição na consulta
if (!empty($cliente_nome)) {
    $sql .= " AND nome_cliente LIKE ?";
}

$sql .= " LIMIT $perPage OFFSET $offset";

$stmt = $conn->prepare($sql);

// Se houver um filtro, vincule o parâmetro
if (!empty($cliente_nome)) {
    $param = "%" . $cliente_nome . "%";
    $stmt->bind_param("s", $param);
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