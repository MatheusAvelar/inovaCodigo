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
    </style>
</head>

<body>
    <div class="container">
        <h1>Agendamento de Macas</h1>
        <?php if (isset($_GET['status'])) : ?>
            <div class="message <?php echo $_GET['status']; ?>">
                <?php echo $_GET['message']; ?>
            </div>
        <?php endif; ?>
        <div class="grid">
            <div class="maca">
                <form id="form1" method="POST" action="agendar_maca.php">
                    <label for="name1">Nome:</label>
                    <input type="text" id="name1" name="name1" required>

                    <label for="maca">Maca:</label>
                    <select id="maca" name="maca" required>
                        <option value="">Selecione a maca</option>
                        <option value="1">Maca 1</option>
                        <option value="2">Maca 2</option>
                        <option value="3">Maca 3</option>
                        <option value="4">Maca 4</option>
                    </select>

                    <label for="date1">Data:</label>
                    <input type="date" id="date1" name="date1" required>

                    <label for="start-time1">Horário Inicial:</label>
                    <select id="start-time1" name="start-time1" required>
                        <?php for ($hour = 0; $hour < 24; $hour++) : ?>
                            <?php $time = sprintf('%02d:00', $hour); ?>
                            <option value="<?php echo $time; ?>"><?php echo $time; ?></option>
                        <?php endfor; ?>
                    </select>

                    <label for="end-time1">Horário Final:</label>
                    <select id="end-time1" name="end-time1" required>
                        <?php for ($hour = 0; $hour < 24; $hour++) : ?>
                            <?php $time = sprintf('%02d:00', $hour); ?>
                            <option value="<?php echo $time; ?>"><?php echo $time; ?></option>
                        <?php endfor; ?>
                    </select>

                    <button type="submit">Agendar</button>
                </form>
            </div>
        </div>
    </div>
    <script>
        function fetchHorariosOcupados() {
            const macaId = document.getElementById('maca').value;
            const date = document.getElementById('date1').value;

            if (macaId && date) {
                fetch(`fetch_horarios_ocupados.php?maca=${macaId}&date=${date}`)
                    .then(response => response.json())
                    .then(data => {
                        const startSelect = document.getElementById('start-time1');
                        const endSelect = document.getElementById('end-time1');
                        startSelect.innerHTML = '';
                        endSelect.innerHTML = '';

                        const allTimes = ["00:00", "01:00", "02:00", "03:00", "04:00", "05:00", "06:00", "07:00", "08:00", "09:00", "10:00", "11:00", "12:00", "13:00", "14:00", "15:00", "16:00", "17:00", "18:00", "19:00", "20:00", "21:00", "22:00", "23:00"];

                        allTimes.forEach(time => {
                            if (!data.includes(time)) {
                                const optionStart = document.createElement('option');
                                optionStart.value = time;
                                optionStart.textContent = time;
                                startSelect.appendChild(optionStart);

                                const optionEnd = document.createElement('option');
                                optionEnd.value = time;
                                optionEnd.textContent = time;
                                endSelect.appendChild(optionEnd);
                            }
                        });
                    });
            }
        }

        function exibirMensagem(mensagem, tipo) {
            const messageContainer = document.getElementById('message-container');
            messageContainer.innerHTML = `<div class="message ${tipo}">${mensagem}</div>`;
        }

        document.getElementById('maca').addEventListener('change', fetchHorariosOcupados);
        document.getElementById('date1').addEventListener('change', fetchHorariosOcupados);
    </script>
</body>

</html>