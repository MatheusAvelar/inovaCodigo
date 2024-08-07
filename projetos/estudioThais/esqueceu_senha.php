<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Esqueceu a Senha</title>
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
        <div id="message-container">
            <?php if (isset($status) && isset($message)) : ?>
                <div class="message <?= $status ?>">
                    <?= $message ?>
                </div>
            <?php endif; ?>
        </div>
        <h2>Esqueceu a Senha</h2>
        <div class="grid">
            <div class="maca">
                <form action="php/recuperar_senha.php" method="POST">
                    <label for="email">Seu E-mail:</label>
                    <input type="email" id="email" name="email" required>
                    <button type="submit">Enviar Link de Redefinição</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>