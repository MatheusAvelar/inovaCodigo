<?php
session_start();

$conexao = mysqli_connect("127.0.0.1", "u221588236_root", "Camila@307", "u221588236_controle_finan");

$email   = $_POST['username'];
$senha   = md5($_POST['password']);
$query   = mysqli_query($conexao,"SELECT * FROM usuarioEstudio WHERE email = '$email' and senha = '$senha'");
$row     = mysqli_num_rows($query);

if($row>0){ 
    $_SESSION['loggedin'] = true;
    $_SESSION['email'] = $email;
    header("Location: ../agendamento.php");
    exit;
} else {
    header("Location: ../login.php");
}

?>