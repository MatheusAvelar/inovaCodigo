<?php
session_start();

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
    $alterado_por = $_SESSION['usuario_id']; // Supondo que o ID do usuário logado está na sessão
    $data_alteracao = date('Y-m-d H:i:s'); // Data e hora atual

    // Inicia uma transação
    $conn->begin_transaction();

    try {
        // Obtém os dados atuais do usuário
        $query = "SELECT nome, sobrenome, email, perfil_id FROM usuarioEstudio WHERE id = $id";
        $result = $conn->query($query);
        $_SESSION['message'] = $query;
        if ($result) {
            $user = $result->fetch_assoc();

            // Prepara a atualização
            $updateQuery = "UPDATE usuarioEstudio 
                            SET nome = '$nome', sobrenome = '$sobrenome', email = '$email', perfil_id = $perfil_id
                            WHERE id = $id";

            if ($conn->query($updateQuery) === TRUE) {
                // Registra alterações
                $fields = ['nome', 'sobrenome', 'email', 'perfil_id'];
                foreach ($fields as $field) {
                    if ($user[$field] !== $$field) {
                        $valor_antigo = $conn->real_escape_string($user[$field]);
                        $valor_novo = $conn->real_escape_string($field);
                        $logQuery = "INSERT INTO log_alteracoes_usuario (usuario_id, campo, valor_antigo, valor_novo, alterado_por, data_alteracao)
                                     VALUES ($id, '$field', '$valor_antigo', '$valor_novo', $alterado_por, $data_alteracao)";
                        $conn->query($logQuery);
                    }
                }

                // Commit da transação
                $conn->commit();

                // Define mensagem de sucesso
                $_SESSION['status'] = "success";
                //$_SESSION['message'] = "Usuário atualizado com sucesso!";
            } else {
                throw new Exception("Erro ao atualizar usuário: " . $conn->error);
            }
        } else {
            throw new Exception("Erro ao buscar dados do usuário: " . $conn->error);
        }
    } catch (Exception $e) {
        // Rollback em caso de erro
        $conn->rollback();
        $_SESSION['status'] = "error";
        //$_SESSION['message'] = $e->getMessage();
        $_SESSION['message'] = $e->getCode();
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
