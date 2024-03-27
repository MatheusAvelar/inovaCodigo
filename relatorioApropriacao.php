<?php
function relatorioApropriacao() {
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
    $result = $conexao->query($sql);

    if ($result->num_rows > 0) {
        // Exibir os dados em cada linha da tabela
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>".$row["data"]."</td>";
            echo "<td>".$row["tarefa"]."</td>";
            echo "<td>".$row["hora_inicio"]."</td>";
            echo "<td>".$row["hora_fim"]."</td>";
            echo "<td>".$row["horas_gastas"]."</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='5'>Nenhum registro encontrado</td></tr>";
    }
    $conexao->close();
}
?>