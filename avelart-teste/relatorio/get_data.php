<?php
include 'php/utils.php';

try {
    $conn = conectaBanco();
} catch (Exception $e) {
    die("Erro: " . $e->getMessage());
}

$sql = "SELECT COUNT(*) as total_agendamentos FROM agendamentos WHERE status = 'ativo'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

echo json_encode($row['total_agendamentos']);