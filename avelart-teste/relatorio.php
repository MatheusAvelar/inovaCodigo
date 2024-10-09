<?php include 'php/utils.php';

try {
    $conn = conectaBanco();
} catch (Exception $e) {
    die("Erro: " . $e->getMessage());
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de Agendamentos</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container">
        <h1>Dashboard de Agendamentos</h1>

        <!-- Seção de Métricas -->
        <div class="row">
            <!-- Total de Agendamentos -->
            <div class="col-md-3">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-header">Total de Agendamentos</div>
                    <div class="card-body">
                        <h5 class="card-title" id="totalAgendamentos">Carregando...</h5>
                    </div>
                </div>
            </div>
            
            <!-- Total Faturado -->
            <div class="col-md-3">
                <div class="card text-white bg-success mb-3">
                    <div class="card-header">Total Faturado</div>
                    <div class="card-body">
                        <h5 class="card-title" id="totalFaturado">Carregando...</h5>
                    </div>
                </div>
            </div>
            
            <!-- Agendamentos por Tatuador -->
            <div class="col-md-3">
                <div class="card text-white bg-warning mb-3">
                    <div class="card-header">Agendamentos por Tatuador</div>
                    <div class="card-body">
                        <h5 class="card-title" id="agendamentosTatuador">Carregando...</h5>
                    </div>
                </div>
            </div>
            
            <!-- Cancelamentos -->
            <div class="col-md-3">
                <div class="card text-white bg-danger mb-3">
                    <div class="card-header">Cancelamentos</div>
                    <div class="card-body">
                        <h5 class="card-title" id="totalCancelamentos">Carregando...</h5>
                    </div>
                </div>
            </div>
        </div>

        <!-- Seção de Métricas -->
        <div class="row">
            <!-- Total de Agendamentos -->
            <div class="col-md-3">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-header">Total de Agendamentos</div>
                    <div class="card-body">
                        <p class="card-text">
                            <?php
                            $result = $conn->query("SELECT COUNT(*) as total_agendamentos FROM agendamentos WHERE status = 'ativo'");
                            $row = $result->fetch_assoc();
                            echo $row['total_agendamentos'];
                            ?>
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Total Faturado -->
            <div class="col-md-3">
                <div class="card text-white bg-success mb-3">
                    <div class="card-header">Total Faturado</div>
                    <div class="card-body">
                        <p class="card-text">
                            <?php
                            $result = $conn->query("SELECT SUM(valor) as total_faturado FROM agendamentos WHERE status = 'ativo'");
                            $row = $result->fetch_assoc();
                            echo "R$ " . number_format($row['total_faturado'], 2, ',', '.');
                            ?>
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Agendamentos por Tatuador 
            <div class="col-md-3">
                <div class="card text-white bg-warning mb-3">
                    <div class="card-header">Agendamentos por Tatuador</div>
                    <div class="card-body">
                        <h5 class="card-title" id="agendamentosTatuador">Carregando...</h5>
                    </div>
                </div>
            </div>-->
            
            <!-- Cancelamentos -->
            <div class="col-md-3">
                <div class="card text-white bg-danger mb-3">
                    <div class="card-header">Cancelamentos</div>
                    <div class="card-body">
                        <p class="card-text">
                            <?php
                            $result = $conn->query("SELECT COUNT(*) as cancelados FROM agendamentos WHERE status = 'inativo'");
                            $row = $result->fetch_assoc();
                            echo $row['cancelados'];
                            ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Seção de Filtros -->
        <div class="row mb-4">
            <div class="col-md-4">
                <label for="periodo">Selecionar Período:</label>
                <select class="form-control" id="periodo">
                    <option value="7">Últimos 7 dias</option>
                    <option value="30" selected>Últimos 30 dias</option>
                    <option value="90">Últimos 90 dias</option>
                </select>
            </div>
            <div class="col-md-2 align-self-end">
                <button class="btn btn-primary" onclick="filtrarDados()">Filtrar</button>
            </div>
        </div>

        <!-- Exibição de gráfico -->
        <canvas id="agendamentosChart" width="400" height="200"></canvas>
        <script>
        /*var ctx = document.getElementById('agendamentosChart').getContext('2d');
        var agendamentosChart = new Chart(ctx, {
            type: 'bar', // Tipo de gráfico
            data: {
                labels: ['Janeiro', 'Fevereiro', 'Março'], // Mude para os meses reais
                datasets: [{
                    label: 'Agendamentos',
                    data: [12, 19, 3], // Dados de exemplo, devem ser dinâmicos
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
        });*/
        // Função para atualizar métricas
        function atualizarMetricas() {
            fetch('get_data.php?action=metricas&periodo=' + document.getElementById('periodo').value)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('totalAgendamentos').textContent = data.total_agendamentos;
                    document.getElementById('totalFaturado').textContent = 'R$ ' + parseFloat(data.total_faturado).toLocaleString('pt-BR', {minimumFractionDigits: 2});
                    document.getElementById('agendamentosTatuador').textContent = data.agendamentos_tatuador;
                    document.getElementById('totalCancelamentos').textContent = data.total_cancelamentos;
                })
                .catch(error => console.error('Erro ao atualizar métricas:', error));
        }
        
        // Função para atualizar o gráfico
        function atualizarGrafico() {
            fetch('get_data.php?action=graficos&periodo=' + document.getElementById('periodo').value)
                .then(response => response.json())
                .then(data => {
                    if (window.agendamentosChart) {
                        window.agendamentosChart.destroy();
                    }
                    
                    var ctx = document.getElementById('agendamentosChart').getContext('2d');
                    window.agendamentosChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: data.labels,
                            datasets: [{
                                label: '# de Agendamentos',
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
        
        // Função para filtrar dados
        function filtrarDados() {
            atualizarMetricas();
            atualizarGrafico();
        }
        
        // Inicializar dados ao carregar a página
        window.onload = function() {
            filtrarDados();
            // Atualizar dados a cada 10 segundos (opcional)
            setInterval(filtrarDados, 10000); // 10000 ms = 10 segundos
        };
        </script>

    </div>
</body>
</html>
