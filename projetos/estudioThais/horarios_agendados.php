<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Horários Já Agendados</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <div class="logo-container">
            <img src="img/tatto.jpeg" alt="Logo do Estúdio" class="logo">
        </div>
        <div class="logout-container">
        <form action="php/logout.php" method="post">
            <button type="submit" class="logout-button">Sair</button>
        </form>
    </header>

    <div class="container">
        <h2>Horários Agendados</h2>
        <form id="filter-form" method="GET" action="horarios_agendados.php">
            <label for="filter-date">Data:</label>
            <input type="date" id="filter-date" name="filter_date" value="<?= htmlspecialchars($_GET['filter_date'] ?? '') ?>">

            <label for="filter-maca">Maca:</label>
            <select id="filter-maca" name="filter_maca">
                <option value="">Todas as Macas</option>
                <option value="1" <?= isset($_GET['filter_maca']) && $_GET['filter_maca'] == '1' ? 'selected' : '' ?>>Maca 1</option>
                <option value="2" <?= isset($_GET['filter_maca']) && $_GET['filter_maca'] == '2' ? 'selected' : '' ?>>Maca 2</option>
                <option value="3" <?= isset($_GET['filter_maca']) && $_GET['filter_maca'] == '3' ? 'selected' : '' ?>>Maca 3</option>
                <option value="4" <?= isset($_GET['filter_maca']) && $_GET['filter_maca'] == '4' ? 'selected' : '' ?>>Maca 4</option>
            </select>

            <button type="submit" class="button">Filtrar</button>
        </form>
        <div class="grid">
            <div class="maca">
                <table>
                    <thead>
                        <tr>
                            <th>Tatuador</th>
                            <th>Maca</th>
                            <th>Data</th>
                            <th>H. Inicial</th>
                            <th>H. Final</th>
                            <th>Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $filter_date = $_GET['filter_date'] ?? '';
                        $filter_maca = $_GET['filter_maca'] ?? '';

                        // Inclua o arquivo com a lógica para buscar agendamentos com filtros
                        include 'php/fetch_agendamentos.php';
                        ?>
                    </tbody>
                </table>
            </div>
            <a href="agendamento.php" class="button">Voltar ao Agendamento</a>
        </div>
    </div>
</body>
</html>
