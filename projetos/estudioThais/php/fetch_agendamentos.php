<?php
// fetch_agendamentos.php

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

// Condição para aplicar os filtros
$whereClause = "WHERE 1=1"; // Começa com condição verdadeira para adicionar filtros dinamicamente

if (!empty($filterDate)) {
    $whereClause .= " AND data = '" . $conn->real_escape_string($filterDate) . "'";
}

if (!empty($filterMaca)) {
    $whereClause .= " AND maca_id = '" . $conn->real_escape_string($filterMaca) . "'";
}

// Busca de agendamentos existentes com os filtros aplicados
$query = "SELECT nome_cliente, maca_id, data, start_time, end_time FROM agendamentos $whereClause ORDER BY data, start_time";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Formatando a data
        $formattedDate = date('d/m/Y', strtotime($row['data']));
        $formattedStartTime = date('H:i', strtotime($row['start_time']));
        $formattedEndTime = date('H:i', strtotime($row['end_time']));
        
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['nome_cliente']) . "</td>";
        echo "<td>" . htmlspecialchars($row['maca_id']) . "</td>";
        echo "<td>" . $formattedDate . "</td>";
        echo "<td>" . $formattedStartTime . "</td>";
        echo "<td>" . $formattedEndTime . "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='5'>Nenhum agendamento encontrado.</td></tr>";
}

// Fechando a conexão
$conn->close();
?>
