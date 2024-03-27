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

// Query para selecionar todas as horas registradas
$sql = "SELECT data, tarefa, hora_inicio, hora_fim, horas_gastas FROM horas";
$result = mysqli_query($conexao, $sql);

if (mysqli_num_rows($result) > 0) {
    echo "<table>
            <tr>
                <th>Data</th>
                <th>Demanda</th>
                <th>Hora Início</th>
                <th>Hora Fim</th>
                <th>Total</th>
            </tr>";

    while ($row = mysqli_fetch_assoc($result)) {
        // Formata as datas no formato "d/m/Y"
        $data_formatada = date('d/m/Y', strtotime($row["data"]));
        
        echo "<tr>";
        echo "<td>".$data_formatada."</td>";
        echo "<td>".$row["tarefa"]."</td>";
        echo "<td>".$row["hora_inicio"]."</td>";
        echo "<td>".$row["hora_fim"]."</td>";
        echo "<td>".$row["horas_gastas"]."</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>Nenhum registro encontrado</p>";
}

mysqli_close($conexao);
?>
