<?php
session_start();

// Conexão com o banco de dados
$conexao = mysqli_connect("127.0.0.1", "u221588236_root", "Camila@307", "u221588236_controle_finan");

// Verificação de conexão
if (!$conexao) {
    die("Falha na conexão: " . mysqli_connect_error());
}

// Captura dos dados do formulário
$email = $_POST['username'];
$senha = md5($_POST['password']);

// Verificação de email e senha
$query = mysqli_query($conexao, "SELECT id FROM usuarioEstudio WHERE email = '$email' AND senha = '$senha'");
$row = mysqli_fetch_assoc($query);

// Verificação se o usuário foi encontrado
if ($row) {
    $_SESSION['loggedin'] = true;
    $_SESSION['email'] = $email;
    $_SESSION['id'] = $row['id']; // Armazenando o ID do usuário na sessão

    // Redirecionamento para a página de agendamento
    header("Location: ../agendamento.php");
    exit;
} else {
    // Redirecionamento para a página de login caso as credenciais estejam incorretas
    header("Location: ../login.php");
    exit;
}

// Fechando a conexão com o banco de dados
mysqli_close($conexao);
?>
