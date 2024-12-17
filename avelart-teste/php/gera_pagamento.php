<?php
// Inclua a biblioteca do Stripe manualmente
require_once __DIR__ . '/stripe-php/init.php';

// Defina a chave secreta do Stripe
\Stripe\Stripe::setApiKey('sk_test_51QVXcjDl7Fi26zyynbuqFrvethFcM92kWyyb98XUeGW16agStI8iswpqtu9TmuxqQDXFxwgwrhCrNlIgUWPmKG1U00ZBGsCFnQ'); // Substitua pela sua chave secreta

// Inicialização de variáveis
$paymentLinkUrl = '';
$error = '';

// Processamento do formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $valor = $_POST['valor'] ?? '';

    try {
        // 1. Criação do Produto
        $product = \Stripe\Product::create([
            'name' => 'Tatuagem Personalizada',
            'description' => 'Tatuagem feita sob medida',
        ]);

        // 2. Criação do Preço
        $price = \Stripe\Price::create([
            'unit_amount' => $valor * 100, // Valor em centavos
            'currency' => 'brl',
            'product' => $product->id,
        ]);

        // 3. Criar Link de Pagamento
        $paymentLink = \Stripe\PaymentLink::create([
            'line_items' => [
                [
                    'price' => $price->id,
                    'quantity' => 1,
                ],
            ],
        ]);

        // Exibir o link gerado
        echo 'Link de pagamento: <a href="' . $paymentLink->url . '" target="_blank">Clique aqui para pagar</a>';

    } catch (\Stripe\Exception\ApiErrorException $e) {
        echo 'Erro ao criar o link de pagamento: ' . $e->getMessage();
    }
}
?>