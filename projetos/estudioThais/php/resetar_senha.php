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
    $token = $conn->real_escape_string($_POST['token']);
    $password = $conn->real_escape_string($_POST['password']);
    $confirm_password = $conn->real_escape_string($_POST['confirm_password']);

    if ($password !== $confirm_password) {
        die("As senhas não coincidem.");
    }

    // Verifica se o token é válido e não expirou
    $query = "SELECT email FROM password_reset WHERE token='$token' AND exp_date > NOW()";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $email = $row['email'];

        // Atualiza a senha do usuário
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $query = "UPDATE usuarios SET senha='$hashed_password' WHERE email='$email'";

        if ($conn->query($query) === TRUE) {
            // Remove o token de recuperação de senha
            $query = "DELETE FROM password_reset WHERE token='$token'";
            $conn->query($query);

            echo "Sua senha foi redefinida com sucesso.";
        } else {
            echo "Erro ao redefinir a senha.";
        }
    } else {
        echo "Token inválido ou expirado.";
    }
}

$conn->close();
?>
