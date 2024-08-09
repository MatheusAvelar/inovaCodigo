<?php
session_start();

// Ativa a exibição de erros
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

    // Obtém os dados do formulário
    $id = intval($_POST['id']);
    $nome = $conn->real_escape_string(trim($_POST['nome']));
    $sobrenome = $conn->real_escape_string(trim($_POST['sobrenome']));
    $email = $conn->real_escape_string(trim($_POST['email']));
    $perfil_id = intval($_POST['perfil_id']);
    $alterado_por = intval($_SESSION['id']); // Supondo que o ID do usuário logado está na sessão
    $data_alteracao = date('Y-m-d H:i:s'); // Data e hora atual
    $status = intval($_POST['status']); // Obtém o status do formulário

    // Verifique se o ID do usuário logado é válido
    $checkUserQuery = "SELECT id FROM usuarioEstudio WHERE id = $alterado_por";
    $checkUserResult = $conn->query($checkUserQuery);
    if ($checkUserResult->num_rows === 0) {
        die("ID do usuário logado não encontrado na tabela usuarioEstudio.");
    }

    // Inicia uma transação
    $conn->begin_transaction();

    try {
        // Obtém os dados atuais do usuário
        $query = "SELECT nome, sobrenome, email, perfil_id FROM usuarioEstudio WHERE id = $id";
        $result = $conn->query($query);
        if (!$result) {
            throw new Exception("Erro ao buscar dados do usuário: " . $conn->error);
        }

        $user = $result->fetch_assoc();

        // Prepara a atualização
        $updateQuery = "UPDATE usuarioEstudio 
                        SET nome = '$nome', sobrenome = '$sobrenome', email = '$email', perfil_id = $perfil_id, ativo = $status
                        WHERE id = $id";

        if (!$conn->query($updateQuery)) {
            throw new Exception("Erro ao atualizar usuário: " . $conn->error);
        }

        // Registra alterações
        $fields = ['nome', 'sobrenome', 'email', 'perfil_id', 'status'];
        foreach ($fields as $field) {
            $old_value = $user[$field];
            $new_value = $$field; // Verifica o valor correto para a variável

            if ($old_value !== $new_value) {
                $valor_antigo = $conn->real_escape_string($old_value);
                $valor_novo = $conn->real_escape_string($new_value);
                $logQuery = "INSERT INTO log_alteracoes_usuario (usuario_id, campo, valor_antigo, valor_novo, alterado_por, data_alteracao)
                             VALUES ($id, '$field', '$valor_antigo', '$valor_novo', $alterado_por, '$data_alteracao')";
                if (!$conn->query($logQuery)) {
                    throw new Exception("Erro ao registrar alteração: " . $conn->error);
                }
            }
        }

        // Commit da transação
        $conn->commit();

        // Define mensagem de sucesso
        $_SESSION['status'] = "success";
        $_SESSION['message'] = "Usuário atualizado com sucesso!";
    } catch (Exception $e) {
        // Rollback em caso de erro
        $conn->rollback();
        $_SESSION['status'] = "error";
        $_SESSION['message'] = $e->getMessage();

        // Exibe a mensagem de erro e código do erro
        echo "Erro: " . $e->getMessage() . "<br>";
        echo "Código do erro: " . $e->getCode() . "<br>";
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
