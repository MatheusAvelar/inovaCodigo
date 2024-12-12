<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Agendamento</title>
    <link rel="icon" href="img/ico.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/style.css">
    <style>
        #menu ul li { 
            display: inline-block; 
        }
        /* Estilo básico do dropdown */
        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px rgba(0,0,0,0.2);
            z-index: 1;
        }

        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .dropdown-content a:hover {
            background-color: #f1f1f1;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        /* Estilo para a engrenagem */
        .settings-icon {
            font-size: 18px;
        }
        
        .welcome-section {
            text-align: center;
            margin-bottom: 30px;
        }

        .welcome-section h2 {
            font-size: 2.5em;
            color: #e67e22;
            margin-bottom: 10px;
        }

        .welcome-section p {
            font-size: 1.2em;
            color: #333;
        }

        .features {
            display: flex;
            gap: 20px;
            justify-content: center;
        }

        .feature {
            text-align: center;
            background: #fff;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            max-width: 300px;
        }

        .feature:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .feature-icon {
            width: 50px;
            height: 50px;
            margin-bottom: 15px;
        }

        .feature h3 {
            font-size: 1.5em;
            color: #e67e22;
            margin-bottom: 10px;
        }

        .feature p {
            font-size: 1em;
            color: #666;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo-container">
            <a href="https://avelart-teste.inovacodigo.com.br/index.php">
                <img src="img/tatto.jpeg" alt="Logo do Estúdio" class="logo">
            </a>
        </div>
    </header>
    <div class="container">
        <nav id="menu"> 
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="contato.php">Contato</a></li>
                <?php if (isset($_SESSION['usuario_logado']) && $_SESSION['usuario_logado']): ?>
                    <li><a href="php/logout.php">Sair</a></li>
                <?php else: ?>
                    <li><a href="agendamento.php">Minha Conta</a></li>
                <?php endif; ?>
            </ul> 
        </nav>
        <br><br>
        <div class="grid">
            <div class="maca">
                <main>
                    <section class="welcome-section">
                        <h1>Bem-vindo</h1>
                        <p>Tenha seu sistema completo para gerenciar agendamentos e melhorar a experiência no estúdio de tatuagem.</p>
                    </section>

                    <section class="features-section">
                        <div class="features" id="features">
                            <div class="feature">
                                <img src="img/agendamentos.png" alt="Ícone de agendamentos" class="feature-icon">
                                <h3>Gestão de Agendamentos</h3>
                                <p>Organize e visualize seus agendamentos de forma prática, otimizando seu tempo.</p>
                            </div>
                            <div class="feature">
                                <img src="img/notificacoes.png" alt="Ícone de notificações" class="feature-icon">
                                <h3>Notificações Automáticas</h3>
                                <p>Envie confirmações e lembretes automáticos por e-mail para seus clientes.</p>
                            </div>
                            <div class="feature">
                                <img src="img/termos.png" alt="Ícone de termos" class="feature-icon">
                                <h3>Termos de Aceite</h3>
                                <p>Crie e imprima termos de aceite personalizados com facilidade.</p>
                            </div>
                        </div>
                    </section>
                </main>
            </div>
        </div>
        <footer>
            <p>&copy; 2024 InkManager. Todos os direitos reservados. | <a href="#contact">Entre em contato</a></p>
        </footer>
    </div>
</body>
</html>
