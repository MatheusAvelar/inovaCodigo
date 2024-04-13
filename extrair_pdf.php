<?php
require_once('pdfparser/src/Smalot/PdfParser/Parser.php');
require_once('pdfparser/src/Smalot/PdfParser/Document.php');
require_once('pdfparser/src/Smalot/PdfParser/Object.php');
require_once('pdfparser/src/Smalot/PdfParser/Exception.php');
require_once('pdfparser/src/Smalot/PdfParser/Resource.php');
require_once('pdfparser/src/Smalot/PdfParser/Element.php');
require_once('pdfparser/src/Smalot/PdfParser/Encoding.php');

use Smalot\PdfParser\Parser;

// Caminho para o arquivo PDF
$pdfPath = 'caminho/do/seu/arquivo.pdf';

// Crie uma instância do PdfParser
$parser = new Parser();

// Parse o PDF e obtenha um objeto Pdf
$pdf = $parser->parseFile($pdfPath);

// Extrai o texto do PDF
$text = $pdf->getText();

// Encontra o nome do pagador e o CPF
$nomePagador = '';
$cpfPagador = '';

// Encontra padrões para o nome do pagador e CPF
if (preg_match('/Nome do Pagador: (.*) CPF\/CNPJ do Pagador: (\d{3}\.\d{3}\.\d{3}-\d{2})/', $text, $matches)) {
    $nomePagador = $matches[1];
    $cpfPagador = $matches[2];
}

// Exibe as informações extraídas
echo "Nome do Pagador: " . $nomePagador . "\n";
echo "CPF do Pagador: " . $cpfPagador . "\n";
?>