<?php
include 'utils.php';

// Verifica se a sessão já foi iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifica se o usuário está logado
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../index.html");
    exit;
}

try {
    $conn = conectaBanco('./.env');
} catch (Exception $e) {
    die("Erro: " . $e->getMessage());
}

// Obtendo o ID do agendamento a ser excluído
$agendamento_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Verifica se o agendamento pertence ao usuário logado
$query = "SELECT a.data, a.usuario_id 
    FROM agendamentos a
    JOIN usuarioEstudio u ON a.usuario_id = u.id
    WHERE a.id = ?
    AND status = '1'";
$stmt = $conn->prepare($query);
if (!$stmt) {
    $_SESSION['status'] = 'error';
    $_SESSION['message'] = 'Erro ao preparar a consulta: ' . $conn->error;
    header("Location: ../horarios_agendados.php");
    exit;
}
$stmt->bind_param("i", $agendamento_id);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($data, $usuario_id);
$stmt->fetch();

if ($stmt->num_rows > 0 && $usuario_id == $_SESSION['id'] || $_SESSION['perfil_id'] == 2) {
    // Verifica se a data do agendamento está a pelo menos 2 dias no futuro
    $agendamentoDate = strtotime($data);
    $currentDate = strtotime(date('Y-m-d'));
    $dateDiff = ($agendamentoDate - $currentDate) / 86400; // diferença em dias

    if ($dateDiff >= 2 || $_SESSION['perfil_id'] == 2) {
        // Exclui o agendamento
        $deleteQuery = "UPDATE agendamentos SET status = '0' WHERE id = ?";
        $deleteStmt = $conn->prepare($deleteQuery);
        if (!$deleteStmt) {
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Erro ao preparar a exclusão: ' . $conn->error;
            header("Location: ../horarios_agendados.php");
            exit;
        }
        $deleteStmt->bind_param("i", $agendamento_id);
        $deleteStmt->execute();

        if ($deleteStmt->affected_rows > 0) {
            // Adiciona log de exclusão
            $logQuery = "INSERT INTO log_exclusoes_agendamento (agendamento_id, data_exclusao, usuario_id, motivo) VALUES (?, NOW(), ?, 'Exclusão pelo usuário')";
            $logStmt = $conn->prepare($logQuery);
            if (!$logStmt) {
                $_SESSION['status'] = 'error';
                $_SESSION['message'] = 'Erro ao preparar o log: ' . $conn->error;
                header("Location: ../horarios_agendados.php");
                exit;
            }
            $logStmt->bind_param("ii", $agendamento_id, $_SESSION['id']);
            $logStmt->execute();

            $_SESSION['status'] = 'success';
            $_SESSION['message'] = 'Agendamento excluído com sucesso.';
        } else {
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Erro ao excluir o agendamento: ' . $conn->error;
        }
    } else {
        $_SESSION['status'] = 'error';
        $_SESSION['message'] = 'Você não pode excluir um agendamento com menos de 2 dias de antecedência.';
    }
} else {
    $_SESSION['status'] = 'error';
    $_SESSION['message'] = "Você não tem permissão para excluir este agendamento.";
    //$_SESSION['message'] = "Você não tem permissão para excluir este agendamento. " . $usuario_id . " " . $_SESSION['id'] . " " . $perfil_id;
}

// Redireciona para a página de erro ou sucesso
header("Location: ../horarios_agendados.php");
exit;

// Fechando a conexão
$conn->close();
?>
