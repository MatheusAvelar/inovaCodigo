<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendamento de Macas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        h1 {
            text-align: center;
        }

        .grid {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .maca {
            flex: 1;
            min-width: 250px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .maca h2 {
            margin-top: 0;
        }

        .maca form {
            display: flex;
            flex-direction: column;
        }

        .maca label {
            margin-bottom: 5px;
        }

        .maca input,
        .maca select,
        .maca button {
            margin-bottom: 15px;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .maca button {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }

        .maca button:hover {
            background-color: #45a049;
        }

        .message {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
            font-size: 16px;
        }

        .message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .error-message {
            color: #721c24;
            font-size: 14px;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Agendamento de Macas</h1>
        <div class="grid">
            <div class="maca">
                <form id="form1" method="POST" action="agendar_maca.php" onsubmit="return validateForm()">
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
    </script>
</body>

</html>
