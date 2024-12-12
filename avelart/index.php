<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Agendamento</title>
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
                <li><a href="">Home</a></li>
                <li><a href="contato.php">Contato</a></li>
                <?php if (isset($_SESSION['usuario_logado']) && $_SESSION['usuario_logado']): ?>
                    <li><a href="php/logout.php">Sair</a></li>
                <?php else: ?>
                    <li><a href="agendamento.php">Minha Conta</a></li>
                <?php endif; ?>
            </ul> 
        </nav>
        <br>
        <div class="grid">
            <div class="maca">
                <main>
                    <h2>Bem-vindo</h2>
                    <p>O sistema de agendamento completo para estúdios de tatuagem.</p>
            
                    <div class="features" id="features">
                        <div class="feature">
                            <h3>Gestão de Agendamentos</h3>
                            <p>Organize e visualize seus agendamentos de forma prática e eficiente.</p>
                        </div>
                        <div class="feature">
                            <h3>Notificações Automáticas</h3>
                            <p>Envie confirmações e lembretes automáticos por e-mail.</p>
                        </div>
                        <div class="feature">
                            <h3>Termos de Aceite</h3>
                            <p>Gere e imprima termos de aceite personalizados.</p>
                        </div>
                    </div>
                </main>
            </div>
        </div>
        <footer>
            <p>&copy; 2024 Inova Código. Todos os direitos reservados. | <a href="https://inovacodigo.com.br/">Entre em contato</a></p>
        </footer>
    </div>

</body>
</html>
