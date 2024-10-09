<?php
include 'utils.php';

try {
    $conn = conectaBanco();
} catch (Exception $e) {
    die("Erro: " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Prepara os dados recebidos do formulário
    $nome = $_POST['nome'];
    $sobrenome = $_POST['sobrenome'];
    $email = $_POST['email'];
    $senha = md5($_POST['senha']);
    $perfil_id = $_POST['perfil'];

    // Escapa caracteres especiais para evitar injeção de SQL
    $nome = mysqli_real_escape_string($conn, $nome);
    $sobrenome = mysqli_real_escape_string($conn, $sobrenome);
    $email = mysqli_real_escape_string($conn, $email);
    $perfil_id = mysqli_real_escape_string($conn, $perfil_id);

    // Verifica se já existe um cadastro com o mesmo e-mail
    $verificaEmail = "SELECT * FROM usuarioEstudio WHERE email = '$email'";
    $resultado = mysqli_query($conn, $verificaEmail);

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
        $sql = "INSERT INTO usuarioEstudio(nome, sobrenome, email, senha, perfil_id, ativo) VALUES('$nome','$sobrenome','$email','$senha', '$perfil_id', '1')";

        if (mysqli_query($conn, $sql)) {
            $status = "success";
            $message = "Cadastro realizado com sucesso!";
            echo "<script>
                sessionStorage.setItem('status', '" . addslashes($status) . "');
                sessionStorage.setItem('message', '" . addslashes($message) . "');
                window.location.href = '../criar_acesso.php';
            </script>";
        } else {
            $status = "error";
            $message = "Erro ao cadastrar: " . mysqli_error($conn);
            echo "<script>
                sessionStorage.setItem('status', '" . addslashes($status) . "');
                sessionStorage.setItem('message', '" . addslashes($message) . "');
                window.location.href = '../criar_acesso.php';
            </script>";
        }
    }

    // Fecha a conexão com o banco de dados
    mysqli_close($conn);
}
?>
