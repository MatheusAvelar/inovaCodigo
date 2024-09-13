<?php
require('parsedown/Parsedown.php'); // Biblioteca Parsedown para conversão de HTML para Markdown

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Receber o texto HTML enviado pelo formulário
    $html_text = $_POST["texto"];
    
    // Instanciar a biblioteca Parsedown
    $Parsedown = new Parsedown();
    
    // Converter o texto HTML para Markdown
    $markdown_text = $Parsedown->text($html_text);
    
    // Exibir o markdown gerado
    echo "<h2>Markdown Gerado:</h2>";
    echo "<pre>" . htmlspecialchars($markdown_text) . "</pre>";
}
?>
