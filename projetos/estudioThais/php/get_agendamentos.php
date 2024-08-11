<?php
header('Content-Type: image/png');

$width = 500;
$height = 300;
$barWidth = 40;
$barSpacing = 10;

$data = [
    'A' => 0,
    'B' => 25,
    'C' => 30,
    'D' => 20,
    'E' => 15
];

$image = imagecreatetruecolor($width, $height);
$backgroundColor = imagecolorallocate($image, 255, 255, 255);
$barColor = imagecolorallocate($image, 0, 0, 255);
$axisColor = imagecolorallocate($image, 0, 0, 0);

imagefill($image, 0, 0, $backgroundColor);

// Desenhar o eixo Y
imageline($image, 50, 10, 50, $height - 20, $axisColor);

// Desenhar o eixo X
imageline($image, 50, $height - 20, $width - 10, $height - 20, $axisColor);

$maxValue = max($data);
$scale = ($height - 30) / $maxValue;

$x = 60;

foreach ($data as $label => $value) {
    $barHeight = $value * $scale;
    imagefilledrectangle($image, $x, $height - 20 - $barHeight, $x + $barWidth, $height - 20, $barColor);
    
    // Adicionar rótulo
    imagestring($image, 3, $x + ($barWidth / 4), $height - 20 + 5, $label, $axisColor);

    $x += $barWidth + $barSpacing;
}

imagepng($image);
imagedestroy($image);
?>
