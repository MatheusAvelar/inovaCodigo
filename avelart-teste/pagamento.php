 <!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Pagamento via Stripe</title>
        <link rel="icon" href="img/ico.ico" type="image/x-icon">
    <script src="https://js.stripe.com/v3/"></script> <!-- Stripe JS -->
    <style>
        body { font-family: Arial, sans-serif; }
        .error-message { color: red; font-size: 0.9rem; }
    </style>
</head>
<body>
    <div>
        <h2>Pagamento via Stripe</h2>
        <form id="paymentForm">
            <div>
                <label for="card-element">Informações do Cartão:</label>
                <div id="card-element">
                    <!-- Elemento do cartão será inserido aqui -->
                </div>
                <div class="error-message" id="error-message"></div>
            </div>
            <button type="submit">Pagar</button>
        </form>
    </div>

    <!-- Stripe.js Script -->
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Inicialize o Stripe
            var stripe = Stripe('pk_test_51QVXcjDl7Fi26zyyYy3z4WkVJr7CLzkV96c9EVuBlFIsUhnJ3HVlAujoXSEzhBWB8XMVVd7jnLwast5vKPfe0Ss300Wpjvpgsk'); // Substitua pela sua chave pública de teste
            var elements = stripe.elements();

            // Prepare o formulário e o elemento de cartão
            var style = {
                base: {
                    color: "#32325d",
                    lineHeight: "24px",
                    fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                    fontSmoothing: "antialiased",
                    fontSize: "16px",
                    "::placeholder": {
                        color: "#aab7c4"
                    }
                }
            };

            var card = elements.create("card", { style: style });
            card.mount("#card-element");

            // Manipulador de envio do formulário
            var form = document.getElementById('paymentForm');
            form.addEventListener('submit', function (event) {
                event.preventDefault();

                stripe.createToken(card).then(function (result) {
                    if (result.error) {
                        // Exibir erro no frontend
                        document.getElementById('error-message').textContent = result.error.message;
                    } else {
                        // Enviar o token para o servidor
                        var formData = new FormData(form);
                        formData.append('stripeToken', result.token.id);

                        fetch('php/processar_stripe.php', {
                            method: 'POST',
                            body: formData,
                        })
                        .then(response => response.json())  // Espera um JSON válido do servidor
                        .then(data => {
                            // Se a resposta for sucesso
                            if (data.status === 'success') {
                                alert('Pagamento realizado com sucesso!');
                            } else {
                                // Exibir erro
                                alert('Erro: ' + data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Erro na comunicação com o servidor:', error);
                            alert('Erro na comunicação com o servidor. Tente novamente.');
                        });
                    }
                });
            });
        });

    </script>
</body>
</html>
