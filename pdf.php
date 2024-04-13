<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Extrair Dados de PDF</title>
    <!-- Inclua o PDF.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.9.359/pdf.min.js"></script>
</head>
<body>
    <h1>Extrair Dados de PDF</h1>
    <input type="file" id="pdfInput" accept=".pdf">
    <button onclick="extractData()">Extrair Dados</button>

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

                const nomeRegex = /Nome do Pagador: (.*) CPF\/CNPJ do Pagador:/;
                const cpfRegex = /CPF\/CNPJ do Pagador: (\d{3}\.\d{3}\.\d{3}-\d{2})/;

                const nomeMatch = text.match(nomeRegex);
                const cpfMatch = text.match(cpfRegex);

                const nomePagador = nomeMatch ? nomeMatch[1] : 'Não encontrado';
                const cpfPagador = cpfMatch ? cpfMatch[1] : 'Não encontrado';

                alert('Nome do Pagador: ' + nomePagador + '\nCPF do Pagador: ' + cpfPagador);
            };

            reader.readAsArrayBuffer(file);
        }
    </script>
</body>
</html>