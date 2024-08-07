<?php
// Verifica se a sessão já foi iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifica se o usuário está logado
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../login.php");
    exit;
}

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

// Obtendo o ID do agendamento a ser excluído
$agendamento_id = isset($_POST['agendamento_id']) ? $_POST['agendamento_id'] : '';

// Verifica se o agendamento pertence ao usuário logado
$query = "SELECT data, usuario_id FROM agendamentos WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $agendamento_id);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($data, $usuario_id);
$stmt->fetch();

if ($stmt->num_rows > 0 && $usuario_id == $_SESSION['usuario_id']) {
    // Verifica se a data do agendamento está a pelo menos 2 dias no futuro
    $agendamentoDate = strtotime($data);
    $currentDate = strtotime(date('Y-m-d'));
    $dateDiff = ($agendamentoDate - $currentDate) / 86400; // diferença em dias

    if ($dateDiff >= 2) {
        // Exclui o agendamento
        $deleteQuery = "DELETE FROM agendamentos WHERE id = ?";
        $deleteStmt = $conn->prepare($deleteQuery);
        $deleteStmt->bind_param("i", $agendamento_id);
        $deleteStmt->execute();

        if ($deleteStmt->affected_rows > 0) {
            echo "Agendamento excluído com sucesso.";
        } else {
            echo "Erro ao excluir o agendamento.";
        }
    } else {
        echo "Você não pode excluir um agendamento com menos de 2 dias de antecedência.";
    }
} else {
    echo "Você não tem permissão para excluir este agendamento.";
}

// Fechando a conexão
$conn->close();
?>
