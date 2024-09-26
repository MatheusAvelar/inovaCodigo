<?php
include 'php/verificar_perfil.php';
include 'php/utils.php';

// Verifica se a sessão já foi iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
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
    die("Falha na conexão: " . $conn->connect_error);
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

// Busca de agendamentos existentes com os filtros aplicados
$query = "SELECT ag.id, ag.descricao, ag.maca_id, ag.data, ag.start_time, ag.end_time, ag.usuario_id, u.nome AS tatuador_nome, u.perfil_id, ag.telefone_cliente, ag.email_cliente
          FROM agendamentos AS ag
          JOIN usuarioEstudio AS u ON ag.usuario_id = u.id
          $whereClause 
          ORDER BY ag.data, ag.start_time";

$result = $conn->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Formatando a data
        $formattedDate = date('d/m/Y', strtotime($row['data']));
        $formattedStartTime = date('H:i', strtotime($row['start_time']));
        $formattedEndTime = date('H:i', strtotime($row['end_time']));

        // Montando o link do termo de responsabilidade
        $nomeCliente = urlencode($row['tatuador_nome']);
        $telefoneCliente = urlencode($row['telefone_cliente']);
        $emailCliente = urlencode($row['email_cliente']);

        $linkTermo = "https://inovacodigo.com.br/projetos/estudioThais/termo_responsabilidade.php";
        $linkTermo .= "?nome_cliente=" . $nomeCliente;
        $linkTermo .= "&telefone_cliente=" . $telefoneCliente;
        $linkTermo .= "&email_cliente=" . $emailCliente;

        echo "<tr>";
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

<script>
    function confirmDelete(form) {
        const button = form.querySelector('button');
        const description = button.getAttribute('data-description');
        const date = button.getAttribute('data-date');
        const startTime = button.getAttribute('data-start-time');
        const endTime = button.getAttribute('data-end-time');
        
        const message = `Você tem certeza de que deseja excluir o agendamento com a descrição "${description}" agendado para ${date} das ${startTime} às ${endTime}?`;

        return confirm(message);
    }
</script>
