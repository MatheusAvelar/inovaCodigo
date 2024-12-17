<?php
if (!file_exists('../stripe-php/init.php')) {
    die('Arquivo ../stripe-php/init.php não encontrado. Verifique o caminho.' + __DIR__ );
}

// Inclua o Stripe manualmente
require_once 'stripe-php/init.php';

// Defina a chave secreta do Stripe
//\Stripe\Stripe::setApiKey('sk_test_51QVXcjDl7Fi26zyynbuqFrvethFcM92kWyyb98XUeGW16agStI8iswpqtu9TmuxqQDXFxwgwrhCrNlIgUWPmKG1U00ZBGsCFnQ'); // Substitua pela sua chave secreta

// Processamento do pagamento
$success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['stripeToken'] ?? '';

    if ($token) {
        try {
            /*$charge = \Stripe\Charge::create([
                'amount' => 2000, // Valor em centavos (R$20,00)
                'currency' => 'brl',
                'description' => 'Pagamento Simples',
                'source' => $token,
            ]);*/
            $success = true; // Pagamento bem-sucedido
        } catch (Exception $e) {
            $error = $e->getMessage(); // Captura o erro
        }
    } else {
        $error = 'Token do Stripe não foi gerado.';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Stripe</title>
    <script src="https://js.stripe.com/v3/"></script>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        #card-element { border: 1px solid #ccc; padding: 10px; border-radius: 4px; margin-top: 10px; }
        button { margin-top: 20px; padding: 10px 20px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .success { color: green; font-size: 18px; }
        .error { color: red; font-size: 18px; }
    </style>
</head>
<body>
    <h2>Checkout Simples - Stripe</h2>

    <?php if ($success): ?>
        <p class="success">Pagamento realizado com sucesso!</p>
    <?php elseif ($error): ?>
        <p class="error">Erro: <?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <?php if (!$success): ?>
        <form id="payment-form" method="POST">
            <div id="card-element"></div>
            <button type="submit" id="submit">Pagar R$ 20,00</button>
            <p id="error-message"></p>
        </form>
    <?php endif; ?>

    <script>
        const stripe = Stripe("pk_test_51QVXcjDl7Fi26zyyYy3z4WkVJr7CLzkV96c9EVuBlFIsUhnJ3HVlAujoXSEzhBWB8XMVVd7jnLwast5vKPfe0Ss300Wpjvpgsk"); // Substitua pela sua chave pública
        const elements = stripe.elements();
        const cardElement = elements.create("card");

        cardElement.mount("#card-element");

        const form = document.getElementById("payment-form");

        form.addEventListener("submit", async (event) => {
            event.preventDefault();
            const { token, error } = await stripe.createToken(cardElement);

            if (error) {
                document.getElementById("error-message").textContent = error.message;
            } else {
                const hiddenInput = document.createElement("input");
                hiddenInput.setAttribute("type", "hidden");
                hiddenInput.setAttribute("name", "stripeToken");
                hiddenInput.setAttribute("value", token.id);
                form.appendChild(hiddenInput);
                form.submit();
            }
        });
    </script>
</body>
</html>
