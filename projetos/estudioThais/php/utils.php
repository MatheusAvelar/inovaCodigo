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
?>
