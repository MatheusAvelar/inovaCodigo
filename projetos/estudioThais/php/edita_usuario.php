<?php
/*// Verifica se o ID do usuário foi passado na URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die('ID do usuário não fornecido.');
}
*/
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

// Obtendo os dados do usuário
$query = "SELECT u.id, u.nome, u.sobrenome, u.email, u.perfil_id, p.nome AS perfil_nome
          FROM usuarioEstudio AS u
          JOIN perfis AS p ON u.perfil_id = p.id
          WHERE u.id = $userId";
$result = $conn->query($query);

if ($result->num_rows === 0) {
    die('Usuário não encontrado.');
}

$user = $result->fetch_assoc();

// Obtendo todos os perfis para o dropdown
$queryPerfis = "SELECT id, nome FROM perfis";
$perfResult = $conn->query($queryPerfis);

// Fechando a conexão
$conn->close();
?>