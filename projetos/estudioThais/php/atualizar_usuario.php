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

    // Obtém os dados antigos para o log
    $queryOldData = "SELECT nome, sobrenome, email, perfil_id FROM usuarioEstudio WHERE id = $id";
    $resultOldData = $conn->query($queryOldData);
    $oldData = $resultOldData->fetch_assoc();

    // Atualiza os dados do usuário no banco de dados
    $query = "UPDATE usuarioEstudio 
              SET nome = '$nome', sobrenome = '$sobrenome', email = '$email', perfil_id = $perfil_id
              WHERE id = $id";

    if ($conn->query($query) === TRUE) {
        // Log de alteração
        $detalhes = "Nome: {$oldData['nome']} -> $nome; Sobrenome: {$oldData['sobrenome']} -> $sobrenome; Email: {$oldData['email']} -> $email; Perfil ID: {$oldData['perfil_id']} -> $perfil_id";
        $logQuery = "INSERT INTO logs (usuario_id, acao, detalhes) VALUES ($id, 'Atualização', '$detalhes')";
        $conn->query($logQuery);

        // Redireciona para a lista de usuários com uma mensagem de sucesso
        $_SESSION['status'] = "success";
        $_SESSION['message'] = "Usuário atualizado com sucesso!";
    } else {
        $_SESSION['status'] = "error";
        $_SESSION['message'] = "Erro ao atualizar usuário: " . $conn->error;
    }

    // Redireciona de volta para a página de edição com o ID do usuário
    header("Location: ../editar_usuario.php?id=$id");
    exit();

    // Fechando a conexão
    $conn->close();
} else {
    // Se o método da requisição não for POST, redireciona para a página de edição do usuário
    header('Location: editar_usuario.php');
    exit();
}
