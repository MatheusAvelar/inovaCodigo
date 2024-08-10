<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

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

// Consulta SQL
$query = "SELECT `id`, `descricao`, `maca_id`, `data`, `start_time`, `end_time`, `usuario_id`, `nome_cliente`, `estilo`, `tamanho`, `valor`, `forma_pagamento`, `sinal_pago`, `telefone_cliente` FROM `agendamentos` WHERE 1";
$result = $conn->query($query);

$data = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

// Retorna os dados em formato JSON
echo json_encode($data);

// Fechando a conexão
$conn->close();
?>
