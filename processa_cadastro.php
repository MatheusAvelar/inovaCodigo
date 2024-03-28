<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conexão com o banco de dados (substitua os valores pelos seus próprios)
    $servername = "seu_servidor";
    $username = "seu_usuario";
    $password = "sua_senha";
    $dbname = "seu_banco_de_dados";

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
    $senha = MD5($_POST['senha']);

    // Escapa caracteres especiais para evitar injeção de SQL
    $nome = mysqli_real_escape_string($conexao, $nome);
    $sobrenome = mysqli_real_escape_string($conexao, $sobrenome);
    $email = mysqli_real_escape_string($conexao, $email);

    // Insere os dados na tabela de usuários
    $sql = "INSERT INTO usuario(nome, sobrenome, email, senha) VALUES('$nome','$sobrenome','$email','$senha')";

    if (mysqli_query($conexao, $sql)) {
        echo "Cadastro realizado com sucesso!";
    } else {
        echo "Erro ao cadastrar: " . mysqli_error($conexao);
    }

    // Fecha a conexão com o banco de dados
    mysqli_close($conexao);
}
?>