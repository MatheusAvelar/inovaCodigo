<?php
session_start();
include 'php/verificar_perfil.php';

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
    <title>Agendamento de Macas</title>
    <link rel="stylesheet" href="css/style.css?v=1.0">
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
                <?php if ($perfil_id == 2) : ?>
                    <li><a href="criar_acesso.php">Criar Acesso</a></li>
                    <li><a href="usuarios_estudio.php">Usuários</a></li>
                <?php endif; ?>
                <li><a href="horarios_agendados.php">Horários Agendados</a></li>
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

        <h2>Agendamento de Macas</h2>

        <div class="grid">
            <div class="maca">
                <form id="form1" method="POST" action="php/agendar_maca.php" onsubmit="return validateForm()">
                    <label for="maca">Maca:</label>
                    <select id="maca" name="maca" required>
                        <option value="">Selecione a maca</option>
                        <option value="1">Maca 1</option>
                        <option value="2">Maca 2</option>
                        <option value="3">Maca 3</option>
                        <option value="4">Maca 4</option>
                    </select>
                    <div id="maca-error" class="error-message"></div>

                    <label for="date1">Data:</label>
                    <input type="date" id="date1" name="date1" required>
                    <div id="date1-error" class="error-message"></div>

                    <label for="start-time1">Horário Inicial:</label>
                    <input type="time" id="start-time1" name="start-time1" required>
                    <div id="start-time1-error" class="error-message"></div>

                    <label for="end-time1">Horário Final:</label>
                    <input type="time" id="end-time1" name="end-time1" required>
                    <div id="end-time1-error" class="error-message"></div>

                    <label for="cliente">Nome do Cliente:</label>
                    <input type="text" id="cliente" name="cliente" required>
                    <div id="name-error" class="error-message"></div>

                    <label for="email">E-mail do Cliente:</label>
                    <input type="email" id="email" name="email">
                    <div id="email-error" class="error-message"></div>

                    <label for="estilo">Estilo:</label>
                    <input type="text" id="estilo" name="estilo" required>
                    <div id="estilo-error" class="error-message"></div>

                    <label for="tamanho">Tamanho (cm):</label>
                    <input type="text" id="tamanho" name="tamanho" required>
                    <div id="tamanho-error" class="error-message"></div>

                    <label for="valor">Valor:</label>
                    <input type="text" id="valor" name="valor" required>
                    <div id="valor-error" class="error-message"></div>

                    <label for="pagamento">Forma de Pagamento:</label>
                    <select id="pagamento" name="pagamento" required>
                        <option value="">Selecione a forma de pagamento</option>
                        <option value="Dinheiro">Dinheiro</option>
                        <option value="Cartão">Cartão</option>
                        <option value="Pix">Pix</option>
                    </select>
                    <div id="pagamento-error" class="error-message"></div>

                    <label for="sinal_pago">Sinal pago?</label>
                    <select id="sinal_pago" name="sinal_pago" required>
                        <option value="">Selecione uma opção</option>
                        <option value="Sim">Sim</option>
                        <option value="Não">Não</option>
                    </select>
                    <div id="sinal_pago-error" class="error-message"></div>

                    <label for="descricao">Descrição:</label>
                    <input type="text" id="descricao" name="descricao">

                    <button type="submit">Agendar</button>
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

        function validateForm() {
            let isValid = true;

            // Clear previous error messages
            document.querySelectorAll('.error-message').forEach(function (element) {
                element.innerText = '';
            });

            // Get form values
            const maca = document.getElementById('maca').value;
            const date = document.getElementById('date1').value;
            const startTime = document.getElementById('start-time1').value;
            const endTime = document.getElementById('end-time1').value;
            
            // Remove mask and convert to float
            const valorInput = document.getElementById('valor').value;
            console.log('Valor input:', valorInput); // Debug: Log the masked input
            const valor = parseFloat(valorInput.replace('R$ ', '').replace(/\./g, '').replace(',', '.'));
            console.log('Valor parsed:', valor); // Debug: Log the parsed value

            // Validate maca
            if (maca === '') {
                isValid = false;
                document.getElementById('maca-error').innerText = 'Selecione uma maca.';
            }

            // Validate date
            if (date === '') {
                isValid = false;
                document.getElementById('date1-error').innerText = 'A data é obrigatória.';
            }

            // Validate start time
            if (startTime === '') {
                isValid = false;
                document.getElementById('start-time1-error').innerText = 'O horário inicial é obrigatório.';
            }

            // Validate end time
            if (endTime === '') {
                isValid = false;
                document.getElementById('end-time1-error').innerText = 'O horário final é obrigatório.';
            }

            // Validate that end time is after start time
            if (startTime && endTime && startTime >= endTime) {
                isValid = false;
                document.getElementById('end-time1-error').innerText = 'O horário final deve ser maior que o horário inicial.';
            }

            // Validate valor
            if (valor <= 0) {
                isValid = false;
                document.getElementById('valor-error').innerText = 'O valor deve ser maior que R$ 0.';
            }

            if (!isValidDateFormat(date)) {
                isValid = false;
                document.getElementById('date1-error').innerText = 'Formato de data inválido. Use YYYY-MM-DD.';
            }

            return isValid;
        }

        function isValidDateFormat(date) {
            const datePattern = /^\d{4}-\d{2}-\d{2}$/;
            return datePattern.test(date);
        }

        document.addEventListener('DOMContentLoaded', function () {
            const status = sessionStorage.getItem('status');
            const message = sessionStorage.getItem('message');

            if (status && message) {
                const messageContainer = document.getElementById('message-container');
                const messageElement = document.createElement('div');
                messageElement.className = 'message ' + status;
                messageElement.innerHTML = message;

                messageContainer.appendChild(messageElement);

                // Limpa as mensagens após exibi-las
                sessionStorage.removeItem('status');
                sessionStorage.removeItem('message');
            }
        });
    </script>
</body>

</html>
