<?php
function sendEmail($to, $subject, $message, $headers) {
    // Verifica se todos os parâmetros foram fornecidos
    if (empty($to) || empty($subject) || empty($message)) {
        return ['success' => false, 'error' => 'Parâmetros incompletos'];
    }
    
    // Verifica o formato do e-mail
    if (!filter_var($to, FILTER_VALIDATE_EMAIL)) {
        return ['success' => false, 'error' => 'E-mail inválido'];
    }

    // Enviar e-mail
    if (mail($to, $subject, $message, $headers)) {
        return ['success' => true, 'error' => ''];
    } else {
        return ['success' => false, 'error' => 'Falha ao enviar o e-mail'];
    }
}
?>
