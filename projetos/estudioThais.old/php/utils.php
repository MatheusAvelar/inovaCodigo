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
?>
