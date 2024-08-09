<?php
session_start();
include 'php/verificar_perfil.php';

// Função para registrar erros em um arquivo de log
function logError($message) {
    $logFile = 'error_log.txt'; // Caminho para o arquivo de log
    $currentDate = date('Y-m-d H:i:s');
    file_put_contents($logFile, "[$currentDate] $message\n", FILE_APPEND);
}

// Verifica se o ID do usuário foi passado na URL e o converte para um inteiro
$userId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// ID do usuário que está realizando a exclusão (ajuste conforme necessário)
$loggedInUserId = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 0;

if ($userId == 0) {
    logError('ID do usuário não fornecido ou inválido.');
    die('ID do usuário não fornecido ou inválido.');
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
    $errorMessage = 'Falha na conexão com o banco de dados: ' . $conn->connect_error;
    logError($errorMessage);
    $_SESSION['status'] = 'error';
    $_SESSION['message'] = $errorMessage;
    header('Location: ../usuarios_estudio.php');
    exit();
}

echo "Conexão com o banco de dados bem-sucedida";

// Verificando se o usuário existe
$query = "SELECT id FROM usuarioEstudio WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $errorMessage = 'Usuário não encontrado.';
    logError($errorMessage);
    $_SESSION['status'] = 'error';
    $_SESSION['message'] = $errorMessage;
    header('Location: ../usuarios_estudio.php');
    exit();
}

// Verificando se o ID do usuário que está realizando a exclusão é válido
$query = "SELECT id FROM usuarioEstudio WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $loggedInUserId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $errorMessage = 'Usuário que está realizando a exclusão não encontrado. ID: ' . $loggedInUserId;
    logError($errorMessage);
    $_SESSION['status'] = 'error';
    $_SESSION['message'] = $errorMessage;
    header('Location: ../usuarios_estudio.php');
    exit();
}

// Verificando se há agendamentos associados ao usuário
$query = "SELECT COUNT(*) AS agendamentos_count FROM agendamentos WHERE usuario_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $userId);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row['agendamentos_count'] > 0) {
    $errorMessage = 'Não é possível excluir o usuário porque há agendamentos associados a ele. Exclua os agendamentos primeiro.';
    logError($errorMessage);
    $_SESSION['status'] = 'error';
    $_SESSION['message'] = $errorMessage;
    header('Location: ../usuarios_estudio.php');
    exit();
}

// Atualizando o usuário para inativo
$query = "UPDATE usuarioEstudio SET ativo = 0 WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $userId);
if ($stmt->execute()) {
    // Inserindo log de exclusão
    $query = "INSERT INTO log_deletes_usuario (usuario_id, deletado_por, data_exclusao) VALUES (?, ?, NOW())";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ii', $userId, $loggedInUserId);
    if ($stmt->execute()) {
        $_SESSION['status'] = 'success';
        $_SESSION['message'] = 'Usuário marcado como inativo com sucesso.';
        echo "Usuário marcado como inativo com sucesso.";
    } else {
        $errorMessage = 'Erro ao registrar log de exclusão: ' . $stmt->error;
        logError($errorMessage);
        $_SESSION['status'] = 'error';
        $_SESSION['message'] = $errorMessage;
        echo "Erro ao registrar log de exclusão.";
    }
} else {
    $errorMessage = 'Erro ao marcar usuário como inativo: ' . $stmt->error;
    logError($errorMessage);
    $_SESSION['status'] = 'error';
    $_SESSION['message'] = $errorMessage;
    echo "Erro ao marcar usuário como inativo.";
}

// Fechando a conexão
$conn->close();

// Redireciona para a lista de usuários com a mensagem de status
header('Location: ../usuarios_estudio.php');
exit();
?>
