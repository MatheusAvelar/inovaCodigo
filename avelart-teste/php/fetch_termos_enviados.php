<?php
include 'utils.php';

try {
    $conn = conectaBanco();
} catch (Exception $e) {
    die("Erro: " . $e->getMessage());
}

$usuarioLogado = $_SESSION['id'];
$perfilUsuario = $_SESSION['perfil_id'];

// Verifica se há um filtro aplicado
$cliente_nome = isset($_GET['cliente_nome']) ? trim($_GET['cliente_nome']) : '';

if($perfilUsuario == 2){
    $sql = "SELECT id, nome_cliente, email_cliente, data_envio FROM termos_enviados WHERE status = 'ativo'";
} else {
    $sql = "SELECT id, nome_cliente, email_cliente, data_envio FROM termos_enviados WHERE status = 'ativo' AND usuario_id = ?";
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
    if($perfilUsuario == 2){
        $stmt->bind_param("s", $param);
    } else {
        $stmt->bind_param("is", $usuarioLogado, $param);
    }
} else {
    if($perfilUsuario != 2){
        $stmt->bind_param("i", $usuarioLogado);
    }
}


// Consulta para contar o número total de registros ativos
$sqlCount = "SELECT COUNT(*) as total FROM sua_tabela";
$count_result = $conn->query($sqlCount);

$total_records = 0;
if ($count_result && $row = $count_result->fetch_assoc()) {
    $total_records = $row['total'];
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
