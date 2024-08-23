<?php
session_start();
include 'php/verificar_perfil.php';
?><!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendamento de Macas</title>
    <link rel="stylesheet" href="css/style.css">
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
                    <li><a href="usuarios_estudio.php">Usuários</a></li>
                    <li><a href="termos_enviados.php">Termos Enviados</a></li>
                <?php endif; ?>
                <li><a href="agendamento.php">Agendamento</a></li>
                <li><a href="horarios_agendados.php">Horários Agendados</a></li>
                <li><a href="php/logout.php">Sair</a></li>
            </ul> 
        </nav>
        <br>
        <div id="message-container">
            <?php if (isset($status) && isset($message)) : ?>
                <div class="message <?= $status ?>">
                    <?= $message ?>
                </div>
            <?php endif; ?>
        </div>  
        <h2>Realizar Cadastro</h2>
        <div class="grid">
            <div class="maca">
                <form action="php/processa_cadastro.php" method="POST">
                    <label for="nome">Nome:</label>
                    <input type="text" id="nome" name="nome" required>

                    <label for="sobrenome">Sobrenome:</label>
                    <input type="text" id="sobrenome" name="sobrenome" required>

                    <label for="email">E-mail:</label>
                    <input type="email" id="email" name="email" required>

                    <label for="senha">Senha:</label>
                    <input type="password" id="senha" name="senha" required>

                    <label for="perfil">Perfil:</label>
                    <select id="perfil" name="perfil" required>
                        <option value="1">Usuário</option>
                        <option value="2">Master</option>
                    </select>
                    
                    <button type="submit">Cadastrar</button>
                </form>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const status = sessionStorage.getItem('status');
            const message = sessionStorage.getItem('message');

            if (status && message) {
                const messageContainer = document.getElementById('message-container');
                const messageElement = document.createElement('div');
                messageElement.className = 'message ' + status;
                messageElement.innerHTML = message;

                messageContainer.appendChild(messageElement);

                // Limpa as mensagens após exibi-las
                sessionStorage.removeItem('status');
                sessionStorage.removeItem('message');
            }
        });
    </script>
</body>

</html>