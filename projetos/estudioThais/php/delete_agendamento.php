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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $loggedInUserId = $_SESSION['id'];
    
    // Verificar se o agendamento pode ser excluído
    $query = "SELECT usuario_id, data FROM agendamentos WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->bind_result($usuario_id, $data);
    $stmt->fetch();
    $stmt->close();
    
    if ($data) {
        $currentDate = new DateTime();
        $appointmentDate = new DateTime($data);
        $interval = $currentDate->diff($appointmentDate);
        
        // Verificar se a data agendada é pelo menos 2 dias após a data atual e se o usuário é o criador do agendamento
        if ($interval->days >= 2 || $interval->invert == 0) {
            if ($usuario_id == $loggedInUserId) {
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
                echo "Você não tem permissão para excluir este agendamento.";
            }
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