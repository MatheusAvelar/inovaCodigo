<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Horários Já Agendados</title>
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
        /* Estilos para a tabela */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            table-layout: fixed; /* Adiciona uma largura fixa para as células */
        }

        thead {
            background-color: #f0f0f0; /* Cor de fundo suave */
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            font-size: 16px;
            overflow: hidden; /* Garante que o texto não ultrapasse a célula */
            text-overflow: ellipsis; /* Adiciona reticências se o texto for muito longo */
            white-space: nowrap; /* Impede que o texto quebre em várias linhas */
        }

        th {
            background-color: #fec76f; /* Cor que combina com o botão */
            color: #333;
            font-weight: bold;
        }

        tbody tr:nth-child(even) {
            background-color: #fafafa; /* Fundo alternado suave */
        }

        tbody tr:hover {
            background-color: #f0f0f0; /* Destaque ao passar o mouse */
        } 

        /* Estilos para mensagens de erro e sucesso */
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
    </style>
</head>
<body>
    <header>
        <div class="logo-container">
            <img src="img/tatto.jpeg" alt="Logo do Estúdio" class="logo">
        </div>
    </header>

    <div class="container">
        <!-- Seção de filtro -->
        <div class="container">
            <h2>Filtrar Agendamentos</h2>
            <form id="filter-form" method="GET" action="horarios_agendados.php">
                <label for="filter-date">Data:</label>
                <input type="date" id="filter-date" name="filter_date" value="<?= htmlspecialchars($_GET['filter_date'] ?? '') ?>">
                <button type="submit">Filtrar</button>
            </form>
        </div>
        
        <h2>Horários Já Agendados</h2>
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
                        <?php include 'fetch_agendamentos.php'; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Botão para voltar à página de agendamento -->
        <div class="container">
            <a href="agendamento.html" class="button">Voltar ao Agendamento</a>
        </div>
    </div>
</body>
</html>