<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Horários Já Agendados</title>
    <style>
        /* Coloque aqui o mesmo estilo do seu código original */
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