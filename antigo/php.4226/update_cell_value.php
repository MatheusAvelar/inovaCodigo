<?php
session_start();

$servername = "127.0.0.1:3306";
$username = "u221588236_root";
$password = "Camila@307";
$dbname = "u221588236_controle_finan";

// Cria a conexão
$conexao = mysqli_connect($servername, $username, $password, $dbname);
    
if (!$conexao) {
    die("Falha na conexão: " . mysqli_connect_error());
}

// Verifica se a solicitação é uma solicitação POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifica se os parâmetros necessários estão presentes
    if (isset($_POST["rowId"]) && isset($_POST["cellIndex"]) && isset($_POST["newValue"])) {
        // Recupera os valores dos parâmetros
        $rowId = $_POST["rowId"];
        $cellIndex = $_POST["cellIndex"];
        $newValue = $_POST["newValue"];

        // Determine qual coluna está sendo atualizada com base no índice da célula
        switch ($cellIndex) {
            case 1: // Coluna de data
                $columnName = "data";
                break;
            case 2: // Coluna de tarefa
                $columnName = "tarefa";
                break;
            case 3: // Coluna de hora_inicio
                $columnName = "hora_inicio";
                break;
            case 4: // Coluna de hora_fim
                $columnName = "hora_fim";
                break;
            case 5: // Coluna de horas_gastas
                $columnName = "horas_gastas";
                break;
            default:
                die("Índice de coluna inválido.");
        }

        // Atualiza o valor da célula no banco de dados
        // Aqui você precisa substituir 'horas' pelo nome da sua tabela no banco de dados
        $sql = "UPDATE horas SET $columnName = '$newValue' WHERE id = $rowId";

        if (!mysqli_query($conexao, $sql)) {
            echo "Error: " . mysqli_error($conexao);
        } else {
            echo "Atualização bem-sucedida!";
        }
    } else {
        // Se algum dos parâmetros estiver faltando, retorne uma mensagem de erro
        echo "Parâmetros ausentes na solicitação.";
    }
} else {
    // Se a solicitação não for POST, retorne uma mensagem de erro
    echo "Este endpoint suporta apenas solicitações POST.";
}

mysqli_close($conexao);
?>