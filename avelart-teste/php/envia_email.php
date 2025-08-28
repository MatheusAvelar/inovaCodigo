<?php
function sendEmail($to, $subject, $message, $headers, $conn) {
    // Verifica se todos os parâmetros foram fornecidos
    if (empty($to) || empty($subject) || empty($message)) {
        $erro = "Parâmetros incompletos";
        logEmail($to, $subject, $message, $headers, "ERRO", $erro, $conn);
        return ['success' => false, 'error' => $erro];
    }
    
    // Verifica o formato do e-mail
    if (!filter_var($to, FILTER_VALIDATE_EMAIL)) {
        $erro = "E-mail inválido";
        logEmail($to, $subject, $message, $headers, "ERRO", $erro, $conn);
        return ['success' => false, 'error' => $erro];
    }

    // Enviar e-mail
    if (mail($to, $subject, $message, $headers)) {
        logEmail($to, $subject, $message, $headers, "SUCESSO", "", $conn);
        return ['success' => true, 'error' => ''];
    } else {
        $erro = "Falha ao enviar o e-mail";
        logEmail($to, $subject, $message, $headers, "ERRO", $erro, $conn);
        return ['success' => false, 'error' => $erro];
    }
}

// Função para logar no banco
function logEmail($to, $subject, $message, $headers, $status, $erro, $conn) {
    $stmt = $conn->prepare("INSERT INTO log_email_enviado 
        (destinatario, assunto, mensagem, headers, status, erro) 
        VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $to, $subject, $message, $headers, $status, $erro);
    $stmt->execute();
    $stmt->close();
}
?>
