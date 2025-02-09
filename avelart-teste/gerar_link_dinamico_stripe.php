<?php
session_start();

// Verifica se há mensagem de status na sessão
$status = isset($_SESSION['status']) ? $_SESSION['status'] : null;
$message = isset($_SESSION['message']) ? $_SESSION['message'] : null;

// Limpa as mensagens de status da sessão após exibir
unset($_SESSION['status'], $_SESSION['message']);
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atualizar Agendamento de Macas</title>
    <link rel="icon" href="img/ico.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/style.css?v=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
        <div class="welcome-message">
            Bem-vindo, <?= htmlspecialchars($_SESSION['usuario_nome']) ?>!
        </div>
    </header>

    <div class="container">
        <nav id="menu"> 
            <ul> 
                <li><a href="termos_enviados.php">Termos Preenchidos</a></li>
                <li><a href="horarios_agendados.php">Horários Agendados</a></li>
                <?php if ($_SESSION['perfil_id'] == 2) : ?>
                    <li class="dropdown">
                        <a href="javascript:void(0)">
                            <i class="fas fa-cog settings-icon"></i>
                        </a>
                        <div class="dropdown-content">
                            <a href="criar_acesso.php">Criar Acesso</a>
                            <a href="usuarios_estudio.php">Usuários</a>
                            <a href="visao_conflitos.php">Conflitos</a>
                        </div>
                    </li>
                <?php endif; ?>
                <li><a href="php/logout.php">Sair</a></li>
            </ul> 
        </nav>
        <br>
        <div id="message-container">
            <?php if (isset($status) && isset($message)) : ?>
                <div class="message <?= $status ?>">
                    <?= $message ?>
                </div>
            <?php endif; ?>
        </div>

        <h2>Gerar Link de Pagamento - Stripe</h2>

        <div class="grid">
            <div class="maca">
                <form id="paymentForm">
                    <label for="valor">Informe o valor da tatuagem (R$):</label>
                    <input type="text" id="valor" name="valor" placeholder="Ex: 150.00" required>
                    <button type="submit">Gerar Link</button>
                </form>

                <div id="response"></div>
            </div>
        </div>
    </div>
    <script>
        const form = document.getElementById('paymentForm');
        const responseDiv = document.getElementById('response');

        form.addEventListener('submit', async function (e) {
            e.preventDefault();

            const valor = document.getElementById('valor').value;
            responseDiv.innerHTML = 'Gerando link...';

            try {
                const res = await fetch('php/processa_pagamento_stripe.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `valor=${encodeURIComponent(valor)}`
                });

                const data = await res.json();

                if (data.success) {
                    const paymentUrl = encodeURIComponent(data.payment_url);
                    const whatsappMessage = encodeURIComponent("Aqui está o link para realizar o pagamento da tatuagem: " + data.payment_url);
                    responseDiv.innerHTML = `
                            <a href="https://wa.me/?text=${whatsappMessage}" target="_blank" 
                            style="display: inline-block; margin-top: 10px; text-decoration: none; background-color: #25D366; color: white; padding: 8px 12px; border-radius: 5px; font-weight: bold;">
                                Compartilhar no WhatsApp
                            </a>`;
                } else {
                    responseDiv.innerHTML = `<div class="error">${data.message}</div>`;
                }
            } catch (error) {
                responseDiv.innerHTML = `<div class="error">Erro ao enviar a solicitação: ${error.message}</div>`;
            }
        });
    </script>
</body>
</html>
