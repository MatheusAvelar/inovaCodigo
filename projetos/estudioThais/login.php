<!DOCTYPE html>
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
    </header>
    
    <div class="container">    
        <div class="grid">
            <div class="maca">
                <form action="php/auth.php" method="POST">
                    <label for="username">Usuário:</label><br>
                    <input type="text" id="username" name="username" required><br>
                    <label for="password">Senha:</label><br>
                    <input type="password" id="password" name="password" required><br><br>
                    <button type="submit">Entrar</button>
                </form>
                <form action="esqueceu_senha.php" method="GET">
                    <button type="submit">Esqueceu a Senha</button>
                </form>
                <form action="criar_acesso.php" method="GET">
                    <button type="submit">Criar Acesso</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
