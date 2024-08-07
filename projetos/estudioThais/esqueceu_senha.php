<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Senha</title>
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
                <h2>Recuperar Senha</h2>
                <form action="php/recuperar_senha.php" method="POST">
                    <label for="email">E-mail:</label><br>
                    <input type="email" id="email" name="email" required><br><br>
                    <button type="submit">Enviar Instruções</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
