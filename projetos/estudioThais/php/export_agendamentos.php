<?php
// Verifica se a sessão já foi iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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
$filterTatuador = isset($_GET['filter_tatuador']) ? $_GET['filter_tatuador'] : '';

// Condição para aplicar os filtros
$whereClause = "WHERE 1=1"; // Começa com condição verdadeira para adicionar filtros dinamicamente

if (!empty($filterDate)) {
    $whereClause .= " AND ag.data = '" . $conn->real_escape_string($filterDate) . "'";
}

if (!empty($filterMaca)) {
    $whereClause .= " AND ag.maca_id = '" . $conn->real_escape_string($filterMaca) . "'";
}

if (!empty($filterTatuador)) {
    $whereClause .= " AND ag.usuario_id = '" . $conn->real_escape_string($filterTatuador) . "'";
}

// Busca de agendamentos existentes com os filtros aplicados
$query = "SELECT ag.id, ag.descricao, ag.maca_id, ag.data, ag.start_time, ag.end_time, ag.usuario_id, u.nome AS tatuador_nome 
          FROM agendamentos AS ag
          JOIN usuarioEstudio AS u ON ag.usuario_id = u.id
          $whereClause 
          ORDER BY ag.data, ag.start_time";

$result = $conn->query($query);

if ($result->num_rows > 0) {
    $delimiter = ";"; // Usando ponto e vírgula como delimitador, teste se a vírgula não funciona bem
    $filename = "agendamentos_filtrados_" . date('Y-m-d') . ".csv";

    // Cria um arquivo temporário
    $f = fopen('php://memory', 'w');

    // Define os cabeçalhos
    $fields = array('Tatuador', 'Maca', 'Data', 'H. Inicial', 'H. Final');
    fputcsv($f, $fields, $delimiter);

    // Preenche os dados
    while ($row = $result->fetch_assoc()) {
        $formattedDate = date('d/m/Y', strtotime($row['data']));
        $formattedStartTime = date('H:i', strtotime($row['start_time']));
        $formattedEndTime = date('H:i', strtotime($row['end_time']));

        $lineData = array($row['tatuador_nome'], $row['maca_id'], $formattedDate, $formattedStartTime, $formattedEndTime);
        fputcsv($f, $lineData, $delimiter);
    }

    // Volta o ponteiro para o início do arquivo
    fseek($f, 0);

    // Define os headers para download
    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="' . $filename . '";');

    // Envia os dados do arquivo para o output
    fpassthru($f);
} else {
    echo "Nenhum agendamento encontrado.";
}

// Fechando a conexão
$conn->close();
exit;
?>