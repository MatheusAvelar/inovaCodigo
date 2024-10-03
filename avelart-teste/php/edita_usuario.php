<?php
include 'utils.php';

// Verifica se o ID do usuário foi passado na URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die('ID do usuário não fornecido.');
}

$userId = intval($_GET['id']);

try {
    $conn = conectaBanco('./.env');
} catch (Exception $e) {
    die("Erro: " . $e->getMessage());
}

// Obtendo os dados do usuário
$query = "SELECT u.ativo, u.id, u.nome, u.sobrenome, u.email, u.perfil_id, p.nome AS perfil_nome
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