<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Cria a conexão
    $conexao = mysqli_connect("127.0.0.1:3306", "u221588236_root", "Camila@307", "u221588236_controle_finan");

    // Verifica se a conexão foi estabelecida com sucesso
    if (!$conexao) {
        die("Falha na conexão: " . mysqli_connect_error());
    }

    // Prepara os dados recebidos do formulário
    $email = $_POST['username'];
    $senha = md5($_POST['password']);

    // Escapa caracteres especiais para evitar injeção de SQL
    $email = mysqli_real_escape_string($conexao, $email);

    // Verifica as credenciais do usuário
    $query = "SELECT id, perfil_id FROM usuarioEstudio WHERE email='$email' AND senha='$senha'";
    $resultado = mysqli_query($conexao, $query);

    if (mysqli_num_rows($resultado) > 0) {
        $usuario = mysqli_fetch_assoc($resultado);
        $_SESSION['id'] = $usuario['id'];
        $_SESSION['perfil_id'] = $usuario['perfil_id'];
        $_SESSION['loggedin'] = true;
        $_SESSION['email'] = $email;

        // Redireciona o usuário com base no perfil
        if ($usuario['perfil_id'] == 1) {
            header("Location: ../agendamento.php");
        } elseif ($usuario['perfil_id'] == 2) {
            header("Location: ../admin_dashboard.php");
        }
        exit;
    } else {
        $status = "error";
        $message = "Usuário ou senha inválidos.";
        echo "<script>
            sessionStorage.setItem('status', '" . addslashes($status) . "');
            sessionStorage.setItem('message', '" . addslashes($message) . "');
            window.location.href = '../login.php';
        </script>";
    }

    // Fecha a conexão com o banco de dados
    mysqli_close($conexao);
}
?>
