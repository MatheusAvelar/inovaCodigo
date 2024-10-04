<?php
// utils.php

/**
 * Função para exibir uma mensagem de depuração usando alert em JavaScript
 *
 * @param mixed $data Dados para depurar. Pode ser uma string, array, ou objeto.
 * @param string $message Mensagem opcional para exibir antes dos dados.
 * debugAlert($perfil_id, 'Perfil: ');
 */
function debugAlert($data, $message = '') {
    $debugJson = json_encode($data);
    $message = addslashes($message);
    $debugJson = addslashes($debugJson);
    
    echo "<script>
            alert('$message: $debugJson');
          </script>";
}

/**
 * Função para executar um script SQL passado como parâmetro
 *
 * @param mysqli $conn Conexão com o banco de dados
 * @param string $sql Script SQL a ser executado
 * @return string Mensagem indicando o sucesso ou erro na execução do script
 */
function executarSQL($conn, $sql) {
    // Executar o script SQL
    if ($conn->query($sql) === TRUE) {
        return "Script SQL executado com sucesso.";
    } else {
        return "Erro ao executar o script: " . $conn->error;
    }
}

/**
 * Função para conectar ao banco de dados utilizando variáveis de ambiente de um arquivo .env
 *
 * @return mysqli Conexão com o banco de dados
 */
function conectaBanco() {
    // Obtendo os dados de conexão a partir do arquivo .env
    $servername = '127.0.0.1:3306';
    $username = 'u221588236_avelart';
    $password = 'Avelart@2024';
    $dbname = 'u221588236_avelart_teste';

    // Criando a conexão
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificando a conexão
    if ($conn->connect_error) {
        throw new Exception("Falha na conexão: " . $conn->connect_error);
    } else {
        throw new Exception('Banco de dados conectado com sucesso!');
    }

    return $conn;
}
?>
