<?php
session_start();

// Verifica se o usuário e senha estão corretos (aqui você pode usar seu próprio sistema de autenticação)
$username = "usuario";
$password = "senha";

if ($_POST['username'] === $username && $_POST['password'] === $password) {
    $_SESSION['loggedin'] = true;
    header("Location: horas.php"); // Redireciona para a página de Apropriação de Horas
    exit;
} else {
    echo "Usuário ou senha incorretos.";
}
?>