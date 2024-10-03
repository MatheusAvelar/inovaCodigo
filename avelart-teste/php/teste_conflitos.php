<?php
include 'verificar_perfil.php';
include 'utils.php';

try {
    $conn = conectaBanco('../.env');
} catch (Exception $e) {
    die("Erro: " . $e->getMessage());
}

// Verifica se a sessão já foi iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Consulta para buscar agendamentos com conflitos
$query = "SELECT a1.id AS agendamento1_id, a2.id AS agendamento2_id,
            a1.data AS data_agendamento, a1.start_time AS inicio1, a1.end_time AS fim1,
            a2.start_time AS inicio2, a2.end_time AS fim2, a1.maca_id, 
            u1.nome AS tatuador1, u2.nome AS tatuador2, 
            sa1.descricao AS status1_descricao, sa2.descricao AS status2_descricao
        FROM agendamentos a1
        JOIN agendamentos a2 ON a1.maca_id = a2.maca_id 
        AND a1.data = a2.data 
        AND a1.id != a2.id 
        AND (a1.start_time BETWEEN a2.start_time AND a2.end_time
            OR a1.end_time BETWEEN a2.start_time AND a2.end_time
            OR a2.start_time BETWEEN a1.start_time AND a1.end_time
            OR a2.end_time BETWEEN a1.start_time AND a1.end_time)
        JOIN usuarioEstudio u1 ON a1.usuario_id = u1.id
        JOIN usuarioEstudio u2 ON a2.usuario_id = u2.id
        JOIN status_agendamento sa1 ON a1.status = sa1.id
        JOIN status_agendamento sa2 ON a2.status = sa2.id
        WHERE a1.status = 1 AND a2.status = 1
        ORDER BY a1.data, a1.start_time";

$result = $conn->query($query);

if ($result->num_rows > 0) {
    echo "<table>";
    echo "<thead>
            <tr>
                <th>Maca</th>
                <th>Status</th>
                <th>Data</th>
                <th>Início</th>
                <th>Fim</th>
                <th>Tatuador</th>
            </tr>
          </thead>";
    echo "<tbody>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['maca_id']) . "</td>";
        echo "<td>" . htmlspecialchars($row['status1_descricao']) . "</td>";  // Exibindo a descrição do status
        echo "<td>" . date('d/m/Y', strtotime($row['data_agendamento'])) . "</td>";
        echo "<td>" . date('H:i', strtotime($row['inicio1'])) . "</td>";
        echo "<td>" . date('H:i', strtotime($row['fim1'])) . "</td>";
        echo "<td>" . htmlspecialchars($row['tatuador1']) . "</td>";
        echo "</tr>";
    }
    echo "</tbody>";
    echo "</table>";
} else {
    echo "<p>Nenhum conflito de horário encontrado.</p>";
}

// Fechando a conexão
$conn->close();
?>
