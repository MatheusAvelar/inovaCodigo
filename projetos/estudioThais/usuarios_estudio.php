<?php
session_start();
include 'php/verificar_perfil.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizar Usuários</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header>
        <div class="logo-container">
            <a href="https://inovacodigo.com.br/projetos/estudioThais/agendamento.php">
                <img src="img/tatto.jpeg" alt="Logo do Estúdio" class="logo">
            </a>
        </div>
        <div class="welcome-message">
            Bem Vindo, <?= htmlspecialchars($_SESSION['usuario_nome']) ?>!
        </div>
    </header>
    
    <div class="container">
        <nav id="menu"> 
            <ul> 
                <?php if ($perfil_id == 2) : ?>
                    <li><a href="criar_acesso.php">Criar Acesso</a></li>
                <?php endif; ?>
                <li><a href="agendamento.php">Agendamento</a></li>
                <li><a href="horarios_agendados.php">Horários Agendados</a></li>
                <li><a href="php/logout.php">Sair</a></li>
            </ul> 
        </nav>
        <h2>Usuários do Sistema</h2>
        <div class="grid">
            <div class="maca">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Sobrenome</th>
                            <th>Email</th>
                            <th>Perfil</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include 'php/get_usuarios.php';
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>