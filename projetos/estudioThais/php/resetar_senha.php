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
    $password = $_POST['password'];
    $confirm_password = $conn->real_escape_string($_POST['confirm_password']);

    if ($password !== $confirm_password) {
        $status = "error";
        $message = "As senhas não coincidem.";
    } else {
        // Verifica se o token é válido e não expirou
        if (isset($_SESSION['reset_token']) && $_SESSION['reset_token'] === $token && isset($_SESSION['reset_token_expiry']) && new DateTime() < new DateTime($_SESSION['reset_token_expiry'])) {
            $email = $_SESSION['reset_email'];

            // Atualiza a senha do usuário
            $hashed_password = MD5($password);
            $query = "UPDATE usuarioEstudio SET senha='$hashed_password' WHERE email='$email'";

            if ($conn->query($query) === TRUE) {
                // Remove o token de recuperação de senha
                unset($_SESSION['reset_token']);
                unset($_SESSION['reset_token_expiry']);
                unset($_SESSION['reset_email']);

                $status = "success";
                $message = "Sua senha foi redefinida com sucesso.";
            } else {
                $status = "error";
                $message = "Erro ao redefinir a senha.";
            }
        } else {
            $status = "error";
            $message = "Token inválido ou expirado.";
        }
    }

    echo "<script>
        console.log('" . addslashes($message) . "');
        sessionStorage.setItem('status', '" . addslashes($status) . "');
        sessionStorage.setItem('message', '" . addslashes($message) . "');
        window.location.href = '../resetar_senha.php?token=" . urlencode($token) . "';
    </script>";
    exit();
}

$conn->close();
?>
