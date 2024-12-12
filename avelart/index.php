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
        /* Responsividade para telas pequenas */
        @media (max-width: 768px) {
            .features {
                flex-direction: column;
                gap: 10px;
                padding: 0 10px;
            }

            .feature {
                max-width: 100%;
                margin: 0 auto;
            }

            nav#menu ul {
                display: flex;
                flex-direction: column;
                align-items: center;
                padding: 0;
            }

            nav#menu ul li {
                margin-bottom: 10px;
            }

            nav#menu ul li a {
                font-size: 1em;
            }

            .welcome-section h2 {
                font-size: 2em;
            }

            .welcome-section p {
                font-size: 1em;
            }

            footer p {
                font-size: 0.9em;
                text-align: center;
            }
        }

        /* Ajustes para telas muito pequenas */
        @media (max-width: 480px) {
            .feature-icon {
                font-size: 2rem;
            }

            .feature h3 {
                font-size: 1.2em;
            }

            .feature p {
                font-size: 0.9em;
            }

            .logo {
                max-width: 80px;
                height: auto;
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
        <br>
        <div class="grid">
            <div class="maca">
                <div style="overflow-x: auto;">
                    <main>
                        <h2>Bem-vindo</h2>
                        <p>Descubra o sistema de agendamento ideal para estúdios de tatuagem.</p>

                        <div class="features" id="features">
                            <div class="feature">
                                <i class="fas fa-calendar-alt feature-icon"></i>
                                <h3>Gestão de Agendamentos</h3>
                                <p>Organize e visualize seus agendamentos de forma prática e eficiente.</p>
                            </div>
                            <div class="feature">
                                <i class="fas fa-bell feature-icon"></i>
                                <h3>Notificações Automáticas</h3>
                                <p>Envie confirmações e lembretes automáticos por e-mail.</p>
                            </div>
                            <div class="feature">
                                <i class="fas fa-file-signature feature-icon"></i>
                                <h3>Termos de Aceite</h3>
                                <p>Gere e imprima termos de aceite personalizados.</p>
                            </div>
                        </div>
                    </main>
                </div>
            </div>
        </div>
        <footer>
            <p>&copy; 2024 Inova Código. Todos os direitos reservados. | <a href="https://inovacodigo.com.br/">Entre em contato</a></p>
        </footer>
    </div>

</body>
</html>
