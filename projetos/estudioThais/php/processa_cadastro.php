<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Cria a conexão
    $conexao = mysqli_connect("127.0.0.1:3306", "u221588236_root", "Camila@307", "u221588236_controle_finan");

    // Verifica se a conexão foi estabelecida com sucesso
    if (!$conexao) {
        die("Falha na conexão: " . mysqli_connect_error());
    }

    // Prepara os dados recebidos do formulário
    $nome = $_POST['nome'];
    $sobrenome = $_POST['sobrenome'];
    $email = $_POST['email'];
    $senha = md5($_POST['senha']);

    // Escapa caracteres especiais para evitar injeção de SQL
    $nome = mysqli_real_escape_string($conexao, $nome);
    $sobrenome = mysqli_real_escape_string($conexao, $sobrenome);
    $email = mysqli_real_escape_string($conexao, $email);

    // Verifica se já existe um cadastro com o mesmo e-mail
    $verificaEmail = "SELECT * FROM usuarioEstudio WHERE email = '$email'";
    $resultado = mysqli_query($conexao, $verificaEmail);

    if (mysqli_num_rows($resultado) > 0) {
        // Se o e-mail já estiver cadastrado
        $status = "error";
        $message = "O e-mail informado já está cadastrado.";
        echo "<script>
            sessionStorage.setItem('status', '" . addslashes($status) . "');
            sessionStorage.setItem('message', '" . addslashes($message) . "');
            window.location.href = '../criar_acesso.php';
        </script>";
    } else {
        // Insere os dados na tabela de usuários
        $sql = "INSERT INTO usuarioEstudio(nome, sobrenome, email, senha) VALUES('$nome','$sobrenome','$email','$senha')";

        if (mysqli_query($conexao, $sql)) {
            $status = "success";
            $message = "Cadastro realizado com sucesso!";
            echo "<script>
                sessionStorage.setItem('status', '" . addslashes($status) . "');
                sessionStorage.setItem('message', '" . addslashes($message) . "');
                window.location.href = '../criar_acesso.php';
            </script>";
        } else {
            $status = "error";
            $message = "Erro ao cadastrar: " . mysqli_error($conexao);
            echo "<script>
                sessionStorage.setItem('status', '" . addslashes($status) . "');
                sessionStorage.setItem('message', '" . addslashes($message) . "');
                window.location.href = '../criar_acesso.php';
            </script>";
        }
    }

    // Fecha a conexão com o banco de dados
    mysqli_close($conexao);
}
?>
