<?php
include 'utils.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    $conn = conectaBanco();
} catch (Exception $e) {
    die("Erro ao conectar ao banco de dados: " . $e->getMessage());
}

// Verifique se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capture e sanitize as entradas do formulário
    $inicio = $_POST['inicio'] ?? '';
    $fim = $_POST['fim'] ?? '';
    $tatuador = $_POST['filter_tatuador'] ?? '';
    $opcao_total = $_POST['opcao_total'] ?? 'faturado';

    // Início da query
    $query = "SELECT 
                    CONCAT(UCASE(LEFT(ue.nome, 1)), LCASE(SUBSTRING(ue.nome, 2)), ' ', 
                           UCASE(LEFT(ue.sobrenome, 1)), LCASE(SUBSTRING(ue.sobrenome, 2))) AS nome_completo,
                    SUM(a.valor) AS total_faturado,
                    (SELECT SUM(valor) FROM agendamentos) AS total_estudio
              FROM 
                    agendamentos a
              JOIN 
                    usuarioEstudio ue
              ON 
                    a.usuario_id = ue.id
              WHERE a.status = 0";

    // Filtros opcionais
    if (!empty($tatuador)) {
        $query .= " AND a.usuario_id = ?";
    }
    if (!empty($inicio) && !empty($fim)) {
        $query .= " AND a.data BETWEEN ? AND ?";
    }

    $query .= " GROUP BY ue.id, ue.nome, ue.sobrenome";

    // Preparar e executar a query
    $stmt = $conn->prepare($query);

    $param_types = '';
    $params = [];

    if (!empty($tatuador)) {
        $param_types .= 'i';
        $params[] = $tatuador;
    }
    if (!empty($inicio) && !empty($fim)) {
        $param_types .= 'ss';
        $params[] = $inicio;
        $params[] = $fim;
    }

    if ($param_types) {
        $stmt->bind_param($param_types, ...$params);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    // Exibir os resultados
    if ($result->num_rows > 0) {
        echo $query;
        echo "<table border='1'>
                <tr>
                    <th>Tatuador</th>
                    <th>" . ($opcao_total == 'faturado' ? "Total Faturado" : "Total Recebido pelo Estúdio") . "</th>
                </tr>";
        
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . $row['nome_completo'] . "</td>
                    <td>R$ " . number_format(
                        ($opcao_total == 'faturado' ? $row['total_faturado'] : $row['total_estudio']),
                        2,
                        ',',
                        '.'
                    ) . "</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "Nenhum resultado encontrado para o período selecionado.";
    }

    // Fechar conexão
    $stmt->close();
    $conn->close();
}
?>
