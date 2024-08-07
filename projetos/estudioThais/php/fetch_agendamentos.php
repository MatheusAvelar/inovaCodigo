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

// Obtendo o ID do usuário logado
$loggedInUserId = $_SESSION['id'];

// Obtendo os filtros do formulário se estiverem definidos
$filterDate = isset($_GET['filter_date']) ? $_GET['filter_date'] : '';
$filterMaca = isset($_GET['filter_maca']) ? $_GET['filter_maca'] : '';

// Condição para aplicar os filtros
$whereClause = "WHERE 1=1"; // Começa com condição verdadeira para adicionar filtros dinamicamente

if (!empty($filterDate)) {
    $whereClause .= " AND ag.data = '" . $conn->real_escape_string($filterDate) . "'";
}

if (!empty($filterMaca)) {
    $whereClause .= " AND ag.maca_id = '" . $conn->real_escape_string($filterMaca) . "'";
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
        echo "<td>" . htmlspecialchars($row['descricao']) . "</td>";
        echo "<td>" . htmlspecialchars($row['maca_id']) . "</td>";
        echo "<td>" . $formattedDate . "</td>";
        echo "<td>" . $formattedStartTime . "</td>";
        echo "<td>" . $formattedEndTime . "</td>";
        echo "<td>" . htmlspecialchars($row['tatuador_nome']) . "</td>";

        // Verifica se a data do agendamento é pelo menos 2 dias antes da data agendada
        $appointmentDate = new DateTime($row['data']);
        $currentDate = new DateTime();
        $interval = $currentDate->diff($appointmentDate);

        // Verifica se o usuário logado é o criador do agendamento
        if ($interval->days >= 2 || $interval->invert == 0 && $row['usuario_id'] == $loggedInUserId) {
            echo "<td><form action='php/delete_agendamento.php' method='post'>
                    <input type='hidden' name='id' value='" . htmlspecialchars($row['id']) . "'>
                    <button type='submit' class='delete-button'>Excluir</button>
                  </form></td>";
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
