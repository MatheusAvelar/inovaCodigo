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

// Busca de agendamentos existentes
$query = "SELECT nome_cliente, maca_id, data, start_time, end_time FROM agendamentos ORDER BY data, start_time";
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
