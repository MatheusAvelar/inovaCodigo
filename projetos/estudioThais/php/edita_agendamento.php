<?php
// Verifica se o ID do agendamento foi passado na URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die('ID do agendamento não fornecido.');
}

$agendamentoId = intval($_GET['id']);

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

// Obtendo os dados do agendamento
$query = "SELECT a.id, a.maca, a.date1, a.start_time1, a.end_time1, a.cliente, a.telefone, 
                 a.email, a.estilo, a.tamanho, a.valor, a.pagamento, a.sinal_pago, a.descricao
          FROM agendamentos AS a
          WHERE a.id = $agendamentoId";
$result = $conn->query($query);

if ($result->num_rows === 0) {
    die('Agendamento não encontrado.');
}

$agendamento = $result->fetch_assoc();

// Obtendo todos os perfis, se necessário (exemplo, caso o agendamento esteja associado a um perfil)
$queryPerfis = "SELECT id, nome FROM perfis";
$perfResult = $conn->query($queryPerfis);

// Fechando a conexão
$conn->close();
?>
