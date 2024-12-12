<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Agendamento</title>
    <link rel="icon" href="img/ico.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

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
        
        /* Estilo geral para a seção */
        .welcome-section {
            text-align: center;
            margin-bottom: 30px;
            padding: 20px;
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

        /* Estilo das funcionalidades */
        .features {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
            justify-content: center;
            padding: 20px;
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
            font-size: 3rem;
            margin-bottom: 15px;
            color: #e67e22;
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

        /* Responsividade */
        @media (max-width: 768px) {
            .welcome-section h2 {
                font-size: 2em;
            }

            .welcome-section p {
                font-size: 1em;
            }

            .feature-icon {
                font-size: 2.5rem;
            }

            .feature h3 {
                font-size: 1.3em;
            }
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
                        <h2>Bem-vindo ao Sistema de Agendamento</h2>
                        <p>Descubra o sistema ideal para estúdios de tatuagem. Organize agendamentos, envie notificações e crie termos de aceite de forma simples e eficiente.</p>
                    </section>

                    <section class="features">
                        <div class="feature">
                            <i class="fas fa-calendar-alt feature-icon"></i>
                            <h3>Gestão de Agendamentos</h3>
                            <p>Visualize e organize os seus agendamentos de forma prática e eficiente.</p>
                        </div>
                        <div class="feature">
                            <i class="fas fa-bell feature-icon"></i>
                            <h3>Notificações Automáticas</h3>
                            <p>Envie confirmações e lembretes automáticos diretamente para o seu cliente.</p>
                        </div>
                        <div class="feature">
                            <i class="fas fa-file-signature feature-icon"></i>
                            <h3>Termos de Aceite</h3>
                            <p>Gere e imprima termos personalizados para cada cliente.</p>
                        </div>
                    </section>
                </main>
            </div>
        </div>
        <footer>
            <p>&copy; 2024 Inova Código. Todos os direitos reservados. | <a href="https://inovacodigo.com.br/">Entre em contato</a></p>
        </footer>
    </div>
</body>
</html>
