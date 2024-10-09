<?php
include 'php/verificar_perfil.php';

if ($_SESSION['perfil_id'] != 2) {
    header("Location: agendamento.php");
    exit();
}
// Configuração da conexão com o banco de dados
$servername = "127.0.0.1:3306";
$username = "u221588236_root";
$password = "Camila@307";
$dbname = "u221588236_controle_finan";

// Criando a conexão
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Exemplo: Pegando o total de agendamentos
$sql = "SELECT COUNT(*) as total_agendamentos FROM agendamentos WHERE status = 'ativo'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Mostrando os dados
    while($row = $result->fetch_assoc()) {
        echo "Total de agendamentos: " . $row['total_agendamentos'];
    }
} else {
    echo "Nenhum agendamento encontrado";
}

$conn->close();
