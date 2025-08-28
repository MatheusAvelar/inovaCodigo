<?php
include 'envia_email.php';

// Configuração da conexão com o banco de dados
$servername = "127.0.0.1:3306";
$username = "u221588236_root";
$password = "Camila@307";
$dbname = "u221588236_controle_finan";

// Criando a conexão
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Destinatário de teste
$to = "matheus_valladao@hotmail.com";
$subject = "Teste de envio de e-mail";
$message = "Olá Matheus,\n\nEste é um teste simples de envio de e-mail usando PHP.";
$headers = "From: no-reply@seudominio.com\r\n" .
           "Reply-To: no-reply@seudominio.com\r\n" .
           "X-Mailer: PHP/" . phpversion();

$result = sendEmail($to, $subject, $message, $headers, $conn);

// Verifica o resultado
if ($result) {
    echo "✅ E-mail enviado com sucesso!";
} else {
    echo "❌ Erro ao enviar o e-mail.";
}
?>
