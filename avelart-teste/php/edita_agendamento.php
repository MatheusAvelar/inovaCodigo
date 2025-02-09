<?php
include 'php/verificar_perfil.php';
include 'utils.php';

// Verifica se o ID do agendamento foi passado na URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die('ID do agendamento não fornecido.');
}

$agendamentoId = intval($_GET['id']);

try {
    $conn = conectaBanco();
} catch (Exception $e) {
    die("Erro: " . $e->getMessage());
}

// Obtendo os dados do agendamento
$query = "SELECT a.id, a.maca_id, a.data, a.start_time, a.end_time, a.nome_cliente, a.telefone_cliente, a.email_cliente, a.estilo, a.tamanho, a.valor, a.forma_pagamento, a.sinal_pago, a.descricao
          FROM agendamentos AS a
          WHERE a.id = $agendamentoId
          AND status = '1'";
$result = $conn->query($query);

if ($result->num_rows === 0) {
    die('Agendamento não encontrado.');
}

$agendamento = $result->fetch_assoc();

// Obtendo todos os perfis, se necessário (exemplo, caso o agendamento esteja associado a um perfil)
$queryPerfis = "SELECT id, nome FROM perfis";
$perfResult = $conn->query($queryPerfis);

// Fechando a conexão
$conn->close();

function formatarTelefone($telefone) {
    // Remove todos os caracteres não numéricos
    $telefone = preg_replace('/\D/', '', $telefone);

    // Verifica se o número possui 11 dígitos (para o formato (XX) XXXXX-XXXX)
    if (strlen($telefone) == 11) {
        // Aplica a formatação
        return '(' . substr($telefone, 0, 2) . ') ' . substr($telefone, 2, 5) . '-' . substr($telefone, 7);
    }
    // Se o telefone não tiver 11 dígitos, retorna ele sem formatação
    return $telefone;
}
?>
