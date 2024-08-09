<?php
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

// Condição para aplicar os filtros
$whereClause = "WHERE 1=1"; // Começa com condição verdadeira para adicionar filtros dinamicamente

if (!empty($filterDate)) {
    $whereClause .= " AND ag.data = '" . $conn->real_escape_string($filterDate) . "'";
}

if (!empty($filterMaca)) {
    $whereClause .= " AND ag.maca_id = '" . $conn->real_escape_string($filterMaca) . "'";
}

if (!empty($filterTatuador)) {
    $whereClause .= " AND ag.usuario_id = '" . $conn->real_escape_string($filterTatuador) . "'";
}

// Busca de agendamentos existentes com os filtros aplicados
$query = "SELECT ag.id, ag.descricao, ag.maca_id, ag.data, ag.start_time, ag.end_time, ag.usuario_id, u.nome AS tatuador_nome 
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

        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['tatuador_nome']) . "</td>";
        echo "<td>" . htmlspecialchars($row['maca_id']) . "</td>";
        echo "<td>" . $formattedDate . "</td>";
        echo "<td>" . $formattedStartTime . "</td>";
        echo "<td>" . $formattedEndTime . "</td>";

        // Verificação para mostrar o ícone de excluir apenas se o usuário logado é o dono do agendamento
        if ($row['usuario_id'] == $_SESSION['id']) {
            // Verifica se a data do agendamento está a pelo menos 2 dias no futuro
            $agendamentoDate = strtotime($row['data']);
            $currentDate = strtotime(date('Y-m-d'));
            $dateDiff = ($agendamentoDate - $currentDate) / 86400; // diferença em dias

            if ($dateDiff >= 2) {
                echo "<td><a href='php/delete_agendamento.php' class='delete-icon' 
                          data-id='" . htmlspecialchars($row['id']) . "' 
                          data-description='" . htmlspecialchars($row['descricao']) . "' 
                          data-date='" . $formattedDate . "' 
                          data-start-time='" . $formattedStartTime . "' 
                          data-end-time='" . $formattedEndTime . "'>
                          <i class='fas fa-trash'></i>
                      </a></td>";
            } else {
                echo "<td>Não pode excluir</td>";
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
