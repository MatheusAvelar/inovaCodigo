<?php
session_start();
include 'php/verificar_perfil.php';

// Verifica se o ID do usuário foi passado na URL e o converte para um inteiro
$userId = isset($_GET['id']) ? intval($_GET['id']) : 0;

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
