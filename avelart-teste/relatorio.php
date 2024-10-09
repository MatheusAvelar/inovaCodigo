<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de Agendamentos</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Dashboard de Agendamentos</h1>
        
        <canvas id="myChart"></canvas>
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
        
        <!-- Seção de Gráficos -->
        <div class="row">
            <div class="col-md-12">
                <canvas id="agendamentosChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Scripts JavaScript -->
    <script>
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
                    
                    // Verifica se o gráfico existe e o destrói se necessário
                    if (window.agendamentosChart) {
                        window.agendamentosChart.destroy();
                    }
                    console.log(window.agendamentosChart);

                    var ctx = document.getElementById('myChart').getContext('2d');
                    window.agendamentosChart = new Chart(ctx, {
                        type: 'bar', // ou outro tipo de gráfico
                        data: {
                            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
                            datasets: [{
                                label: 'Agendamentos',
                                data: [65, 59, 80, 81, 56, 55, 40],
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
            setInterval(filtrarDados, 30); // 300000 ms = 5 minutos
        };
    </script>
</body>
</html>
