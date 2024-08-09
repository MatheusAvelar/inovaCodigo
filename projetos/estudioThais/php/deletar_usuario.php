<?php
session_start();
include 'php/verificar_perfil.php';
echo "Script de exclusão foi chamado"; 
// Verifica se o ID do usuário foi passado na URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['status'] = 'error';
    $_SESSION['message'] = 'ID do usuário não fornecido.';
    header('Location: ../usuarios_estudio.php');
    exit();
}

$userId = intval($_GET['id']);

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

// Deletando o usuário
$query = "DELETE FROM usuarioEstudio WHERE id = $userId";
if ($conn->query($query) === TRUE) {
    echo "Usuário deletado com sucesso.";
    $_SESSION['status'] = 'success';
    $_SESSION['message'] = 'Usuário deletado com sucesso.';
    echo "Usuário deletado com sucesso.";
} else {
    echo "Erro ao deletar usuário.";
    $_SESSION['status'] = 'error';
    $_SESSION['message'] = 'Erro ao deletar usuário: ' . $conn->error;
}

// Fechando a conexão
$conn->close();

// Redireciona para a lista de usuários com a mensagem de status
header('Location: ../usuarios_estudio.php');
exit();
?>
