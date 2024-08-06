<?php
session_start();

// Verifica se o usuário está logado, se não, redireciona para a página de login
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

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
        <div class="logout-container">
        <form action="php/logout.php" method="post">
            <button type="submit" class="logout-button">Logout</button>
        </form>
    </div>
    </header>

    <div class="container">
        <div id="message-container">
            <?php if (isset($status) && isset($message)) : ?>
                <div class="message <?= $status ?>">
                    <?= $message ?>
                </div>
            <?php endif; ?>
        </div>

        <h1>Agendamento de Macas</h1>

        <div class="grid">
            <div class="maca">
                <form id="form1" method="POST" action="php/agendar_maca.php" onsubmit="return validateForm()">
                    <label for="name1">Nome:</label>
                    <input type="text" id="name1" name="name1" required>
                    <div id="name1-error" class="error-message"></div>

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

                    <button type="submit">Agendar</button>
                    <a href="horarios_agendados.php" class="button">Ver Horários Agendados</a>
                </form>
            </div>
        </div>
    </div>

    <script>
        function validateForm() {
            let isValid = true;

            // Clear previous error messages
            document.querySelectorAll('.error-message').forEach(function (element) {
                element.innerText = '';
            });

            // Get form values
            const name = document.getElementById('name1').value.trim();
            const maca = document.getElementById('maca').value;
            const date = document.getElementById('date1').value;
            const startTime = document.getElementById('start-time1').value;
            const endTime = document.getElementById('end-time1').value;

            // Validate name
            if (name === '') {
                isValid = false;
                document.getElementById('name1-error').innerText = 'O nome é obrigatório.';
            }

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

            return isValid; 
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
