<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CRM</title>
    <!--<link rel="stylesheet" href="{{ asset('css/app.css') }}">-->
    <style>
        /* Estilos básicos para a página de login */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        input[type="email"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #3490dc;
            border: none;
            border-radius: 5px;
            color: white;
            cursor: pointer;
        }
        button:hover {
            background-color: #2779bd;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login CRM</h2>
        <form method="POST" action="#">
            @csrf <!-- Token CSRF do Laravel, necessário para segurança -->
            <div>
                <label for="email">Email</label>
                <input id="email" type="email" name="email" placeholder="Digite seu email" required autofocus>
            </div>

            <div>
                <label for="password">Senha</label>
                <input id="password" type="password" name="password" placeholder="Digite sua senha" required>
            </div>

            <div>
                <button type="submit">Entrar</button>
            </div>
        </form>
    </div>
</body>
</html>
