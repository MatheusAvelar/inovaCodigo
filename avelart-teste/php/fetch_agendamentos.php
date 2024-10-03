<?php
include 'verificar_perfil.php';
include 'utils.php';

try {
    $conn = conectaBanco('./.env');
} catch (Exception $e) {
    die("Erro: " . $e->getMessage());
}

// Verifica se a sessão já foi iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Obtendo os filtros do formulário se estiverem definidos
$filterDate = isset($_GET['filter_date']) ? $_GET['filter_date'] : '';
$filterMaca = isset($_GET['filter_maca']) ? $_GET['filter_maca'] : '';
$filterTatuador = isset($_GET['filter_tatuador']) ? $_GET['filter_tatuador'] : '';
$filterMonth = isset($_GET['filter_month']) ? $_GET['filter_month'] : '';

// Condição para aplicar os filtros
$whereClause = "WHERE status = 1"; // Começa com condição verdadeira para adicionar filtros dinamicamente

if (!empty($filterDate)) {
    $whereClause .= " AND ag.data = '" . $conn->real_escape_string($filterDate) . "'";
}

if (!empty($filterMaca)) {
    $whereClause .= " AND ag.maca_id = '" . $conn->real_escape_string($filterMaca) . "'";
}

if (!empty($filterTatuador)) {
    $whereClause .= " AND ag.usuario_id = '" . $conn->real_escape_string($filterTatuador) . "'";
}

if (!empty($filterMonth)) {
    $whereClause .= " AND MONTH(ag.data) = '" . $conn->real_escape_string($filterMonth) . "'";
}

// Busca de agendamentos com conflitos
$conflictQuery = "
    SELECT a1.id AS agendamento1_id, a2.id AS agendamento2_id
    FROM agendamentos a1
    JOIN agendamentos a2 ON a1.maca_id = a2.maca_id 
        AND a1.data = a2.data 
        AND a1.id != a2.id 
        AND (
            a1.start_time BETWEEN a2.start_time AND a2.end_time
            OR a1.end_time BETWEEN a2.start_time AND a2.end_time
            OR a2.start_time BETWEEN a1.start_time AND a1.end_time
            OR a2.end_time BETWEEN a1.start_time AND a1.end_time
        )
    WHERE a1.status = 1 AND a2.status = 1
";

$conflictResult = $conn->query($conflictQuery);
$conflictIds = [];

if ($conflictResult->num_rows > 0) {
    while ($conflictRow = $conflictResult->fetch_assoc()) {
        $conflictIds[] = $conflictRow['agendamento1_id'];
        $conflictIds[] = $conflictRow['agendamento2_id'];
    }
}

// Busca de agendamentos existentes com os filtros aplicados
$query = "SELECT ag.id, ag.descricao, ag.maca_id, ag.data, ag.start_time, ag.end_time, ag.usuario_id, u.nome AS tatuador_nome, u.perfil_id, ag.telefone_cliente, ag.email_cliente
          FROM agendamentos AS ag
          JOIN usuarioEstudio AS u ON ag.usuario_id = u.id
          $whereClause 
          ORDER BY ag.data, ag.start_time";

$result = $conn->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Verifica se o agendamento atual está na lista de conflitos
        $isConflict = in_array($row['id'], $conflictIds);

        // Formatando a data
        $formattedDate = date('d/m/Y', strtotime($row['data']));
        $formattedStartTime = date('H:i', strtotime($row['start_time']));
        $formattedEndTime = date('H:i', strtotime($row['end_time']));

        // Montando o link do termo de responsabilidade
        $nomeCliente = urlencode($row['tatuador_nome']);
        $telefoneCliente = urlencode($row['telefone_cliente']);
        $emailCliente = urlencode($row['email_cliente']);

        $linkTermo = "https://avelart.inovacodigo.com.br/termo_responsabilidade.php";
        $linkTermo .= "?nome_cliente=" . $nomeCliente;
        $linkTermo .= "&telefone_cliente=" . $telefoneCliente;
        $linkTermo .= "&email_cliente=" . $emailCliente;
        $linkTermo .= "&id=" . $_SESSION['id'];

        // Definindo a classe CSS de linha de conflito
        $rowClass = $isConflict ? 'style="color: red;"' : '';

        echo "<tr $rowClass>";
        echo "<td>" . htmlspecialchars($row['tatuador_nome']) . "</td>";
        echo "<td>" . htmlspecialchars($row['maca_id']) . "</td>";
        echo "<td>" . $formattedDate . "</td>";
        echo "<td>" . $formattedStartTime . "</td>";
        echo "<td>" . $formattedEndTime . "</td>";

        // Verificação para mostrar o botão de excluir apenas se o usuário logado é o dono do agendamento
        if ($row['usuario_id'] == $_SESSION['id'] || $perfil_id == 2) {
            // Verifica se a data do agendamento está a pelo menos 2 dias no futuro
            $agendamentoDate = strtotime($row['data']);
            $currentDate = strtotime(date('Y-m-d'));
            $dateDiff = ($agendamentoDate - $currentDate) / 86400; // diferença em dias

            if ($dateDiff > 2 || $perfil_id == 2) {
                echo "<td>
                        <a href='editar_agendamento.php?id=" . htmlspecialchars($row['id']) . "' title='Editar'>
                            <i class='fas fa-edit'></i>
                        </a>
                        <a href='php/delete_agendamento.php?id=" . htmlspecialchars($row['id']) . "' title='Deletar' onclick='return confirm(\"Tem certeza que deseja deletar este agendamento?\");'>
                            <i class='fas fa-trash-alt'></i>
                        </a>
                        <a href='php/reenvia_termos.php?id=" . htmlspecialchars($row['id']) . "' title='Reenviar Termo'>
                            <i class='fas fa-envelope'></i>
                        </a>
                        <a href='$linkTermo' title='Gerar Termo'>
                            <i class='fas fa-file-alt'></i>
                        </a>
                    </td>";
            } else {
                echo "<td>
                        <a href='php/reenvia_termos.php?id=" . htmlspecialchars($row['id']) . "' title='Reenviar Termo'>
                            <i class='fas fa-envelope'></i>
                        </a>
                        <a href='$linkTermo' title='Gerar Termo'>
                            <i class='fas fa-file-alt'></i>
                        </a>
                    </td>";
            }
        } else {
            echo "<td>Não pode excluir</td>";
        }

        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='7'>Nenhum agendamento encontrado.</td></tr>";
}

// Fechando a conexão
$conn->close();
?>
