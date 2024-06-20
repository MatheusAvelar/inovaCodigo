<?php

function autenticaUsuario() {
    $conexao = mysqli_connect("127.0.0.1:3306", "u221588236_root", "Inova@307", "u221588236_controle_finan");
    //$conexao = mysqli_connect("localhost", "root", "", "sgv_2024");
    ?>
    <script type="text/javascript">
    function redirecionaPainel() {
        setTimeout("window.location='index.php'", 1500);
    }
    function redirecionaLogin() {
        setTimeout("window.location='login.php'", 1500);
    }
    </script>
    <?php
    $email   = $_POST['EmailEntrar'];
    $senha   = md5($_POST['SenhaEntrar']);
    $query   = mysqli_query($conexao,"SELECT * FROM usuario WHERE email = '$email' and senha = '$senha'");
    $row     = mysqli_num_rows($query);
    
    if($row>0){
        $_SESSION['EmailEntrar'] = $email;
        ?>
        <div class="alert alert-success" role="alert">
        <center>Autenticado com sucesso!</center>
        </div>
        <hr>
        <?php
        echo "<script>redirecionaPainel()</script>";      
    } else {
        ?>
        <div class="alert alert-danger" role="alert">
        <center>Usuario ou Senha invalidos !</center>
        </div>
        <hr> 
        <?php
        echo "<script>redirecionaLogin()</script>"; 
    }
}

function logout() {
    session_destroy();
    echo "<center><h3><b>Você foi Desconectado !</b></h3></center><br><br>";
    echo "<script>usuarioDesconectado()</script>";
    ?>
    <script type="text/javascript">
    function usuarioDesconectado() {
        setTimeout("window.location='login.php'", 1500);
    }
    </script><?php
}
function verificaEmailExistente($email){
    $conexao = mysqli_connect("127.0.0.1:3306", "u221588236_root", "Inova@307", "u221588236_controle_finan");
    //$conexao = mysqli_connect("localhost", "root", "", "sgv_2024");
    $query = mysqli_query($conexao,"SELECT * FROM usuario WHERE email = '$email'");
    $row   = mysqli_num_rows($query);
    if($row > 0){
        return false;
    } else {
        return true;
    }   
}

function cadastraLogin(){
    $conexao = mysqli_connect("127.0.0.1:3306", "u221588236_root", "Inova@307", "u221588236_controle_finan");
    //$conexao = mysqli_connect("localhost", "root", "", "sgv_2024");
    $nome       = $_POST['nomeCriar'];
    $email      = $_POST['emailCriar1'];
    $senha      = md5($_POST['senhaCriar1']);
    $cidade     = $_POST['validationCustom03'];
    $estado     = $_POST['validationCustom04'];
    $cep     = $_POST['validationCustom05'];

    if(!verificaEmailExistente($email)) {
        ?>
        <div class="alert alert-danger" role="alert">
        <center>Já existe um usuário cadastrado neste email !</center>
        </div> 
        <?php
    }

        $query = "INSERT INTO usuario(nome, senha, email, cidade, estado, cep) VALUES('$nome','$senha','$email','$cidade','$estado','$cep)"; 
        if(mysqli_query($conexao,$query)){
            ?>
            <div class="alert alert-success" role="alert">
            <center>Usuario criado !</center>
            </div> 
            <?php
        } else {
            ?>
            <div class="alert alert-danger" role="alert">
            <center>Houve uma falha na conexão com o banco de dados !</center>
            </div> 
            <?php
        }

}
?>