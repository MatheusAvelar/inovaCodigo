<?php
session_start();
include 'php/verificar_perfil.php';

// Verifica se o ID do usuário foi passado na URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die('ID do usuário não fornecido.');
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
    die("Falha na conexão: " . $conn->connect_error);
}

// Deletando o usuário
$query = "DELETE FROM usuarioEstudio WHERE id = $userId";
if ($conn->query($query) === TRUE) {
    header('Location: visualizar_usuarios.php'); // Redireciona para a lista de usuários
    exit();
} else {
    echo "Erro ao deletar usuário: " . $conn->error;
}

// Fechando a conexão
$conn->close();
?>
