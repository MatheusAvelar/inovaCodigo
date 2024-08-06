<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendamento de Macas</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: #f4f4f4;
            color: #333;
        }

        header {
            background-color: #fff; /* Removido fundo preto */
            padding: 15px;
            text-align: center;
            border-bottom: 1px solid #ddd; /* Linha fina para separação */
        }

        .logo-container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .logo {
            width: 50px;
            height: auto;
            border-radius: 50%; /* Bordas arredondadas */
        }

        h1 {
            font-size: 20px;
            margin: 0;
        }

        .container {
            max-width: 90%;
            margin: 20px auto;
            background-color: #fff;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .grid {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .maca {
            background-color: #fafafa;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            padding: 15px;
        }

        .maca h2 {
            font-size: 18px;
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
            margin-bottom: 10px;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .maca button {
            background-color: #fec76f;
            color: white;
            border: none;
            cursor: pointer;
        }

        .maca button:hover {
            background-color: #dbbe4d;
        }

        .message {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
            font-size: 14px;
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

        @media (min-width: 600px) {
            .container {
                max-width: 600px;
            }

            .logo {
                width: 80px;
            }

            h1 {
                font-size: 24px;
            }
        }
    </style>
</head>

<body>
    <header>
        <div class="logo-container">
            <img src="img/tatto.jpeg" alt="Logo do Estúdio" class="logo">
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

    <!-- Seção de agendamentos -->
    <div class="container">
        <h2>Horários Agendados</h2>
        <div class="grid">
            <div class="maca">
                <table border="1" cellspacing="0" cellpadding="10">
                    <thead>
                        <tr>
                            <th>Cliente</th>
                            <th>Maca</th>
                            <th>Data</th>
                            <th>Horário Inicial</th> 
                            <th>Horário Final</th> 
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($agendamentos as $agendamento) : ?>
                            <tr>
                                <td><?= htmlspecialchars($agendamento['nome_cliente']) ?></td>
                                <td><?= htmlspecialchars($agendamento['maca_id']) ?></td>
                                <td><?= htmlspecialchars($agendamento['data']) ?></td>
                                <td><?= htmlspecialchars($agendamento['start_time']) ?></td>
                                <td><?= htmlspecialchars($agendamento['end_time']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($agendamentos)) : ?> 
                            <tr>
                                <td colspan="5">Nenhum agendamento encontrado.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>