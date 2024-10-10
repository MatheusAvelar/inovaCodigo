<?php
include 'utils.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    $conn = conectaBanco();
} catch (Exception $e) {
    die("Erro: " . $e->getMessage());
}

// Verifique se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capture as entradas do formulário
    $inicio = $_POST['inicio'];
    $fim = $_POST['fim'];
    $tatuador = $_POST['filter_tatuador'];
    $opcao_total = $_POST['opcao_total'];

    // Construir a consulta SQL
    $query = "SELECT usuario_id, SUM(valor) AS total_faturado, SUM(valor) AS total_estudio
              FROM agendamentos
              WHERE ";

    // Filtrar por tatuador se não for 'Todos'
    if ($tatuador != '') {
        $query .= " AND usuario_id = $tatuador";
    }

    $query .= " GROUP BY usuario_id";

    // Executar a consulta
    $result = $conn->query($query);

    // Verificar se há resultados
    if ($result->num_rows > 0) {
        echo "<table border='1'>
                <tr>
                    <th>Tatuador</th>";
        if ($opcao_total == 'faturado') {
            echo "<th>Total Faturado</th>";
        } else {
            echo "<th>Total Recebido pelo Estúdio</th>";
        }
        echo "</tr>";

        // Exibir os dados
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . $row['usuario_id'] . "</td>";
            if ($opcao_total == 'faturado') {
                echo "<td>R$ " . number_format($row['total_faturado'], 2, ',', '.') . "</td>";
            } else {
                echo "<td>R$ " . number_format($row['total_estudio'], 2, ',', '.') . "</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "Nenhum resultado encontrado para o período selecionado.";
    }

    // Fechar conexão
    $conn->close();
}
?>
