<?php
session_start();
//include 'php/verificar_perfil.php';

// Verifica se o ID do usuário foi passado na URL e o converte para um inteiro
$userId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// ID do usuário que está realizando a exclusão (ajuste conforme necessário)
$loggedInUserId = isset($_SESSION['id']) ? intval($_SESSION['id']) : 0;

if ($userId == 0) {
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
    $_SESSION['status'] = 'error';
    $_SESSION['message'] = 'Falha na conexão com o banco de dados: ' . $conn->connect_error;
    header('Location: ../usuarios_estudio.php');
    exit();
}

echo "Conexão com o banco de dados bem-sucedida";

// Verificando se o usuário existe
$query = "SELECT id FROM usuarioEstudio WHERE id = $userId";
$result = $conn->query($query);

if ($result->num_rows === 0) {
    $_SESSION['status'] = 'error';
    $_SESSION['message'] = 'Usuário não encontrado.';
    header('Location: ../usuarios_estudio.php');
    exit();
}

// Verificando se há agendamentos associados ao usuário
$query = "SELECT COUNT(*) AS agendamentos_count FROM agendamentos WHERE usuario_id = $userId";
$result = $conn->query($query);
$row = $result->fetch_assoc();

if ($row['agendamentos_count'] > 0) {
    $_SESSION['status'] = 'error';
    $_SESSION['message'] = 'Não é possível excluir o usuário porque há agendamentos associados a ele. Exclua os agendamentos primeiro.';
    header('Location: ../usuarios_estudio.php');
    exit();
}

// Deletando o usuário
$query = "UPDATE usuarioEstudio SET ativo = 0 WHERE id = $userId";
if ($conn->query($query) === TRUE) {
    // Inserindo log de exclusão
    $query = "INSERT INTO log_deletes_usuario (usuario_id, deletado_por, data_exclusao) VALUES ($userId, $loggedInUserId, NOW())";
    if ($conn->query($query) === TRUE) {
        $_SESSION['status'] = 'success';
        $_SESSION['message'] = 'Usuário desativado com sucesso.';
        echo "Usuário desativado com sucesso.";
    } else {
        $_SESSION['status'] = 'error';
        $_SESSION['message'] = 'Erro ao registrar log de exclusão: ' . $conn->error;
        echo "Erro ao registrar log de exclusão.";
    }
} else {
    $_SESSION['status'] = 'error';
    $_SESSION['message'] = 'Erro ao deletar usuário: ' . $conn->error;
    echo "Erro ao deletar usuário.";
}

// Fechando a conexão
$conn->close();

// Redireciona para a lista de usuários com a mensagem de status
header('Location: ../usuarios_estudio.php');
exit();
?>
