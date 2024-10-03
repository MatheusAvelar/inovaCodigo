<?php
include 'utils.php';

try {
    $conn = conectaBanco('./.env');
} catch (Exception $e) {
    die("Erro: " . $e->getMessage());
}

// Verifica se o usuário está logado, se não, redireciona para a página de login
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: index.html");
    exit;
}

// Obtém o perfil do usuário
$usuario_id = $_SESSION['id'];
$sql = "SELECT perfil_id FROM usuarioEstudio WHERE id = '$usuario_id'";
$resultado = mysqli_query($conn, $sql);

if ($resultado) {
    $usuario = mysqli_fetch_assoc($resultado);
    $perfil_id = $usuario['perfil_id'];
} else {
    die("Erro ao obter perfil do usuário: " . mysqli_error($conn));
}

mysqli_close($conn);
?>
