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
            <img src="img/tatto.jpeg" alt="Logo do Estúdio" class="logo">
        </div>
    </header>
    
    <div class="container">    
        <div class="grid">
            <div class="maca">
                <form action="php/auth.php" method="POST">
                    <label for="username">Usuário:</label><br>
                    <input type="text" id="username" name="username"><br>
                    <label for="password">Senha:</label><br>
                    <input type="password" id="password" name="password"><br><br>
                    <button type="submit">Login</button>
                </form>
                <form action="criar_acesso.php" method="GET">
                    <button type="submit">Criar Acesso</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>