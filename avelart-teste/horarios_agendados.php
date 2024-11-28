<?php
session_start();
include 'php/verificar_perfil.php';

// Verifica se há mensagem de status na sessão
$status = isset($_SESSION['status']) ? $_SESSION['status'] : null;
$message = isset($_SESSION['message']) ? $_SESSION['message'] : null;

// Limpa as mensagens de status da sessão após exibir
unset($_SESSION['status'], $_SESSION['message']);
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Horários Agendados</title>
    <link rel="icon" href="img/ico.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdn.sheetjs.com/xlsx-latest/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        #menu ul li {
            display: inline-block;
        }

        /* Estilo básico do dropdown */
        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
            z-index: 1;
        }

        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .dropdown-content a:hover {
            background-color: #f1f1f1;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        /* Estilo para a engrenagem */
        .settings-icon {
            font-size: 18px;
        }

        #filter-actions-form {
            display: flex;
            flex-direction: column;
            gap: 20px;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }

        #filters-container {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            padding: 15px;
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 8px;
        }

        #filters-container div {
            flex: 1 1 calc(33.333% - 15px);
            min-width: 200px;
        }

        #filters-container label {
            display: block;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
        }

        #filters-container select,
        #filters-container input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
            background-color: #fff;
            transition: border-color 0.2s ease-in-out;
        }

        #filters-container select:hover,
        #filters-container input:hover {
            border-color: #ff6f61;
        }

        #actions-container {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            padding: 15px;
            background-color: #f4f4f4;
            border: 1px solid #ddd;
            border-radius: 8px;
        }

        #actions-container .button {
            padding: 12px 20px;
            font-size: 16px;
            font-weight: bold;
            color: #fff;
            background-color: #ff6f61;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        #actions-container .button:hover {
            background-color: #e65c50;
        }

        #actions-container .button i {
            margin-right: 5px;
        }

        @media (max-width: 768px) {
            #filters-container {
                flex-direction: column;
            }

            #filters-container div {
                flex: 1 1 100%;
            }

            #actions-container {
                justify-content: center;
            }

            #actions-container .button {
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <header>
        <div class="logo-container">
            <a href="https://avelart.inovacodigo.com.br/agendamento.php">
                <img src="img/tatto.jpeg" alt="Logo do Estúdio" class="logo">
            </a>
        </div>
        <div class="welcome-message">
            Bem Vindo, <?= htmlspecialchars($_SESSION['usuario_nome']) ?>!
        </div>
    </header>

    <div class="container">
        <nav id="menu">
            <ul>
                <li><a href="termos_enviados.php">Termos Preenchidos</a></li>
                <li><a href="agendamento.php">Agendamento</a></li>
                <?php if ($perfil_id == 2) : ?>
                    <li class="dropdown">
                        <a href="javascript:void(0)">
                            <i class="fas fa-cog settings-icon"></i>
                        </a>
                        <div class="dropdown-content">
                            <a href="criar_acesso.php">Criar Acesso</a>
                            <a href="usuarios_estudio.php">Usuários</a>
                            <a href="visao_conflitos.php">Conflitos</a>
                        </div>
                    </li>
                <?php endif; ?>
                <li><a href="php/logout.php">Sair</a></li>
            </ul>
        </nav>
        <br>
        <div id="message-container">
            <?php if ($status && $message) : ?>
                <div class="message <?= $status ?>">
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>
        </div>
        <h2>Horários Agendados</h2>
        <form id="filter-actions-form" method="GET" action="horarios_agendados.php">
            <div id="filters-container">
                <div>
                    <label for="filter-month">Mês:</label>
                    <select id="filter-month" name="filter_month">
                        <option value="">Todos os Meses</option>
                        <?php
                        foreach ($months as $value => $name) {
                            $selected = (isset($_GET['filter_month']) && $_GET['filter_month'] == $value) ? 'selected' : '';
                            echo "<option value=\"$value\" $selected>$name</option>";
                        }
                        ?>
                    </select>
                </div>

                <div>
                    <label for="filter-maca">Maca:</label>
                    <select id="filter-maca" name="filter_maca">
                        <option value="">Todas as Macas</option>
                        <option value="1" <?= isset($_GET['filter_maca']) && $_GET['filter_maca'] == '1' ? 'selected' : '' ?>>Maca 1</option>
                        <option value="2" <?= isset($_GET['filter_maca']) && $_GET['filter_maca'] == '2' ? 'selected' : '' ?>>Maca 2</option>
                        <option value="3" <?= isset($_GET['filter_maca']) && $_GET['filter_maca'] == '3' ? 'selected' : '' ?>>Maca 3</option>
                        <option value="4" <?= isset($_GET['filter_maca']) && $_GET['filter_maca'] == '4' ? 'selected' : '' ?>>Maca 4</option>
                        <option value="5" <?= isset($_GET['filter_maca']) && $_GET['filter_maca'] == '5' ? 'selected' : '' ?>>Sala de Atendimento Íntimo</option>
                    </select>
                </div>

                <div>
                    <label for="filter-tatuador">Tatuador:</label>
                    <select id="filter-tatuador" name="filter_tatuador">
                        <option value="">Todos os Tatuadores</option>
                        <!-- Inclua a lógica PHP para listar os tatuadores -->
                    </select>
                </div>

                <?php if ($perfil_id == 2) : ?>
                    <div>
                        <label for="filter-status">Status:</label>
                        <select id="filter-status" name="filter_status">
                            <option value="1">Ativo</option>
                            <option value="0">Inativo</option>
                        </select>
                    </div>
                <?php endif; ?>
            </div>

            <div id="actions-container">
                <button type="submit" class="button">
                    <i class="fas fa-search"></i> Filtrar
                </button>

                <?php if ($perfil_id == 2) : ?>
                    <button type="button" class="button" id="export-button">
                        <i class="fa-solid fa-file-csv"></i> Exportar
                    </button>
                <?php endif; ?>
            </div>
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
                .then(response => response.text()) // Use text() para ver o conteúdo bruto
                .then(data => {
                    console.log('Dados recebidos (texto bruto):', data);

                    try {
                        // Tente converter o texto para JSON
                        const jsonData = JSON.parse(data);
                        console.log('Dados recebidos (JSON):', jsonData);

                        // Adiciona os cabeçalhos das colunas
                        const headers = ['tatuador', 'maca', 'data', 'h.inicial', 'h.final', 'nomeCliente', 'estilo', 'tamanho', 'valor', 'formaPagamento', 'sinal', 'descricao'];

                        // Cria uma planilha com os dados
                        const worksheet = XLSX.utils.json_to_sheet(jsonData, {
                            header: headers
                        });
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