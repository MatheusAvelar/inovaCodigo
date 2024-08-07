<?php
// delete_agendamento.php

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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    
    // Verificar se o agendamento pode ser excluído
    $query = "SELECT data FROM agendamentos WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->bind_result($data);
    $stmt->fetch();
    $stmt->close();
    
    if ($data) {
        $currentDate = new DateTime();
        $appointmentDate = new DateTime($data);
        $interval = $currentDate->diff($appointmentDate);
        
        // Verificar se a data agendada é pelo menos 2 dias após a data atual
        if ($interval->days >= 2 || $interval->invert == 0) {
            // Excluir agendamento
            $query = "DELETE FROM agendamentos WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('i', $id);
            if ($stmt->execute()) {
                echo "Agendamento excluído com sucesso.";
            } else {
                echo "Erro ao excluir o agendamento.";
            }
            $stmt->close();
        } else {
            echo "Não é possível excluir um agendamento com menos de 2 dias de antecedência.";
        }
    } else {
        echo "Agendamento não encontrado.";
    }
}

$conn->close();

// Redirecionar de volta para a página de horários agendados
header("Location: ../horarios_agendados.php");
exit;
