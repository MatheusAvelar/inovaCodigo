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
            <th>V. TOTAL DA NOTA</th>
            <th>CNPJ / CPF</th>
            <th>BASE DE CÁLC. DO ICMS</th>
            <th>VALOR DO ICMS</th>
            <th>INSCRIÇÃO ESTADUAL</th>
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
                const totalNotaRegex = /V\. TOTAL DA NOTA(.+?)\n/g; // V. TOTAL DA NOTA
                const cnpjCpfRegex = /CNPJ \/ CPF(.+?)\n/g; // CNPJ / CPF
                const baseCalcIcmsRegex = /BASE DE CÁLC. DO ICMS(.+?)\n/g; // BASE DE CÁLC. DO ICMS
                const valorIcmsRegex = /VALOR DO ICMS(.+?)\n/g; // VALOR DO ICMS
                const inscricaoEstadualRegex = /INSCRIÇÃO ESTADUAL(.+?)\n/g; // INSCRIÇÃO ESTADUAL

                // Extrair informações usando regex
                const vencimentos = allText.match(dateRegex);
                const totalNota = allText.match(totalNotaRegex);
                const cnpjCpf = allText.match(cnpjCpfRegex);
                const baseCalcIcms = allText.match(baseCalcIcmsRegex);
                const valorIcms = allText.match(valorIcmsRegex);
                const inscricaoEstadual = allText.match(inscricaoEstadualRegex);

                // Preencher a tabela com os dados extraídos
                const resultTable = document.getElementById('resultTable');
                for (let i = 0; i < vencimentos.length; i++) {
                    const row = resultTable.insertRow(-1);
                    row.insertCell(0).textContent = vencimentos[i];
                    row.insertCell(8).textContent = totalNota[i];
                    row.insertCell(9).textContent = cnpjCpf[i];
                    row.insertCell(10).textContent = baseCalcIcms[i];
                    row.insertCell(11).textContent = valorIcms[i];
                    row.insertCell(12).textContent = inscricaoEstadual[i];
                }
            };

            reader.readAsArrayBuffer(file);
        }
    </script>
</body>
</html>