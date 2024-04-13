<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="icon" href="img/ico.ico" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" />
    <title>Inova Código - Apropriação de Horas - Controle de Demandas</title>
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-1516963750502427" crossorigin="anonymous"></script>
    <title>Extrair Dados de PDF</title>
</head>

<body>
    <header class="navbar">
        <nav class="nav-links">
            <a href="index.html">Home</a>
            <a href="projetos.html">Projetos</a>
            <a href="publicacoes.html">Publicações</a>
            <a href="gerador.html">Geradores</a>
            <a href="projetos/controleFinanceiro/login.php">Controle Financeiro</a>
            <a href="kanban.html">Kanban</a>
            <a href="jogos.html">Jogos</a>
            <a href="https://matheusavelar.github.io/">Currículo</a>
            <a href="apropriacao.php">Apropriação de Horas</a>
            <a href="receitas.html">Receitas</a>
            <a href="logout.php">Sair</a>
        </nav>
    </header>

    <center>
        <h1>Extrair Dados de PDF</h1>
    </center>
    <form action="" method="post" enctype="multipart/form-data">
        <input type="file" name="pdfFile" accept=".pdf">
        <button type="submit" name="submit">Extrair Dados</button>
    </form>

    <?php
    // Verifica se o formulário foi submetido
    if(isset($_POST['submit'])) {
        require_once('pdfparser/src/Smalot/PdfParser/Parser.php');
        require_once('pdfparser/src/Smalot/PdfParser/Document.php');
        require_once('pdfparser/src/Smalot/PdfParser/PDFObject.php');
        require_once('pdfparser/src/Smalot/PdfParser/Exception.php');
        require_once('pdfparser/src/Smalot/PdfParser/Resource.php');
        require_once('pdfparser/src/Smalot/PdfParser/Element.php');
        require_once('pdfparser/src/Smalot/PdfParser/Encoding.php');
        require_once('pdfparser/src/Smalot/PdfParser/Parser.php');

        // Caminho para o arquivo PDF
        $pdfPath = $_FILES['pdfFile']['tmp_name'];

        // Crie uma instância do PdfParser
        $parser = new Smalot\PdfParser\Parser();

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
        echo "<h2>Informações do Boleto:</h2>";
        echo "<p>Nome do Pagador: " . $nomePagador . "</p>";
        echo "<p>CPF do Pagador: " . $cpfPagador . "</p>";
    }
    ?>

</body>

</html>