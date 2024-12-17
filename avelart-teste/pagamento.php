<?php
if (!file_exists('../stripe-php/init.php')) {
    die('Arquivo ../stripe-php/init.php não encontrado. Verifique o caminho.' . __DIR__ );
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
</body>
</html>
