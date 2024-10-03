<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinir Senha</title>
    <link rel="icon" href="img/ico.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <div class="logo-container">
            <a href="https://avelart.inovacodigo.com.br/agendamento.php">
                <img src="img/tatto.jpeg" alt="Logo do Estúdio" class="logo">
            </a>
        </div>
    </header>
    
    <div class="container">
        <div id="message-container">
            <?php if (isset($status) && isset($message)) : ?>
                <div class="message <?= $status ?>">
                    <?= $message ?>
                </div>
            <?php endif; ?>
        </div>
        <h2>Redefinir Senha</h2>
        <div class="grid">
            <div class="maca">
                <form action="php/resetar_senha.php" method="POST">
                    <input type="hidden" name="token" value="<?= htmlspecialchars($_GET['token'] ?? '') ?>">

                    <label for="password">Nova Senha:</label>
                    <input type="password" id="password" name="password" required>

                    <label for="confirm_password">Confirme a Senha:</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>

                    <button type="submit">Redefinir Senha</button>
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
