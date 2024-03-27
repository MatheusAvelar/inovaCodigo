<?php
$servername = "127.0.0.1:3306";
$username = "u221588236_root";
$password = "Camila@307";
$dbname = "u221588236_controle_finan";

// Cria a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Checa a conexão
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Pega os dados do formulário
$task = $_POST['task'];
$hours = $_POST['hours'];

// Prepara e executa a query para inserir os dados no banco
$sql = "INSERT INTO horas (tarefa, horas_gastas) VALUES ('$task', '$hours')";

if ($conn->query($sql) === TRUE) {
    echo "Horas registradas com sucesso!";
} else {
    echo "Erro ao registrar horas: " . $conn->error;
}

$conn->close();
?>