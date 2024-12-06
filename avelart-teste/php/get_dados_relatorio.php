<?php
include 'utils.php';

// Habilitar exibição de erros para debug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    $conn = conectaBanco(); // Função para conectar ao banco
} catch (Exception $e) {
    die("Erro ao conectar ao banco de dados: " . $e->getMessage());
}

// Verifique se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capture e sanitize as entradas do formulário
    $usuario_id = $_POST['filter_tatuador'] ?? '';
    $inicio = $_POST['inicio'] ?? '';
    $fim = $_POST['fim'] ?? '';

    // Validar os campos obrigatórios
    if (empty($usuario_id) || empty($inicio) || empty($fim)) {
        echo "Por favor, preencha todos os campos.";
        exit;
    }

    // Definir a consulta SQL
    $query = "
        SELECT 
            CONCAT(UCASE(LEFT(ue.nome, 1)), LCASE(SUBSTRING(ue.nome, 2)), ' ', 
                   UCASE(LEFT(ue.sobrenome, 1)), LCASE(SUBSTRING(ue.sobrenome, 2))) AS nome_completo,
            SUM(a.valor) AS total_faturado,
            (SELECT SUM(valor) FROM agendamentos WHERE status = 0) AS total_estudio
        FROM 
            agendamentos a
        JOIN 
            usuarioEstudio ue
        ON 
            a.usuario_id = ue.id
        WHERE 
            a.status = 0 
            AND a.usuario_id = ? 
            AND a.data BETWEEN ? AND ?
        GROUP BY 
            ue.id, ue.nome, ue.sobrenome
    ";

    // Preparar e executar a consulta
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("iss", $usuario_id, $inicio, $fim); // Vincular os parâmetros
        $stmt->execute();
        $result = $stmt->get_result(); // Obter o resultado da consulta

        // Verificar se há resultados
        if ($result->num_rows > 0) {
            echo "<table border='1'>
                    <tr>
                        <th>Tatuador</th>
                        <th>Total Faturado</th>
                        <th>Total do Estúdio</th>
                    </tr>";

            // Exibir os resultados
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . $row['nome_completo'] . "</td>
                        <td>R$ " . number_format($row['total_faturado'], 2, ',', '.') . "</td>
                        <td>R$ " . number_format($row['total_estudio'], 2, ',', '.') . "</td>
                      </tr>";
            }
            echo "</table>";
        } else {
            echo "Nenhum resultado encontrado para os filtros selecionados.";
        }

        echo $query;
        // Fechar a declaração
        $stmt->close();
    } else {
        echo "Erro na preparação da consulta: " . $conn->error;
    }

    // Fechar a conexão
    $conn->close();
}
?>
