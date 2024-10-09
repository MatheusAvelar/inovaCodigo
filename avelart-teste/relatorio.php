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
                <input type="number" class="form-control" id="ano" value="<?php echo date('Y'); ?>" min="2020" max="<?php echo date('Y'); ?>">
            </div>
            <div class="col-md-2 align-self-end">
                <button class="btn btn-primary" onclick="filtrarDados()">Filtrar</button>
            </div>
        </div>

        <!-- Exibição de gráfico -->
        <canvas id="agendamentosChart" width="400" height="200"></canvas>
        <script>
        function filtrarDados() {
            const mes = document.getElementById('mes').value;
            const ano = document.getElementById('ano').value;

            atualizarMetricas(mes, ano);
            atualizarGrafico(mes, ano);
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
