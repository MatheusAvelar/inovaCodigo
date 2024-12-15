<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagamento via Stripe</title>
    <link rel="stylesheet" href="css/style.css">    
    <link rel="icon" href="img/ico.ico" type="image/x-icon">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f9;
        }

        .payment-container {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        .payment-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 1rem;
        }

        .submit-btn {
            background-color: #28a745;
            color: #fff;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            font-size: 1rem;
            cursor: pointer;
            width: 100%;
        }

        .submit-btn:hover {
            background-color: #218838;
        }

        .error-message {
            color: red;
            font-size: 0.9rem;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="payment-container">
        <h2>Pagamento via Stripe</h2>
        <form id="paymentForm" method="POST" action="php/processar_stripe.php">
            <div class="form-group">
                <label for="name">Nome Completo:</label>
                <input type="text" id="name" name="name" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="amount">Valor (R$):</label>
                <input type="text" id="amount" name="amount" required>
            </div>

            <div class="form-group">
                <label for="description">Descrição do Pagamento:</label>
                <input type="text" id="description" name="description" required>
            </div>

            <div class="form-group">
                <label for="card-element">Informações do Cartão:</label>
                <div id="card-element">
                    <!-- A Stripe Element will be inserted here. -->
                </div>
                <!-- Used to display form errors. -->
                <div id="card-errors" role="alert"></div>
            </div>

            <button type="submit" id="submit" class="submit-btn">Pagar</button>
        </form>
    </div>

    <!-- Stripe.js Script -->
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        var stripe = Stripe('pk_live_51QVXcjDl7Fi26zyyLnlI9rmR4q9UDpWfbkH1ehLnVR0c9Lw4dAGVHddCyk0WSPjoxVGEvYlOfV0pDIsW5J6778Ll00PMbAtB77'); // Substitua com sua chave pública
        var elements = stripe.elements();

        // Criação do Stripe Card Element
        var card = elements.create('card');
        card.mount('#card-element');

        // Handle form submission
        var form = document.getElementById('paymentForm');
        form.addEventListener('submit', function(event) {
            event.preventDefault();

            stripe.createToken(card).then(function(result) {
                if (result.error) {
                    // Exibir erro de forma amigável ao usuário
                    var errorElement = document.getElementById('card-errors');
                    errorElement.textContent = result.error.message;
                } else {
                    // Enviar o token para o servidor para processar o pagamento
                    var token = result.token.id;
                    var name = document.getElementById('name').value;
                    var email = document.getElementById('email').value;
                    var amount = document.getElementById('amount').value;
                    var description = document.getElementById('description').value;

                    var data = {
                        token: token,
                        name: name,
                        email: email,
                        amount: amount,
                        description: description
                    };

                    // Enviar dados para o servidor
                    fetch('php/processar_stripe.php', {
                        method: 'POST',
                        body: JSON.stringify(data),
                        headers: {
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            alert('Pagamento bem-sucedido!');
                        } else {
                            alert('Erro no pagamento: ' + data.message);
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>
