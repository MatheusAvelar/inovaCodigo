<?php
// Inclua o arquivo de biblioteca pChart
require_once("php/pChart/pData.class");
require_once("php/pChart/pChart.class");

// Dados para o gráfico
$data = [
    'Jan' => 120,
    'Feb' => 150,
    'Mar' => 170,
    'Apr' => 130,
    'May' => 180
];

// Criação do objeto de dados
$myData = new pData();
$myData->addPoints(array_values($data), "Valores");
$myData->addPoints(array_keys($data), "Meses");
$myData->setSerieDescription("Meses", "Meses");
$myData->setAbscissa("Meses");

// Criação do gráfico
$myPicture = new pChart(700, 400);
$myPicture->setBackgroundColor(array(255, 255, 255));
$myPicture->setGraphArea(60, 40, 680, 350);

// Desenho das barras
$myPicture->drawScale(array("drawSubTicks" => TRUE));
$myPicture->drawBarChart(array("displayValues" => TRUE, "displayColor" => DISPLAY_AUTO));

// Desenho das linhas de referência
$myPicture->drawLineChart();
$myPicture->drawThreshold(100, array("R" => 255, "G" => 0, "B" => 0, "Alpha" => 50));

// Adicionar títulos e outras anotações
$myPicture->drawText(350, 20, "Gráfico de Barras Avançado", array("FontSize" => 15, "Align" => TEXT_ALIGN_BOTTOMMIDDLE));

// Renderizar a imagem
$myPicture->Render("chart.png");

// Exibir a imagem
header("Content-Type: image/png");
readfile("chart.png");
?>
