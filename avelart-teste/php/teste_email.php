<?php
// Destinatário de teste
$to = "matheus_valladao@hotmail.com";
$subject = "Teste de envio de e-mail";
$message = "Olá Matheus,\n\nEste é um teste simples de envio de e-mail usando PHP.";
$headers = "From: no-reply@seudominio.com\r\n" .
           "Reply-To: no-reply@seudominio.com\r\n" .
           "X-Mailer: PHP/" . phpversion();

$result = mail($to, $subject, $message, $headers);

// Verifica o resultado
if ($result) {
    echo "✅ E-mail enviado com sucesso!";
} else {
    echo "❌ Erro ao enviar o e-mail.";
}
?>
