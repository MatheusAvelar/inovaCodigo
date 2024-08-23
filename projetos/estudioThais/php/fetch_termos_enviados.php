<?php
include 'utils.php';
// Conexão com o banco de dados
$servername = "127.0.0.1:3306";
$username = "u221588236_root";
$password = "Camila@307";
$dbname = "u221588236_controle_finan";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Verifica se há um filtro aplicado
$cliente_nome = isset($_GET['cliente_nome']) ? trim($_GET['cliente_nome']) : '';

$sql = "SELECT id, nome_cliente, email_cliente, data_envio FROM termos_enviados WHERE status = 'ativo'";

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
        echo "<td>" . htmlspecialchars(date('d/m/Y H:i:s', strtotime($row['data_envio']))) . "</td>";
        echo "<td><a href='php/visualizar_termo.php?id=" . $row['id'] . "' target='_blank'>Visualizar</a></td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='5'>Nenhum termo encontrado.</td></tr>";
}

$stmt->close();
$conn->close();
?>