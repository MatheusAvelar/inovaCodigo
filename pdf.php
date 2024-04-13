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
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-1516963750502427" crossorigin="anonymous"></script>
    <title>Extrair Dados de PDF</title>
    <!-- Inclua o PDF.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.9.359/pdf.min.js"></script>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
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
    <div class="consulta-cep">
        <input type="file" id="pdfInput" accept=".pdf">
        <button onclick="extractData()">Extrair Dados</button> 
    </div>

    <!-- Tabela para exibir a data de vencimento -->
    <table id="resultTable" style="margin: 20px auto; border-collapse: collapse; border: 1px solid black;">
        <tr>
            <th>Data de Vencimento</th>
        </tr>
    </table>

    <script>
        async function extractData() {
            const fileInput = document.getElementById('pdfInput');
            const file = fileInput.files[0];

            if (!file) {
                alert('Selecione um arquivo PDF.');
                return;
            }

            const reader = new FileReader();

            reader.onload = async function(event) {
                const arrayBuffer = event.target.result;
                const pdf = await pdfjsLib.getDocument({ data: arrayBuffer }).promise;

                const page = await pdf.getPage(1);
                const textContent = await page.getTextContent();
                const textItems = textContent.items.map(item => item.str);

                const text = textItems.join(' ');

                // Padrão para procurar uma data no formato DD/MM/AAAA
                const dateRegex = /(\d{2}\/\d{2}\/\d{4})/;

                // Procura pela data de vencimento no texto
                const dateMatch = text.match(dateRegex);

                const vencimento = dateMatch ? dateMatch[1] : 'Data de vencimento não encontrada';

                // Exibir a data de vencimento na tabela
                const resultTable = document.getElementById('resultTable');
                const row = resultTable.insertRow(-1); // Insere uma nova linha na tabela
                const cell = row.insertCell(0); // Insere uma nova célula na linha
                cell.textContent = vencimento;
            };

            reader.readAsArrayBuffer(file);
        }
    </script>
</body>
</html>
