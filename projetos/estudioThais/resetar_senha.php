<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinir Senha</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <div class="logo-container">
            <a href="https://inovacodigo.com.br/projetos/estudioThais/agendamento.php">
                <img src="img/tatto.jpeg" alt="Logo do Estúdio" class="logo">
            </a>
        </div>
    </header>
    
    <div class="container">    
        <div class="grid">
            <div class="maca">
                <h2>Redefinir Senha</h2>
                <?php
                if (isset($_GET['message']) && !empty($_GET['message'])): 
                    $message = htmlspecialchars($_GET['message']);
                ?>
                    <div class="message"><?= $message ?></div>
                <?php endif; ?>
                <form action="php/resetar_senha.php" method="POST">
                    <input type="hidden" name="token" value="<?= htmlspecialchars($_GET['token']) ?>">

                    <label for="password">Nova Senha:</label>
                    <input type="password" id="password" name="password" required>

                    <label for="confirm_password">Confirme a Senha:</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>

                    <button type="submit">Redefinir Senha</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>