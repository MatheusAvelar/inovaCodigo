<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exemplo de iFrame</title>
</head>

<body>
    <div id="bet365Content"></div>

    <script>
        // Realizar uma solicitação HTTP para obter o conteúdo do site
        fetch('https://www.bet365.com/')
            .then(response => response.text())
            .then(html => {
                // Colocar o conteúdo obtido dentro da div
                document.getElementById('bet365Content').innerHTML = html;
            })
            .catch(error => {
                console.error('Erro ao carregar o conteúdo do site:', error);
            });
    </script>
</body>

</html>