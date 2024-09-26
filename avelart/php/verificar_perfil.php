<?php
// Verifica se o usuário está logado, se não, redireciona para a página de login
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: index.html");
    exit;
}

// Conecta ao banco de dados para obter o perfil do usuário
$conexao = mysqli_connect("127.0.0.1:3306", "u221588236_root", "Camila@307", "u221588236_controle_finan");

if (!$conexao) {
    die("Falha na conexão: " . mysqli_connect_error());
}

// Obtém o perfil do usuário
$usuario_id = $_SESSION['id'];
$sql = "SELECT perfil_id FROM usuarioEstudio WHERE id = '$usuario_id'";
$resultado = mysqli_query($conexao, $sql);

if ($resultado) {
    $usuario = mysqli_fetch_assoc($resultado);
    $perfil_id = $usuario['perfil_id'];
} else {
    die("Erro ao obter perfil do usuário: " . mysqli_error($conexao));
}

mysqli_close($conexao);
?>
