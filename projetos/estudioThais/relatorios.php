<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatórios</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        canvas {
            -moz-user-select: none;
            -webkit-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }
    </style>
</head>
<body>
    <h1>Relatórios de Agendamentos</h1>

    <h2>Gráfico de Pizza - Forma de Pagamento</h2>
    <canvas id="pieChart"></canvas>

    <h2>Gráfico de Barra - Valor por Estilo</h2>
    <canvas id="barChart"></canvas>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetch('get_agendamentos.php')
                .then(response => response.json())
                .then(data => {
                    console.log('Dados recebidos:', data); // Adiciona log para verificar dados

                    if (!data || data.length === 0) {
                        console.error('Nenhum dado encontrado.');
                        return;
                    }

                    // Processa os dados para o gráfico de pizza
                    const formaPagamento = {};
                    data.forEach(agendamento => {
                        const pagamento = agendamento.forma_pagamento;
                        formaPagamento[pagamento] = (formaPagamento[pagamento] || 0) + 1;
                    });

                    const pieLabels = Object.keys(formaPagamento);
                    const pieData = Object.values(formaPagamento);

                    // Gráfico de Pizza
                    const ctxPie = document.getElementById('pieChart').getContext('2d');
                    new Chart(ctxPie, {
                        type: 'pie',
                        data: {
                            labels: pieLabels,
                            datasets: [{
                                label: 'Forma de Pagamento',
                                data: pieData,
                                backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56']
                            }]
                        }
                    });

                    // Processa os dados para o gráfico de barra
                    const valorEstilo = {};
                    data.forEach(agendamento => {
                        const estilo = agendamento.estilo;
                        valorEstilo[estilo] = (valorEstilo[estilo] || 0) + parseFloat(agendamento.valor);
                    });

                    const barLabels = Object.keys(valorEstilo);
                    const barData = Object.values(valorEstilo);

                    // Gráfico de Barra
                    const ctxBar = document.getElementById('barChart').getContext('2d');
                    new Chart(ctxBar, {
                        type: 'bar',
                        data: {
                            labels: barLabels,
                            datasets: [{
                                label: 'Valor por Estilo',
                                data: barData,
                                backgroundColor: '#FF6384'
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
                .catch(error => {
                    console.error('Erro ao carregar os dados:', error);
                });
        });
    </script>
</body>
</html>
