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

if($perfilUsuario == 2){
    // Consulta para contar o número total de registros ativos
    $count_query = "SELECT COUNT(*) AS total FROM termos_enviados WHERE status = 'ativo'";
    $count_result = $mysqli->query($count_query);
    $total_records = 0;
    if ($count_result) {
        $total_records = $count_result;
    }

    $sql = "SELECT 
                id, 
                CONCAT(UPPER(SUBSTRING(nome_cliente, 1, 1)), LOWER(SUBSTRING(nome_cliente, 2))) AS nome_cliente, 
                email_cliente, 
                data_envio 
            FROM termos_enviados 
            WHERE status = 'ativo'";
} else {
    // Consulta para contar o número total de registros ativos
    $count_query = "SELECT COUNT(*) AS total FROM termos_enviados WHERE status = 'ativo' AND usuario_id = $usuarioLogado";
    $count_result = $mysqli->query($count_query);
    $total_records = 0;
    if ($count_result) {
        $total_records = $count_result;
    }

    $sql = "SELECT 
                id,
                CONCAT(UPPER(SUBSTRING(nome_cliente, 1, 1)), LOWER(SUBSTRING(nome_cliente, 2))) AS nome_cliente,email_cliente,
                data_envio 
            FROM termos_enviados 
            WHERE status = 'ativo' 
            AND usuario_id = $usuarioLogado";
}

// Se houver um filtro, adicione uma condição na consulta
if (!empty($cliente_nome)) {
    $sql .= " AND nome_cliente LIKE ?";
}

$sql .= " ORDER BY data_envio DESC";

$stmt = $conn->prepare($sql);

// Se houver um filtro, vincule o parâmetro
if (!empty($cliente_nome)) {
    $param = "%" . $cliente_nome . "%";
    $stmt->bind_param("s", $param);
}

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
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

$stmt->close();
$conn->close();
?>