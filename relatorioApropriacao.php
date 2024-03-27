<?php
$servername = "127.0.0.1:3306";
$username = "u221588236_root";
$password = "Camila@307";
$dbname = "u221588236_controle_finan";

// Cria a conexão
$conexao = mysqli_connect($servername, $username, $password, $dbname);

// Checa a conexão
if (!$conexao) {
    die("Falha na conexão: " . mysqli_connect_error());
}

// Constrói a query base
$sql = "SELECT id, data, tarefa, hora_inicio, hora_fim, horas_gastas FROM horas WHERE 1";

// Adiciona os filtros de data e demanda, se fornecidos
if (isset($_GET['filterDate']) && !empty($_GET['filterDate'])) {
    $filterDate = $_GET['filterDate'];
    $sql .= " AND data = '$filterDate'";
}

if (isset($_GET['filterTask']) && !empty($_GET['filterTask'])) {
    $filterTask = $_GET['filterTask'];
    $sql .= " AND tarefa LIKE '%$filterTask%'";
}

$result = mysqli_query($conexao, $sql);

if (mysqli_num_rows($result) > 0) {
    echo "<h1>Relatório de Horas</h1>
        <table>
            <tr>
                <th>Data</th>
                <th>Demanda</th>
                <th>Hora Início</th>
                <th>Hora Fim</th>
                <th>Total</th>
                <th>Ação</th>
            </tr>";

    $totalHoras = 0; // Inicializa o total de horas

    while ($row = mysqli_fetch_assoc($result)) {
        // Calcula o total de horas gastas no formato HH:MM
        $horasGastas = $row["horas_gastas"];
        list($horas, $minutos) = explode(':', $horasGastas);
        $totalHoras += $horas * 60 + $minutos;
        
        // Formata as datas no formato "d/m/Y"
        $data_formatada = date('d/m/Y', strtotime($row["data"]));

        // Exibe os dados do registro
        echo "<tr>";
        echo "<td>$data_formatada</td>";
        echo "<td>".$row["tarefa"]."</td>";
        echo "<td>".$row["hora_inicio"]."</td>";
        echo "<td>".$row["hora_fim"]."</td>";
        echo "<td>".$row["horas_gastas"]."</td>";
        echo "<td><button onclick=\"deleteRecord('" . $row['id'] . "')\">Excluir</button></td>";
        echo "</tr>";
    }

    // Converte o total de minutos de volta para o formato "HH:MM"
    $horasTotal = floor($totalHoras / 60);
    $minutosTotal = $totalHoras % 60;
    $totalFormatado = sprintf('%02d:%02d', $horasTotal, $minutosTotal);

    // Exibe o total das horas
    echo "<tr><td colspan='5'>Total: $totalFormatado</td></tr>";

    echo "</table>";
} else {
    echo "<p>Nenhum registro encontrado</p>";
}

mysqli_close($conexao);
?>