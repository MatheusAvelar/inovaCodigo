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
    $email = $conn->real_escape_string($_POST['email']);

    // Verifica se o e-mail existe no banco de dados
    $query = "SELECT id FROM usuarioEstudio WHERE email='$email'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // Gerar um token único para a recuperação de senha
        $token = bin2hex(random_bytes(50));
        $tokenExpDate = date("Y-m-d H:i:s", strtotime("+1 hour"));

        // Salva o token na sessão
        $_SESSION['reset_token'] = $token;
        $_SESSION['reset_token_expiry'] = $tokenExpDate;
        $_SESSION['reset_email'] = $email;

        // Envia o e-mail com o link de recuperação de senha
        $resetLink = "https://inovacodigo.com.br/projetos/estudioThais/resetar_senha.php?token=$token";
        $subject = "Recuperação de Senha";
        $message = "Clique no link a seguir para resetar sua senha: $resetLink";
        $headers = "From: no-reply@inovacodigo.com.br";

        if (mail($email, $subject, $message, $headers)) {
            $status = "success";
            $message = "Um link de recuperação de senha foi enviado para o seu e-mail.";
        } else {
            $status = "error";
            $message = "Falha ao enviar o e-mail. Tente novamente mais tarde.";
        }
    } else {
        $status = "error";
        $message = "E-mail não encontrado.";
    }

    echo "<script>
        console.log('" . addslashes($message) . "');
        sessionStorage.setItem('status', '" . addslashes($status) . "');
        sessionStorage.setItem('message', '" . addslashes($message) . "');
        //window.location.href = '../esqueceu_senha.php';
    </script>";
    exit();
}

$conn->close();
?>
