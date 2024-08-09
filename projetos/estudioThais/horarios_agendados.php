<?php
session_start();
include 'php/verificar_perfil.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Horários Agendados</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdn.sheetjs.com/xlsx-latest/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
                <li><a href="agendamento.php">Agendamento</a></li>
                <li><a href="php/logout.php">Sair</a></li>
            </ul> 
        </nav>
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

            <button type="submit" class="button" id="filter-button">
                <i class="fas fa-search"></i>
            </button>
            <button type="button" class="button" id="export-button">
                <i class="fas fa-file-excel"></i>
            </button>
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
        </div>
    </div>

    <script>
        document.getElementById('export-button').addEventListener('click', function() {
            console.log('Exportar botão clicado');

            const form = document.getElementById('filter-form');
            const filterData = new FormData(form);

            // Converte os dados do formulário para uma string de query
            const queryString = new URLSearchParams(filterData).toString();
            console.log('Query string gerada:', queryString);

            // Faz a requisição para o PHP e busca os dados
            fetch('php/export_agendamentos.php?' + queryString)
            .then(response => response.text())  // Use text() para ver o conteúdo bruto
            .then(data => {
                console.log('Dados recebidos (texto bruto):', data);

                try {
                    // Tente converter o texto para JSON
                    const jsonData = JSON.parse(data);
                    console.log('Dados recebidos (JSON):', jsonData);

                    // Adiciona os cabeçalhos das colunas
                    const headers = ['Tatuador', 'Maca', 'Data', 'H.Inicial', 'H.Final'];

                    // Cria uma planilha com os dados
                    const worksheet = XLSX.utils.json_to_sheet(jsonData, {header: headers});
                    const workbook = XLSX.utils.book_new();
                    XLSX.utils.book_append_sheet(workbook, worksheet, "Agendamentos");

                    // Gera o arquivo e força o download
                    XLSX.writeFile(workbook, "agendamentos_filtrados.xlsx");

                    console.log('Arquivo Excel gerado e baixado.');
                } catch (error) {
                    console.error('Erro ao interpretar JSON:', error);
                }
            })
            .catch(error => {
                console.error('Erro na requisição ou na geração do arquivo:', error);
            });
        });
    </script>

</body>
</html>
