<?php
session_start();
include 'php/utils.php';

try {
    $conn = conectaBanco();
} catch (Exception $e) {
    die("Erro: " . $e->getMessage());
}

// Verifica se há mensagem de status na sessão
$status = isset($_SESSION['status']) ? $_SESSION['status'] : null;
$message = isset($_SESSION['message']) ? $_SESSION['message'] : null;

// Limpa as mensagens de status da sessão após exibir
unset($_SESSION['status'], $_SESSION['message']);

$months = [
    "01" => "Janeiro",
    "02" => "Fevereiro",
    "03" => "Março",
    "04" => "Abril",
    "05" => "Maio",
    "06" => "Junho",
    "07" => "Julho",
    "08" => "Agosto",
    "09" => "Setembro",
    "10" => "Outubro",
    "11" => "Novembro",
    "12" => "Dezembro",
];
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de Agendamentos</title>
    <link rel="icon" href="img/ico.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/style.css?v=1.0">
    <script src="https://cdn.sheetjs.com/xlsx-latest/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
    </style>
</head>

<body>
    <header>
        <div class="logo-container">
            <a href="https://avelart-teste.inovacodigo.com.br/agendamento.php">
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
                <li><a href="horarios_agendados.php">Horários Agendados</a></li>
                <?php if ($_SESSION['perfil_id'] == 2) : ?>
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
            <?php if (isset($status) && isset($message)) : ?>
                <div class="message <?= $status ?>">
                    <?= $message ?>
                </div>
            <?php endif; ?>
        </div>

        <h2>Relatório</h2>
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
                    <select id="filter_tatuador" name="filter_tatuador">
                        <option value="">Todos os Tatuadores</option>
                        <?php
                        // Carregar a lista de tatuadores
                        try {
                            $query = "SELECT id, nome FROM usuarioEstudio ORDER BY nome ASC";
                            $result = $conn->query($query);

                            // Verifica se retornou algum resultado
                            if ($result->num_rows > 0) {
                                // Loop pelos resultados e imprime as opções do select
                                while ($row = $result->fetch_assoc()) {
                                    echo "<option value='" . htmlspecialchars($row['id']) . "'>" . htmlspecialchars($row['nome']) . "</option>";
                                }
                            } else {
                                // Caso não haja tatuadores
                                echo "<option value=''>Nenhum tatuador encontrado</option>";
                            }
                        } catch (Exception $e) {
                            // Em caso de erro na conexão ou na query, exibe uma opção com erro
                            echo "<option value=''>Erro ao carregar tatuadores</option>";
                        }
                        ?>
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
                <div>
                    <label for="inicio">Data de Início:</label>
                    <input type="date" id="inicio" name="inicio">
                </div>

                <label for="fim">Data de Fim:</label>
                <input type="date" id="fim" name="fim">

                <label for="tipo_relatorio">Tipo de Relatório:</label>
                <select id="tipo_relatorio" name="tipo_relatorio">
                    <option value="faturado">Total Faturado</option>
                    <option value="recebido_estudio">Total Recebido pelo Estúdio</option>
                </select>
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
                            <th>Valor</th>
                            <th>Tipo Relatório</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $filter_date = $_GET['filter_date'] ?? '';
                        $filter_maca = $_GET['filter_maca'] ?? '';

                        // Inclua o arquivo com a lógica para buscar agendamentos com filtros
                        include 'php/get_dados_relatorio_tatuador.php';
                        ?>
                    </tbody>
                </table><br>
                <!-- Exibe a contagem de registros -->
                <div class="record-count">
                    <?php echo "Total de Registros: " . $total_records; ?>
                </div>
            </div>
        </div>

        
    </div>
</body>

</html>