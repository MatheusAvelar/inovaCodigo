<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerar Link de Pagamento</title>
    <link rel="stylesheet" href="css/style.css?v=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <div class="container">
        <h2>Gerar Link de Pagamento - PagBank</h2>
        <form id="paymentForm">
            <label for="valor">Informe o valor da tatuagem (R$):</label>
            <input type="text" id="valor" name="valor" placeholder="Ex: 150.00" required>
            <button type="submit">Gerar Link</button>
        </form>
        <div id="response"></div>
    </div>

    <script>
        document.getElementById('paymentForm').addEventListener('submit', async function (e) {
            e.preventDefault();

            const valor = document.getElementById('valor').value;
            const responseDiv = document.getElementById('response');
            responseDiv.innerHTML = 'Gerando link...';

            try {
                const response = await fetch('php/processa_pagamento_pagbank.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `valor=${encodeURIComponent(valor)}`
                });

                const data = await response.json();

                if (data.success) {
                    const paymentUrl = encodeURIComponent(data.payment_url);
                    const whatsappMessage = encodeURIComponent("Aqui está o link para realizar o pagamento da tatuagem: " + data.payment_url);
                    responseDiv.innerHTML = `
                        <a href="https://wa.me/?text=${whatsappMessage}" target="_blank" 
                        style="display: inline-block; margin-top: 10px; text-decoration: none; background-color: #25D366; color: white; padding: 8px 12px; border-radius: 5px; font-weight: bold;">
                            Compartilhar no WhatsApp
                        </a>`;
                } else {
                    responseDiv.innerHTML = `<div class="error">${data.message}</div>`;
                }
            } catch (error) {
                responseDiv.innerHTML = `<div class="error">Erro ao enviar a solicitação: ${error.message}</div>`;
            }
        });
    </script>
</body>

</html>
