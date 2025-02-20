<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $message = $_POST["message"];
    
    $to = "matheus_valladao@hotmail.com"; // Substitua pelo seu endereço de email
    $subject = "Nova mensagem de contato";
    $headers = "From: $email";
    
    mail($to, $subject, $message, $headers);
    
    header("Location: ../obrigado.html"); // Página de agradecimento após enviar o formulário
    exit();
}
?>