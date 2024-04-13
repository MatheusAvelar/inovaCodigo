<?php
require('fpdf.php');

// Função para extrair o título do PDF
function extractPdfTitle($pdfPath) {
    // Instancia um objeto FPDF
    $pdf = new FPDF();

    // Abre o arquivo PDF
    $pdf->Open($pdfPath);

    // Retorna o título do PDF
    return $pdf->title;
}

// Verifica se um arquivo foi enviado
if(isset($_FILES['pdfFile'])) {
    $file = $_FILES['pdfFile'];

    // Verifica se não houve erro no upload
    if($file['error'] === 0) {
        // Move o arquivo temporário para um local permanente
        $tempPath = $file['tmp_name'];
        $targetPath = 'arquivos/' . $file['name']; // Local onde o arquivo será salvo
        move_uploaded_file($tempPath, $targetPath);

        // Extrai o título do PDF
        $pdfTitle = extractPdfTitle($targetPath);

        // Exibe o título do PDF
        echo "<h2>Título do PDF:</h2>";
        echo "<p>$pdfTitle</p>";
    } else {
        echo "Erro ao fazer upload do arquivo.";
    }
}
?>