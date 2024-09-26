<?php
session_start();
include 'php/verificar_perfil.php';
include 'php/edita_agendamento.php';

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
            <a href="https://inovacodigo.com.br/projetos/estudioThais/agendamento.php">
                <img src="img/tatto.jpeg" alt="Logo do Estúdio" class="logo">
            </a>
        </div>
        <div class="welcome-message">
            Bem Vindo, <?= htmlspecialchars($_SESSION['usuario_nome']) ?>!
        </div>
    </header>

    <div class="container">
        <nav id="menu"> 
            <ul> 
                <li><a href="termos_enviados.php">Termos Enviados</a></li>
                <li><a href="horarios_agendados.php">Horários Agendados</a></li>
                <?php if ($perfil_id == 2) : ?>
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

        <h2>Atualizar Agendamento de Macas</h2>

        <div class="grid">
            <div class="maca">
                <form id="form1" method="POST" action="php/atualizar_maca.php" onsubmit="return validateForm()">
                    <input type="hidden" name="id" value="<?= $agendamento['id'] ?>">

                    <label for="maca">Maca:</label>
                    <select id="maca" name="maca" required>
                        <option value="1" <?= $agendamento['maca_id'] == 1 ? 'selected' : '' ?>>Maca 1</option>
                        <option value="2" <?= $agendamento['maca_id'] == 2 ? 'selected' : '' ?>>Maca 2</option>
                        <option value="3" <?= $agendamento['maca_id'] == 3 ? 'selected' : '' ?>>Maca 3</option>
                        <option value="4" <?= $agendamento['maca_id'] == 4 ? 'selected' : '' ?>>Maca 4</option>
                    </select>
                    <div id="maca-error" class="error-message"></div>

                    <label for="date1">Data:</label>
                    <input type="date" id="date1" name="date1" value="<?= $agendamento['data'] ?>" required>
                    <div id="date1-error" class="error-message"></div>

                    <label for="start-time1">Horário Inicial:</label>
                    <input type="time" id="start-time1" name="start-time1" value="<?= $agendamento['start_time'] ?>" required>
                    <div id="start-time1-error" class="error-message"></div>

                    <label for="end-time1">Horário Final:</label>
                    <input type="time" id="end-time1" name="end-time1" value="<?= $agendamento['end_time'] ?>" required>
                    <div id="end-time1-error" class="error-message"></div>

                    <label for="cliente">Nome do Cliente:</label>
                    <input type="text" id="cliente" name="cliente" value="<?= $agendamento['nome_cliente'] ?>" required>
                    <div id="name-error" class="error-message"></div>

                    <label for="telefone">Telefone Celular:</label>
                    <input type="tel" id="telefone" name="telefone" value="<?= $agendamento['telefone_cliente'] ?>">
                    <div id="telefone-error" class="error-message"></div>

                    <label for="email">E-mail do Cliente:</label>
                    <input type="email" id="email" name="email" value="<?= $agendamento['email_cliente'] ?>">
                    <div id="email-error" class="error-message"></div>

                    <label for="estilo">Estilo:</label>
                    <input type="text" id="estilo" name="estilo" value="<?= $agendamento['estilo'] ?>" required>
                    <div id="estilo-error" class="error-message"></div>

                    <label for="tamanho">Tamanho (cm):</label>
                    <input type="text" id="tamanho" name="tamanho" value="<?= $agendamento['tamanho'] ?>" required>
                    <div id="tamanho-error" class="error-message"></div>

                    <label for="valor">Valor:</label>
                    <input type="text" id="valor" name="valor" value="<?= 'R$ ' . number_format($agendamento['valor'], 2, ',', '.') ?>" required>
                    <div id="valor-error" class="error-message"></div>

                    <label for="pagamento">Forma de Pagamento:</label>
                    <select id="pagamento" name="pagamento" required>
                        <option value="Dinheiro" <?= $agendamento['forma_pagamento'] == 'Dinheiro' ? 'selected' : '' ?>>Dinheiro</option>
                        <option value="Cartão" <?= $agendamento['forma_pagamento'] == 'Cartão' ? 'selected' : '' ?>>Cartão</option>
                        <option value="Pix" <?= $agendamento['forma_pagamento'] == 'Pix' ? 'selected' : '' ?>>Pix</option>
                    </select>
                    <div id="pagamento-error" class="error-message"></div>

                    <label for="sinal_pago">Sinal pago?</label>
                    <select id="sinal_pago" name="sinal_pago" required>
                        <option value="Sim" <?= $agendamento['sinal_pago'] == 'Sim' ? 'selected' : '' ?>>Sim</option>
                        <option value="Não" <?= $agendamento['sinal_pago'] == 'Não' ? 'selected' : '' ?>>Não</option>
                    </select>
                    <div id="sinal_pago-error" class="error-message"></div>

                    <label for="descricao">Descrição:</label>
                    <input type="text" id="descricao" name="descricao" value="<?= $agendamento['descricao'] ?>">

                    <button type="submit">Atualizar</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var emailInput = document.getElementById('email');
            var emailError = document.getElementById('email-error');

            emailInput.addEventListener('input', function() {
                if (emailInput.validity.valid) {
                    emailError.textContent = ''; // Limpa a mensagem de erro se válido
                    emailError.style.display = 'none';
                } else {
                    emailError.textContent = 'Por favor, insira um e-mail válido.';
                    emailError.style.display = 'block';
                }
            });
        });

        document.addEventListener('DOMContentLoaded', function () {
            const valorInput = document.getElementById('valor');
            
            valorInput.addEventListener('input', function () {
                let value = valorInput.value.replace(/\D/g, '');
                value = (value / 100).toFixed(2);
                value = value.replace('.', ',');
                valorInput.value = 'R$ ' + value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            });
        });
    </script>
</body>

</html>