<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerar Link de Pagamento</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        input, button { padding: 10px; margin-top: 10px; border: 1px solid #ccc; border-radius: 4px; width: 100%; }
        button { background-color: #007bff; color: white; cursor: pointer; }
        .success, .error { margin-top: 10px; padding: 10px; border-radius: 4px; }
        .success { background-color: #28a745; color: white; }
        .error { background-color: #dc3545; color: white; }
        a { display: inline-block; margin-top: 10px; background-color: #28a745; color: white; padding: 10px 15px; text-decoration: none; border-radius: 4px; }
    </style>
</head>
<body>
    <h2>Gerar Link de Pagamento - Valor Dinâmico</h2>
    <form id="paymentForm">
        <label for="valor">Informe o valor da tatuagem (R$):</label>
        <input type="text" id="valor" name="valor" placeholder="Ex: 150.00" required>
        <button type="submit">Gerar Link</button>
    </form>

    <div id="response"></div>

    <script>
        const form = document.getElementById('paymentForm');
        const responseDiv = document.getElementById('response');

        form.addEventListener('submit', async function (e) {
            e.preventDefault();

            const valor = document.getElementById('valor').value;
            responseDiv.innerHTML = 'Gerando link...';

            try {
                const res = await fetch('php/processa_pagamento.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `valor=${encodeURIComponent(valor)}`
                });

                const data = await res.json();

                if (data.success) {
                    responseDiv.innerHTML = `
                        <div class="success">
                            <a href="${data.payment_url}" target="_blank">Ir para o Pagamento</a>
                        </div>`;
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
