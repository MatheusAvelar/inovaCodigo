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
$sql = "ALTER TABLE termos_enviados
ADD COLUMN nome_responsavel VARCHAR(255) NOT NULL,
ADD COLUMN rg_responsavel VARCHAR(20) NOT NULL,
ADD COLUMN cpf_responsavel VARCHAR(14) NOT NULL,
ADD COLUMN nascimento_responsavel DATE NOT NULL,
ADD COLUMN rg_cliente VARCHAR(20) DEFAULT NULL,
ADD COLUMN cpf_cliente VARCHAR(14) DEFAULT NULL,
ADD COLUMN nascimento_cliente DATE DEFAULT NULL,
ADD COLUMN local_tatuagem VARCHAR(255) NOT NULL,
ADD COLUMN data_tatuagem DATE NOT NULL,
ADD COLUMN nome_tatuador VARCHAR(255) NOT NULL,
ADD COLUMN cicatrizacao ENUM('sim', 'não') NOT NULL,
ADD COLUMN desmaio ENUM('sim', 'não') NOT NULL,
ADD COLUMN hemofilico ENUM('sim', 'não') NOT NULL,
ADD COLUMN hepatite ENUM('sim', 'não') NOT NULL,
ADD COLUMN hiv ENUM('sim', 'não') NOT NULL,
ADD COLUMN autoimune ENUM('sim', 'não') NOT NULL,
ADD COLUMN epileptico ENUM('sim', 'não') NOT NULL,
ADD COLUMN medicamento ENUM('sim', 'não') NOT NULL,
ADD COLUMN alergia ENUM('sim', 'não') NOT NULL,
ADD COLUMN assinatura_responsavel TEXT NOT NULL;";

// Executar o comando ALTER TABLE
$resultado = executarSQL($conn, $sql);

// Exibir resultado
echo $resultado;

$sql = "SELECT cliente_nome, cliente_email, data_envio, status FROM termos_enviados ORDER BY data_envio DESC";
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
