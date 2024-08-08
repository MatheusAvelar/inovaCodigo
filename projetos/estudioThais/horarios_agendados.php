<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Horários Agendados</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdn.sheetjs.com/xlsx-latest/xlsx.full.min.js"></script>
</head>
<body>
    <header>
        <div class="logo-container">
            <a href="https://inovacodigo.com.br/projetos/estudioThais/agendamento.php">
                <img src="img/tatto.jpeg" alt="Logo do Estúdio" class="logo">
            </a>
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

            <label for="filter-tatuador">Tatuador:</label>
            <select id="filter-tatuador" name="filter_tatuador">
                <option value="">Todos os Tatuadores</option>
                <?php
                // Carregar a lista de tatuadores
                // Inclua a lógica para obter os tatuadores do banco de dados
                include 'php/get_tatuadores.php';
                ?>
            </select>

            <button type="submit" class="button">Filtrar</button>
            <button type="button" class="button" id="export-button">Exportar</button>
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

    <script>
        document.getElementById('export-button').addEventListener('click', function() {
            const form = document.getElementById('filter-form');
            form.action = 'export_agendamentos.php'; // Define a ação para exportar
            form.method = 'GET'; // Define o método GET
            form.submit(); // Submete o formulário
        });

        document.getElementById('export-button').addEventListener('click', function() {
        const form = document.getElementById('filter-form');
        const filterData = new FormData(form);

        fetch('php/fetch_agendamentos.php?' + new URLSearchParams(filterData))
            .then(response => response.json())
            .then(data => {
                // Adiciona os cabeçalhos das colunas
                const headers = ['Tatuador', 'Maca', 'Data', 'H. Inicial', 'H. Final'];
                
                // Cria uma planilha com os dados
                const worksheet = XLSX.utils.json_to_sheet(data, {header: headers});
                const workbook = XLSX.utils.book_new();
                XLSX.utils.book_append_sheet(workbook, worksheet, "Agendamentos");

                // Gera o arquivo e força o download
                XLSX.writeFile(workbook, "agendamentos_filtrados.xlsx");
            })
            .catch(error => console.error('Error:', error));
    });
    </script>
</body>
</html>
