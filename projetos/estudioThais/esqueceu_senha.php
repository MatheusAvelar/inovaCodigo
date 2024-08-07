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
        <h2>Esqueceu a Senha</h2>
        <?php if (!empty($message)): ?>
            <div class="message"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
        <form action="" method="POST">
            <label for="email">Seu E-mail:</label>
            <input type="email" id="email" name="email" required>
            <button type="submit">Enviar Link de Redefinição</button>
        </form>
    </div>
</body>
</html>