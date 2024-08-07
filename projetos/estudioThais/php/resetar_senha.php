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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $password = $conn->real_escape_string($_POST['password']);
    $confirm_password = $conn->real_escape_string($_POST['confirm_password']);

    if ($password !== $confirm_password) {
        echo "As senhas não coincidem.";
        exit;
    }

    // Verifica se o token é válido e não expirou
    if (isset($_SESSION['reset_token']) && isset($_SESSION['reset_token_expiry']) && new DateTime() < new DateTime($_SESSION['reset_token_expiry'])) {
        $token = $_GET['token']; // Captura o token da URL
        if ($_SESSION['reset_token'] === $token) {
            $email = $_SESSION['reset_email'];

            // Atualiza a senha do usuário
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $query = "UPDATE usuarioEstudio SET senha='$hashed_password' WHERE email='$email'";

            if ($conn->query($query) === TRUE) {
                // Remove o token de recuperação de senha
                unset($_SESSION['reset_token']);
                unset($_SESSION['reset_token_expiry']);
                unset($_SESSION['reset_email']);

                echo "Sua senha foi redefinida com sucesso.";
            } else {
                echo "Erro ao redefinir a senha.";
            }
        } else {
            echo "Token inválido.";
        }
    } else {
        echo "Token expirado.";
    }
}

$conn->close();
?>
