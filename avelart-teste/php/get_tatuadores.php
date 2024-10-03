<?php
// Configuração da conexão com o banco de dados
$servername = "127.0.0.1:3306";
$username = "u221588236_root";
$password = "Camila@307";
$dbname = "u221588236_controle_finan";

// Criando a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificando a conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Obtendo a lista de tatuadores
$query = "SELECT id, nome FROM usuarioEstudio";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $id = htmlspecialchars($row['id']);
        $nome = htmlspecialchars($row['nome']);
        $selected = (isset($_GET['filter_tatuador']) && $_GET['filter_tatuador'] == $id) ? 'selected' : '';
        echo "<option value=\"$id\" $selected>$nome</option>";
    }
}

// Fechando a conexão
$conn->close();
?>
