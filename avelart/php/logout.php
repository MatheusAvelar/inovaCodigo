<?php
session_start();

// Destrua todas as variáveis de sessão
$_SESSION = array();

// Se desejar destruir a sessão completamente, apague também o cookie de sessão
// Nota: Isto destruirá a sessão e não apenas os dados da sessão
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Finaliza a sessão
session_destroy();

// Redireciona para a página de login após o logout
header("Location: ../index.php");
exit;
?>