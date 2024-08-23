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

// Comando ALTER TABLE para adicionar os campos
$sql = "SHOW COLUMNS FROM termos_enviados";

// Executar o comando ALTER TABLE
$resultado = executarSQL($conn, $sql);

// Exibir resultado
echo $resultado;

$sql = "SELECT nome_cliente, email_cliente, data_envio, status FROM termos_enviados ORDER BY data_envio DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['cliente_nome']) . "</td>";
        echo "<td>" . htmlspecialchars($row['cliente_email']) . "</td>";
        echo "<td>" . htmlspecialchars(date('d/m/Y H:i:s', strtotime($row['data_envio']))) . "</td>";
        echo "<td>" . htmlspecialchars($row['status']) . "</td>";
        echo "<td><a href='visualizar_termo.php?id=" . $row['id'] . "'>Visualizar</a></td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='5'>Nenhum termo encontrado.</td></tr>";
}

$conn->close();
?>
