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
    $tipoRelatorio = $_POST['tipo_relatorio'] ?? '';
    $usuario_id = $_POST['filter_tatuador'] ?? '';
    $inicio = $_POST['inicio'] ?? '';
    $fim = $_POST['fim'] ?? '';

    // Validar os campos obrigatórios
    /*if (empty($usuario_id) || empty($inicio) || empty($fim) || empty($tipoRelatorio)) {
        echo "Por favor, preencha todos os campos.";
        exit;
    }*/

    // Definir a consulta SQL
    // Inicializar a query
    $query = '';

    if ($tipoRelatorio === 'faturado') {
        // Query para faturado
        $query = "
            SELECT 
                CONCAT(UCASE(LEFT(ue.nome, 1)), LCASE(SUBSTRING(ue.nome, 2)), ' ', 
                        UCASE(LEFT(ue.sobrenome, 1)), LCASE(SUBSTRING(ue.sobrenome, 2))) AS nome_completo,
                SUM(a.valor) AS total_faturado
            FROM usuarioEstudio ue
            JOIN agendamentos a ON ue.id = a.usuario_id
            WHERE a.status = 1
            GROUP BY nome_completo
        ";
    } elseif ($tipoRelatorio === 'recebido_estudio') {
        // Query para recebido_estudio
        $query = "
            SELECT 
                SUM(valor) AS total_recebido
            FROM agendamentos
            WHERE status = 1
        ";
    } else {
        echo "Tipo de relatório inválido ou não informado.";
        exit;
    }

    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }
    
    $result = $conn->query($query);
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            print_r($row);
        }
    } else {
        echo "Nenhum dado encontrado.";
    }
    
    $conn->close();
}
?>
