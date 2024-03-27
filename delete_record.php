<?php
$servername = "127.0.0.1:3306";
$username = "u221588236_root";
$password = "Camila@307";
$dbname = "u221588236_controle_finan";

// Cria a conexão
$conexao = mysqli_connect($servername, $username, $password, $dbname);

// Checa a conexão
if (!$conexao) {
    die("Falha na conexão: " . mysqli_connect_error());
}

// Verifica se o ID do registro a ser excluído foi enviado
if (isset($_POST['recordId']) && !empty($_POST['recordId'])) {
    $recordId = $_POST['recordId'];

    // Query para excluir o registro com base no ID
    $sql = "DELETE FROM horas WHERE id = '$recordId'";
    
    if (mysqli_query($conexao, $sql)) {
        echo "Registro excluído com sucesso.";
    } else {
        echo "Erro ao excluir registro: " . mysqli_error($conexao);
    }
} else {
    echo "ID do registro não fornecido.";
}

mysqli_close($conexao);
?>