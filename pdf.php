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

    <!-- Tabela para exibir os dados extraídos -->
    <table id="resultTable" style="margin: 20px auto; border-collapse: collapse; border: 1px solid black;">
        <thead>
            <tr>
                <th>Valor Total</th>
                <th>Chave de Acesso</th>
                <th>Natureza da Operação</th>
                <th>Inscrição Estadual</th>
                <th>CNPJ</th>
                <th>Base de Cálculo do ICMS</th>
                <th>Valor do ICMS</th>
                <th>V. Total Produtos</th>
                <th>Valor da COFINS</th>
                <th>V. Total da Nota</th>
                <th>Informações Complementares</th>
            </tr>
        </thead>
        <tbody>
            <!-- Os dados extraídos serão adicionados aqui -->
        </tbody>
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

                let allText = '';

                for (let i = 1; i <= pdf.numPages; i++) {
                    const page = await pdf.getPage(i);
                    const textContent = await page.getTextContent();
                    const textItems = textContent.items.map(item => item.str);
                    const text = textItems.join(' ');
                    allText += text + '\n';
                }

                const dataToExtract = {
                    'valorTotal': /VALOR TOTAL: R\$\s*([\d,]+)/,
                    'chaveDeAcesso': /CHAVE DE ACESSO\s*([\d\s]+)/,
                    'naturezaDaOperacao': /NATUREZA DA OPERAÇÃO (.+?)\s*INSCRIÇÃO ESTADUAL/,
                    'InscricaoEstadual': /INSCRIÇÃO ESTADUAL (\d{9})/,
                    'cnpj': /CNPJ (\d{2}\.\d{3}\.\d{3}\/\d{4}-\d{2})/,
                    'baseDeCalculoDoIcms': /BASE DE CÁLC\. DO ICMS\s*([\d,]+)/,
                    'valorDoIcms': /VALOR DO ICMS\s*([\d,]+)/,
                    'vTotalProdutos': /V\. TOTAL PRODUTOS\s*([\d,]+)/,
                    'valorDaCofins': /VALOR DA COFINS\s*([\d,]+)/,
                    'vTotalDaNota': /V\. TOTAL DA NOTA\s*([\d,]+)/,
                    'InformacoesComplementares': /INFORMAÇÕES COMPLEMENTARES([\s\S]+)/
                };

                const resultTable = document.getElementById('resultTable');
                const tbody = resultTable.getElementsByTagName('tbody')[0];
                tbody.innerHTML = ''; // Limpar o conteúdo existente da tabela

                const rowData = {}; // Objeto para armazenar os dados extraídos

                // Extrair os dados
                for (const [description, regex] of Object.entries(dataToExtract)) {
                    const match = allText.match(regex);
                    if (match) {
                        const value = match[1].trim();
                        rowData[description] = value;
                    }
                }

                // Inserir os dados na tabela
                const row = tbody.insertRow();
                const columns = ['valorTotal', 'chaveDeAcesso', 'naturezaDaOperacao', 'InscricaoEstadual', 'cnpj', 'baseDeCalculoDoIcms', 'valorDoIcms', 'vTotalProdutos', 'valorDaCofins', 'vTotalDaNota', 'InformacoesComplementares'];
                columns.forEach(column => {
                    const value = rowData[column] || ''; // Valor vazio se não houver correspondência
                    const cell = row.insertCell();
                    cell.textContent = value;
                });
            };

            reader.readAsArrayBuffer(file);
        }

    </script>
</body>
</html>