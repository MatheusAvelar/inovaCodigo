<?php
session_start();

// Inicializar variáveis de mensagens
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

    $password = $conn->real_escape_string($_POST['password']);
    $confirm_password = $conn->real_escape_string($_POST['confirm_password']);
    $token = $conn->real_escape_string($_GET['token']);

    if ($password !== $confirm_password) {
        $message = "As senhas não coincidem.";
    } else {
        if (isset($_SESSION['reset_token']) && $_SESSION['reset_token'] === $token && isset($_SESSION['reset_token_expiry']) && new DateTime() < new DateTime($_SESSION['reset_token_expiry'])) {
            $email = $_SESSION['reset_email'];

            // Atualiza a senha do usuário
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $query = "UPDATE usuarioEstudio SET senha='$hashed_password' WHERE email='$email'";

            if ($conn->query($query) === TRUE) {
                unset($_SESSION['reset_token']);
                unset($_SESSION['reset_token_expiry']);
                unset($_SESSION['reset_email']);

                $message = "Sua senha foi redefinida com sucesso.";
            } else {
                $message = "Erro ao redefinir a senha.";
            }
        } else {
            $message = "Token inválido ou expirado.";
        }
    }

    $conn->close();
}
?>