<?php
session_start();
include 'php/utils.php';

if ($_SESSION['perfil_id'] != 2) {
    header("Location: agendamento.php");
    exit();
}

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

        <!-- Exemplo de visualização de métricas -->
        <div class="row">
            <div class="col-sm-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Total de Agendamentos</h5>
                        <p class="card-text">
                            <?php
                            $result = $conn->query("SELECT COUNT(*) as total_agendamentos FROM agendamentos");
                            $row = $result->fetch_assoc();
                            echo $row['total_agendamentos'];
                            ?>
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-sm-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Total Faturado</h5>
                        <p class="card-text">
                            <?php
                            $result = $conn->query("SELECT SUM(valor) as total_faturado FROM agendamentos");
                            $row = $result->fetch_assoc();
                            echo "R$ " . number_format($row['total_faturado'], 2, ',', '.');
                            ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Exibição de gráfico -->
        <canvas id="agendamentosChart" width="400" height="200"></canvas>
        <script>
        var ctx = document.getElementById('agendamentosChart').getContext('2d');
        var agendamentosChart = new Chart(ctx, {
            type: 'bar', // Tipo de gráfico
            data: {
                labels: ['Janeiro', 'Fevereiro', 'Março'], // Mude para os meses reais
                datasets: [{
                    label: '# de Agendamentos',
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
        });
        </script>

    </div>
</body>
</html>
