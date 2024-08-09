<?php
session_start();

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Configuração da conexão com o banco de dados
    $servername = "127.0.0.1:3306";
    $username = "u221588236_root";
    $password = "Camila@307";
    $dbname = "u221588236_controle_finan";

    // Definindo variáveis para mensagem de retorno
    $status = "";
    $message = "";

    // Criando a conexão
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificando a conexão
    if ($conn->connect_error) {
        die("Falha na conexão: " . $conn->connect_error);
    }

    // Obtém os dados do formulário
    $id = intval($_POST['id']);
    $nome = $conn->real_escape_string(trim($_POST['nome']));
    $sobrenome = $conn->real_escape_string(trim($_POST['sobrenome']));
    $email = $conn->real_escape_string(trim($_POST['email']));
    $perfil_id = intval($_POST['perfil_id']);

    // Atualiza os dados do usuário no banco de dados
    $query = "UPDATE usuarioEstudio 
              SET nome = '$nome', sobrenome = '$sobrenome', email = '$email', perfil_id = $perfil_id
              WHERE id = $id";

    if ($conn->query($query) === TRUE) {
        // Redireciona para a lista de usuários com uma mensagem de sucesso
        $status = "success";
        $message = "Usuário atualizado com sucesso!";
        //exit();
    } else {
        $status = "error";
        $message = "Erro ao atualizar usuário: " . $conn->error;
    }

    echo "<script>
            console.log('Mensagem de erro: " . addslashes($message) . "');
            sessionStorage.setItem('status', '" . addslashes($status) . "');
            sessionStorage.setItem('message', '" . addslashes($message) . "');
            window.location.href = '../editar_usuario.php?id=$id';
        </script>";

    // Fechando a conexão
    $conn->close();
} else {
    // Se o método da requisição não for POST, redireciona para a página de edição do usuário
    header('Location: editar_usuario.php');
    exit();
}
