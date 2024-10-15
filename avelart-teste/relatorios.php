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
            box-shadow: 0px 8px 16px rgba(0,0,0,0.2);
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
    
        <div class="grid">
            <div class="maca">
                <!-- Seção de Filtros -->
                <!--<div class="row mb-4">
                    <div class="col-md-4">
                        <label for="mes">Selecionar Mês:</label>
                        <select class="form-control" id="mes">
                            <option value="">Selecione um mês</option>
                            <option value="01">Janeiro</option>
                            <option value="02">Fevereiro</option>
                            <option value="03">Março</option>
                            <option value="04">Abril</option>
                            <option value="05">Maio</option>
                            <option value="06">Junho</option>
                            <option value="07">Julho</option>
                            <option value="08">Agosto</option>
                            <option value="09">Setembro</option>
                            <option value="10">Outubro</option>
                            <option value="11">Novembro</option>
                            <option value="12">Dezembro</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="ano">Selecionar Ano:</label>
                        <input type="number" class="form-control" id="ano" min="2020" value="<?php echo date('Y'); ?>" max="<?php echo date('Y'); ?>">
                    </div>
                    <div class="col-md-2 align-self-end">
                        <button class="btn btn-primary" onclick="filtrarDados()">Filtrar</button>
                    </div>
                </div>-->

                <!-- Seção de Métricas -->
                <!-- <div class="row"> -->
                    <!-- Total de Agendamentos -->
                    <!--<div class="col-md-3">
                        <div class="card text-white bg-primary mb-3">
                            <div class="card-header">Total de Agendamentos</div>
                            <div class="card-body">
                                <h5 class="card-title" id="totalAgendamentos">Carregando...</h5>
                            </div>
                        </div>
                    </div>-->
                    
                    <!-- Total Faturado -->
                    <!--<div class="col-md-3">
                        <div class="card text-white bg-success mb-3">
                            <div class="card-header">Total Faturado</div>
                            <div class="card-body">
                                <h5 class="card-title" id="totalFaturado">Carregando...</h5>
                            </div>
                        </div>
                    </div>-->
                    
                    <!-- Cancelamentos -->
                    <!--<div class="col-md-3">
                        <div class="card text-white bg-danger mb-3">
                            <div class="card-header">Cancelamentos</div>
                            <div class="card-body">
                                <h5 class="card-title" id="totalCancelamentos">Carregando...</h5>
                            </div>
                        </div>
                    </div>
                </div>-->
                

                <!-- Exibição de gráfico -->
                <!-- <canvas id="agendamentosChart" width="400" height="200"></canvas> -->
                <!-- Exibição de gráfico de agendamentos por tatuador -->
                <!-- <canvas id="agendamentosTatuadorChart" width="400" height="200"></canvas> -->
                <form method="POST" action="php/get_dados_relatorio.php">
                    <label for="inicio">Data de Início:</label>
                    <input type="date" id="inicio" name="inicio"> 

                    <label for="fim">Data de Fim:</label>
                    <input type="date" id="fim" name="fim">

                    <label for="filter_tatuador">Tatuador:</label>
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

                    <label for="opcao_total">Tipo de Relatório:</label>
                    <select id="opcao_total" name="opcao_total">
                        <option value="faturado">Total Faturado</option>
                        <option value="recebido_estudio">Total Recebido pelo Estúdio</option>
                    </select>

                    <button type="submit">Gerar Relatório</button>
                </form>

            </div>
        </div>

        <script>
        function filtrarDados() {
            const mes = document.getElementById('mes').value;
            const ano = document.getElementById('ano').value;
            atualizarMetricas(mes, ano);
            atualizarGrafico(mes, ano);
            atualizarGraficoTatuadores(mes, ano);
        }


        function atualizarMetricas(mes, ano) {
            fetch(`get_data.php?action=metricas&mes=${mes}&ano=${ano}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('totalAgendamentos').textContent = data.total_agendamentos;
                    document.getElementById('totalFaturado').textContent = 'R$ ' + parseFloat(data.total_faturado).toLocaleString('pt-BR', { minimumFractionDigits: 2 });
                    document.getElementById('totalCancelamentos').textContent = data.total_cancelamentos;
                })
                .catch(error => console.error('Erro ao atualizar métricas:', error));
        }

        function atualizarGrafico(mes, ano) {
            fetch(`get_data.php?action=graficos&mes=${mes}&ano=${ano}`)
                .then(response => response.json())
                .then(data => {
                    var ctx = document.getElementById('agendamentosChart').getContext('2d');
                    window.agendamentosChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: data.labels,
                            datasets: [{
                                label: 'Agendamentos',
                                data: data.agendamentos,
                                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                borderColor: 'rgba(54, 162, 235, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                })
                .catch(error => console.error('Erro ao atualizar gráfico:', error));
        }

        // Função para atualizar o gráfico de agendamentos por tatuador
        function atualizarGraficoTatuadores(mes,ano) {
            fetch(`get_data.php?action=graficos_tatuadores&mes=${mes}&ano=${ano}`)
                .then(response => response.json())
                .then(data => {
                    var ctx = document.getElementById('agendamentosTatuadorChart').getContext('2d');
                    window.agendamentosTatuadorChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: data.labels,
                            datasets: [{
                                label: 'Agendamentos por Tatuador',
                                data: data.tatuadores,
                                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                })
                .catch(error => console.error('Erro ao atualizar gráfico de tatuadores:', error));
        }

        // Inicializar dados ao carregar a página
        window.onload = function() {
            atualizarMetricas('', '');
            atualizarGrafico('', '');
            atualizarGraficoTatuadores('','');
            // Atualizar dados a cada 10 segundos (opcional)
            setInterval(filtrarDados, 10000); // 10000 ms = 10 segundos
        };
        </script>

    </div>
</body>
</html>
