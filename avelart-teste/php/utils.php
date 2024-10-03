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
 * Função para carregar variáveis de ambiente a partir de um arquivo .env
 *
 * @param string $filePath Caminho para o arquivo .env
 * @throws Exception Se o arquivo .env não for encontrado
 */
function loadEnv($filePath)
{
    if (!file_exists($filePath)) {
        throw new Exception("Arquivo .env não encontrado.");
    }

    // Ler o arquivo linha por linha
    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
    foreach ($lines as $line) {
        // Ignorar linhas de comentário
        if (strpos(trim($line), '#') === 0) {
            continue;
        }

        // Separar a chave do valor
        list($name, $value) = explode('=', $line, 2);

        // Remover espaços em branco e aspas desnecessárias
        $name = trim($name);
        $value = trim($value, " \t\n\r\0\x0B\"");

        // Definir a variável de ambiente
        putenv(sprintf('%s=%s', $name, $value));
    }
}
?>
