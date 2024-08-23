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
$sql = "-- Excluir a tabela existente
DROP TABLE IF EXISTS termos_enviados;

-- Criar a tabela novamente com os novos campos
CREATE TABLE termos_enviados (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    data_envio DATETIME NOT NULL,
    conteudo TEXT NOT NULL,
    status ENUM('ativo', 'inativo') NOT NULL,
    nome_responsavel VARCHAR(255) NOT NULL,
    rg_responsavel VARCHAR(20) NOT NULL,
    cpf_responsavel VARCHAR(14) NOT NULL,
    nascimento_responsavel DATE NOT NULL,
    nome_cliente VARCHAR(255) NOT NULL,
    email_cliente VARCHAR(255) NOT NULL,
    rg_cliente VARCHAR(20) NOT NULL,
    cpf_cliente VARCHAR(14) NOT NULL,
    nascimento_cliente DATE NOT NULL,
    local_tatuagem VARCHAR(255) NOT NULL,
    data_tatuagem DATE NOT NULL,
    nome_tatuador VARCHAR(255) NOT NULL,
    cicatrizacao ENUM('sim', 'nao') NOT NULL,
    desmaio ENUM('sim', 'nao') NOT NULL,
    hemofilico ENUM('sim', 'nao') NOT NULL,
    hepatite ENUM('sim', 'nao') NOT NULL,
    hiv ENUM('sim', 'nao') NOT NULL,
    autoimune ENUM('sim', 'nao') NOT NULL,
    epileptico ENUM('sim', 'nao') NOT NULL,
    medicamento ENUM('sim', 'nao') NOT NULL,
    alergia ENUM('sim', 'nao') NOT NULL,
    assinatura_responsavel TEXT NOT NULL
)";

// Executar o comando ALTER TABLE
$resultado = executarSQL($conn, $sql);

// Exibir resultado
echo $resultado;

//$sql = "SELECT nome_cliente, email_cliente, data_envio, status FROM termos_enviados ORDER BY data_envio DESC";
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
