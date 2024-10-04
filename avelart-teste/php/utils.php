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
 * @param string $filePath Caminho para o arquivo .env
 * @throws Exception Se o arquivo .env não for encontrado ou se houver falha na conexão
 * @return mysqli Conexão com o banco de dados
 */
function conectaBanco($filePath) {
    $filePath = __DIR__ . '/../.env';
    echo "Diretório atual: ".$filePath;
    // Verifica se o arquivo .env existe
    if (!file_exists($filePath)) {
        throw new Exception("O arquivo .env não foi encontrado no caminho: " . $filePath);
    }

    // Carrega o arquivo .env e define as variáveis de ambiente
    $envVars = parse_ini_file($filePath);

    // Verifica se as variáveis foram carregadas corretamente
    if (!$envVars) {
        throw new Exception("Erro ao carregar o arquivo .env.");
    }

    // Obtendo os dados de conexão a partir do arquivo .env
    $servername = $envVars['DB_HOST'];
    $username = $envVars['DB_USER'];
    $password = $envVars['DB_PASS'];
    $dbname = $envVars['DB_NAME'];

    // Criando a conexão
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificando a conexão
    if ($conn->connect_error) {
        throw new Exception("Falha na conexão: " . $conn->connect_error);
    }

    return $conn;
}
?>
