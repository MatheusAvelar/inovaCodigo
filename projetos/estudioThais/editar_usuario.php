<?php
session_start();
include 'php/verificar_perfil.php';
include 'php/edita_usuario.php';

// Verifica se há mensagem de status na sessão
$status = isset($_SESSION['status']) ? $_SESSION['status'] : null;
$message = isset($_SESSION['message']) ? $_SESSION['message'] : null;

// Limpa as mensagens de status da sessão após exibir
unset($_SESSION['status'], $_SESSION['message']);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuário</title>
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
    <br>
    <div class="container">
        <nav id="menu"> 
            <ul> 
                <?php if ($perfil_id == 2) : ?>
                    <li><a href="criar_acesso.php">Criar Acesso</a></li>
                    <li><a href="usuarios_estudio.php">Usuários</a></li>
                    <li><a href="visao_conflitos.php">Conflitos</a></li>
                <?php endif; ?>
                <li><a href="termos_enviados.php">Termos Enviados</a></li>
                <li><a href="agendamento.php">Agendamento</a></li>
                <li><a href="horarios_agendados.php">Horários Agendados</a></li>
                <li><a href="php/logout.php">Sair</a></li>
            </ul> 
        </nav>
        <br>
        <div id="message-container">
            <?php if ($status && $message) : ?>
                <div class="message <?= $status ?>">
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>
        </div>
        <h2>Editar Usuário</h2>
        <div class="grid">
            <div class="maca">
                <form action="php/atualizar_usuario.php" method="POST">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($user['id']) ?>">
                    <label for="nome">Nome:</label>
                    <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($user['nome']) ?>" required>
                    
                    <label for="sobrenome">Sobrenome:</label>
                    <input type="text" id="sobrenome" name="sobrenome" value="<?= htmlspecialchars($user['sobrenome']) ?>" required>
                    
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                    
                    <label for="perfil">Perfil:</label>
                    <select id="perfil" name="perfil_id" required>
                        <?php while ($perfil = $perfResult->fetch_assoc()) : ?>
                            <option value="<?= htmlspecialchars($perfil['id']) ?>" <?= $perfil['id'] == $user['perfil_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($perfil['nome']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>

                    <label for="status">Status:</label>
                    <select id="status" name="status" required>
                        <option value="1" <?= $user['ativo'] == 1 ? 'selected' : '' ?>>Ativo</option>
                        <option value="0" <?= $user['ativo'] == 0 ? 'selected' : '' ?>>Inativo</option>
                    </select>
                    
                    <button type="submit" class="button">Atualizar</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>