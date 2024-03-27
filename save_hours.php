<?php
$servername = "127.0.0.1:3306";
$username = "u221588236_root";
$password = "Camila@307";
$dbname = "u221588236_controle_finan";

// Cria a conexão
$conexao = mysqli_connect($servername, $username, $password, $dbname);
    
if (!$conexao) {
    die("Falha na conexão: " . mysqli_connect_error());
}

$task = $_POST['task'];
$hours = $_POST['hours'];

$sql = "INSERT INTO horas (tarefa, horas_gastas) VALUES ('$task', '$hours')";

if(!mysqli_query($conexao, $sql)) {
    echo "Error: ".mysqli_error($conexao);
} else {
    echo "Salvo no Banco!";
}

mysqli_close($conexao);
?>