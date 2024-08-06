<?php
// Conexão com o banco de dados
$servername = "127.0.0.1:3306";
$username = "u221588236_root";
$password = "Camila@307";
$dbname = "u221588236_controle_finan";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

$macaId = $_GET['maca'];
$date = $_GET['date'];

// Consultar horários ocupados
$sql = "SELECT start_time, end_time FROM agendamentos WHERE maca_id = ? AND date = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $macaId, $date);
$stmt->execute();
$result = $stmt->get_result();

$ocupados = [];
while($row = $result->fetch_assoc()) {
    $start = $row['start_time'];
    $end = $row['end_time'];
    $current = $start;

    while ($current != $end) {
        $ocupados[] = $current;
        // Incrementa a hora em 1
        $time = strtotime($current) + 3600;
        $current = date('H:i', $time);
    }
}

$stmt->close();
$conn->close();

// Retorna horários ocupados como JSON
echo json_encode($ocupados);
?>
