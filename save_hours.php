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

$data = $_POST['date'];
$task = $_POST['task'];
$hours = $_POST['hours'];
$hora_inicio = $_POST['startTime'];
$hora_fim = $_POST['endTime'];
$email = $_SESSION['email'];


$sql = "INSERT INTO horas (data ,tarefa, hora_inicio, hora_fim, horas_gastas, email) VALUES ('$data','$task', '$hora_inicio', '$hora_fim','$hours', '$email')";

if(!mysqli_query($conexao, $sql)) {
    echo "Error: ".mysqli_error($conexao);
}

mysqli_close($conexao);
?>