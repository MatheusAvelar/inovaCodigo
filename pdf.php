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
        <tr>
            <th>Data de Vencimento</th>
            <th>Nome do Pagador</th>
            <th>CPF</th>
            <th>Número do Documento</th>
            <th>Valor do Documento</th>
            <th>Nome do Beneficiário</th>
            <th>CNPJ do Beneficiário</th>
            <th>Endereço</th>
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

                let allText = ''; // Variável para armazenar todo o texto do PDF

                // Loop através de todas as páginas do PDF
                for (let i = 1; i <= pdf.numPages; i++) {
                    const page = await pdf.getPage(i);
                    const textContent = await page.getTextContent();
                    const textItems = textContent.items.map(item => item.str);
                    const text = textItems.join(' '); // Texto da página
                    allText += text + '\n'; // Adiciona o texto da página à variável allText
                }

                // Padrões de regex para cada informação específica
                const dateRegex = /(\d{2}\/\d{2}\/\d{4})/g; // Data no formato DD/MM/AAAA
                const nomePagadorRegex = /Nome do Pagador: (.+?)\n/g; // Nome do Pagador
                const cpfRegex = /CPF: (\d{3}\.\d{3}\.\d{3}-\d{2})/g; // CPF no formato XXX.XXX.XXX-XX
                const numDocumentoRegex = /Num\. Documento: (\d+)/g; // Número do Documento
                const valorDocumentoRegex = /Valor do documento: (.+?)\n/g; // Valor do Documento
                const nomeBeneficiarioRegex = /Nome do Beneficiário: (.+?)\n/g; // Nome do Beneficiário
                const cnpjBeneficiarioRegex = /CNPJ Beneficiário: (\d{2}\.\d{3}\.\d{3}\/\d{4}-\d{2})/g; // CNPJ do Beneficiário no formato XX.XXX.XXX/XXXX-XX
                const enderecoRegex = /Endereço: (.+?)\n/g; // Endereço

                // Extrair informações usando regex
                const vencimentos = allText.match(dateRegex);
                const nomesPagador = allText.match(nomePagadorRegex);
                const cpfs = allText.match(cpfRegex);
                const numerosDocumento = allText.match(numDocumentoRegex);
                const valoresDocumento = allText.match(valorDocumentoRegex);
                const nomesBeneficiario = allText.match(nomeBeneficiarioRegex);
                const cnpjsBeneficiario = allText.match(cnpjBeneficiarioRegex);
                const enderecos = allText.match(enderecoRegex);

                // Preencher a tabela com os dados extraídos
                const resultTable = document.getElementById('resultTable');
                for (let i = 0; i < vencimentos.length; i++) {
                    const row = resultTable.insertRow(-1);
                    row.insertCell(0).textContent = vencimentos[i];
                    row.insertCell(1).textContent = nomesPagador[i];
                    row.insertCell(2).textContent = cpfs[i];
                    row.insertCell(3).textContent = numerosDocumento[i];
                    row.insertCell(4).textContent = valoresDocumento[i];
                    row.insertCell(5).textContent = nomesBeneficiario[i];
                    row.insertCell(6).textContent = cnpjsBeneficiario[i];
                    row.insertCell(7).textContent = enderecos[i];
                }
            };

            reader.readAsArrayBuffer(file);
        }
    </script>
</body>
</html>
