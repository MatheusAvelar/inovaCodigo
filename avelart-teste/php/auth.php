<?php
session_start();
include 'php/utils.php';

try {
    $conn = conectaBanco();
} catch (Exception $e) {
    die("Erro: " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Prepara os dados recebidos do formulário
    $email = $_POST['username'];
    $senha = md5($_POST['password']);

    // Escapa caracteres especiais para evitar injeção de SQL
    $email = mysqli_real_escape_string($conn, $email);

    // Verifica as credenciais do usuário
    $query = "SELECT id, perfil_id, nome FROM usuarioEstudio WHERE email='$email' AND senha='$senha' AND ativo=1";
    $resultado = mysqli_query($conn, $query);

    if (mysqli_num_rows($resultado) > 0) {
        $usuario = mysqli_fetch_assoc($resultado);
        $_SESSION['id'] = $usuario['id'];
        $_SESSION['perfil_id'] = $usuario['perfil_id'];
        $_SESSION['loggedin'] = true;
        $_SESSION['email'] = $email;
        $_SESSION['usuario_nome'] = $usuario['nome'];

        header("Location: ../agendamento.php");
        exit;
    } else {
        $status = "error";
        $message = "Usuário ou senha inválidos.";
        echo "<script>
            sessionStorage.setItem('status', '" . addslashes($status) . "');
            sessionStorage.setItem('message', '" . addslashes($message) . "');
            window.location.href = '../index.html';
        </script>";
    }

    // Fecha a conexão com o banco de dados
    mysqli_close($conn);
}
?>
