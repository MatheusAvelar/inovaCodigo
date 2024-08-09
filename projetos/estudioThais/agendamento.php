<?php
session_start();
include 'php/verificar_perfil.php';
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
    </header>

    <div class="container">
        <nav id="menu"> 
            <ul> 
                <?php if ($perfil_id == 2) : ?>
                    <li><a href="criar_acesso.php">Criar Acesso</a></li>
                <?php endif; ?>
                <li><a href="horarios_agendados.php">Horários Agendados</a></li>
                <li><a href="php/logout.php">Sair</a></li>
            </ul> 
        </nav>
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

                    <label for="estilo">Estilo:</label>
                    <select id="estilo" name="estilo" required>
                        <option value="">Selecione o estilo</option>
                        <option value="Pontilhismo">Pontilhismo</option>
                        <option value="Minimalista">Minimalista</option>
                        <option value="Blackwork">Blackwork</option>
                        <option value="Realista">Realista</option>
                    </select>
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
            const startTime = document.getElementById('start-time1').value;
            const endTime = document.getElementById('end-time1').value;

            
            // Validate that end time is after start time
            if (startTime && endTime && startTime >= endTime) {
                isValid = false;
                document.getElementById('end-time1-error').innerText = 'O horário final deve ser maior que o horário inicial.';
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
